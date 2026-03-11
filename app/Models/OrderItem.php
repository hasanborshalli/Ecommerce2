<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id',
        'product_name', 'product_sku', 'product_price', 'product_cost',
        'quantity', 'variant',
        'line_total', 'line_cost', 'line_profit',
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'product_cost'  => 'decimal:2',
        'line_total'    => 'decimal:2',
        'line_cost'     => 'decimal:2',
        'line_profit'   => 'decimal:2',
        'variant'       => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getVariantLabelAttribute(): string
    {
        $variant = $this->variant;

        if (empty($variant)) return '';

        // Guard: if the DB stored a plain string instead of JSON, return it as-is
        if (!is_array($variant)) return (string) $variant;

        $parts = [];
        foreach ($variant as $k => $v) {
            $parts[] = is_string($k) ? ucfirst($k) . ': ' . $v : (string) $v;
        }
        return implode(', ', $parts);
    }
}