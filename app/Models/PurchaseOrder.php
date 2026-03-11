<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'supplier_id', 'reference_number', 'order_date',
        'received_date', 'total_cost', 'status', 'notes',
    ];

    protected $casts = [
        'order_date'    => 'date',
        'received_date' => 'date',
        'total_cost'    => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────

    public static function generateReference(): string
    {
        $year  = date('Y');
        $last  = static::whereYear('created_at', $year)->count();
        return 'PO-' . $year . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    // Recalculate total_cost from items and save
    public function recalculateTotal(): void
    {
        $this->update([
            'total_cost' => $this->items()->sum('total_cost'),
        ]);
    }

    /**
     * Mark all items as received, update product stock + cost,
     * and set status to 'received'.
     */
    public function markReceived(): void
    {
        foreach ($this->items()->with('product')->get() as $item) {
            $item->update(['quantity_received' => $item->quantity_ordered]);

            $item->product->addStock(
                $item->quantity_ordered,
                (float) $item->cost_per_unit,
                'purchase_order',
                $this->id,
                "PO {$this->reference_number}"
            );
        }

        $this->update([
            'status'        => 'received',
            'received_date' => now()->toDateString(),
        ]);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'draft'    => ['label' => 'Draft',    'class' => 'badge-neutral'],
            'ordered'  => ['label' => 'Ordered',  'class' => 'badge-info'],
            'partial'  => ['label' => 'Partial',  'class' => 'badge-warning'],
            'received' => ['label' => 'Received', 'class' => 'badge-success'],
            default    => ['label' => 'Unknown',  'class' => 'badge-neutral'],
        };
    }
}