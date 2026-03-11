<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'image',
        'is_active', 'sort_order',
        'meta_title', 'meta_description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts(): HasMany
    {
        return $this->hasMany(Product::class)
                    ->where('is_active', true);
    }

    // Route model binding by slug
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Total revenue from this category (for reports)
    public function totalRevenue(): float
    {
        return (float) OrderItem::whereHas('product', fn($q) => $q->where('category_id', $this->id))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->sum('order_items.line_total');
    }

    // Total profit from this category (for reports)
    public function totalProfit(): float
    {
        return (float) OrderItem::whereHas('product', fn($q) => $q->where('category_id', $this->id))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->sum('order_items.line_profit');
    }
}