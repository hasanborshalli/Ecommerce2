<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'shipping_city',
        'subtotal', 'shipping_cost', 'discount', 'total', 'cost_total',
        'status', 'payment_status', 'payment_method',
        'notes',
    ];

    protected $casts = [
        'subtotal'     => 'decimal:2',
        'shipping_cost'=> 'decimal:2',
        'discount'     => 'decimal:2',
        'total'        => 'decimal:2',
        'cost_total'   => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────

    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD-' . date('Ymd') . '-';
        $last   = static::where('order_number', 'like', $prefix . '%')->count();
        return $prefix . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    // Profit = total revenue − cost of goods − (shipping is a pass-through)
    public function getProfitAttribute(): float
    {
        return round((float) $this->total - (float) $this->cost_total, 2);
    }

    public function getProfitMarginAttribute(): float
    {
        if ((float) $this->total <= 0) return 0;
        return round(($this->profit / (float) $this->total) * 100, 1);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'pending'    => ['label' => 'Pending',    'class' => 'badge-warning'],
            'confirmed'  => ['label' => 'Confirmed',  'class' => 'badge-info'],
            'processing' => ['label' => 'Processing', 'class' => 'badge-info'],
            'shipped'    => ['label' => 'Shipped',    'class' => 'badge-primary'],
            'delivered'  => ['label' => 'Delivered',  'class' => 'badge-success'],
            'cancelled'  => ['label' => 'Cancelled',  'class' => 'badge-danger'],
            default      => ['label' => 'Unknown',    'class' => 'badge-neutral'],
        };
    }

    public function getPaymentBadgeAttribute(): array
    {
        return match ($this->payment_status) {
            'paid'     => ['label' => 'Paid',     'class' => 'badge-success'],
            'unpaid'   => ['label' => 'Unpaid',   'class' => 'badge-warning'],
            'refunded' => ['label' => 'Refunded', 'class' => 'badge-danger'],
            default    => ['label' => 'Unknown',  'class' => 'badge-neutral'],
        };
    }
}