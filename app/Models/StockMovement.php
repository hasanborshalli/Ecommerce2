<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id', 'type', 'quantity',
        'stock_before', 'stock_after',
        'reference_type', 'reference_id',
        'unit_cost', 'notes',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Human-readable movement type
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'in'         => 'Stock In',
            'out'        => 'Stock Out',
            'adjustment' => 'Adjustment',
            default      => 'Unknown',
        };
    }

    // Reference label (e.g. "Purchase Order #PO-2024-0001" or "Order #ORD-001")
    public function getReferenceLabelAttribute(): string
    {
        return match ($this->reference_type) {
            'purchase_order' => 'Purchase Order',
            'order'          => 'Sale Order',
            'manual'         => 'Manual Adjustment',
            'return'         => 'Customer Return',
            default          => 'System',
        };
    }

    // Total value of this movement
    public function getMovementValueAttribute(): float
    {
        return round($this->quantity * (float) $this->unit_cost, 2);
    }
}