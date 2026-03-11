<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'sku',
        'short_description', 'description',
        'price', 'sale_price', 'cost_price',
        'stock', 'low_stock_threshold', 'show_when_out_of_stock',
        'main_image', 'gallery', 'variants',
        'is_active', 'is_featured', 'is_new', 'is_on_sale',
        'meta_title', 'meta_description', 'meta_keywords',
        'sort_order',
    ];

    protected $casts = [
        'price'                  => 'decimal:2',
        'sale_price'             => 'decimal:2',
        'cost_price'             => 'decimal:2',
        'gallery'                => 'array',
        'variants'               => 'array',
        'is_active'              => 'boolean',
        'is_featured'            => 'boolean',
        'is_new'                 => 'boolean',
        'is_on_sale'             => 'boolean',
        'show_when_out_of_stock' => 'boolean',
    ];

    // ── Relationships ────────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    // ── Accessors ────────────────────────────────────────────────────

    // Current selling price (respects sale flag)
    public function getEffectivePriceAttribute(): float
    {
        return ($this->is_on_sale && $this->sale_price)
            ? (float) $this->sale_price
            : (float) $this->price;
    }

    // Margin percentage: (price − cost) / price × 100
    public function getMarginPercentAttribute(): float
    {
        if ($this->price <= 0 || $this->cost_price <= 0) return 0;
        return round((($this->effective_price - $this->cost_price) / $this->effective_price) * 100, 1);
    }

    // Profit per unit
    public function getProfitPerUnitAttribute(): float
    {
        return round($this->effective_price - (float) $this->cost_price, 2);
    }

    // Discount percentage when on sale
    public function getDiscountPercentAttribute(): int
    {
        if ($this->is_on_sale && $this->sale_price && $this->price > 0) {
            return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->stock <= 0;
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->low_stock_threshold;
    }

    // Stock status label for admin
    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0)                   return 'out_of_stock';
        if ($this->stock <= $this->low_stock_threshold) return 'low_stock';
        return 'in_stock';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ── Scopes ───────────────────────────────────────────────────────

    // Visible on storefront: active + (in stock OR show_when_out_of_stock)
    public function scopeVisible($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->where('stock', '>', 0)
                           ->orWhere('show_when_out_of_stock', true);
                     });
    }

    // Can actually be added to cart (active + in stock)
    public function scopeOrderable($query)
    {
        return $query->where('is_active', true)->where('stock', '>', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->visible()->where('is_featured', true);
    }

    public function scopeNewArrivals($query)
    {
        return $query->visible()->where('is_new', true);
    }

    public function scopeOnSale($query)
    {
        return $query->visible()->where('is_on_sale', true);
    }

    public function scopeLowStock($query)
    {
        return $query->where('is_active', true)
                     ->where('stock', '>', 0)
                     ->whereColumn('stock', '<=', 'low_stock_threshold');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('is_active', true)->where('stock', '<=', 0);
    }

    // ── Stock helpers ────────────────────────────────────────────────

    /**
     * Increment stock and log the movement.
     * Called when a purchase order is received.
     */
    public function addStock(
        int    $qty,
        float  $unitCost   = 0,
        string $refType    = 'manual',
        ?int   $refId      = null,
        string $notes      = ''
    ): StockMovement {
        $before = $this->stock;
        $after  = $before + $qty;

        $this->update([
            'stock'      => $after,
            'cost_price' => $unitCost > 0 ? $unitCost : $this->cost_price,
        ]);

        return StockMovement::create([
            'product_id'     => $this->id,
            'type'           => 'in',
            'quantity'       => $qty,
            'stock_before'   => $before,
            'stock_after'    => $after,
            'reference_type' => $refType,
            'reference_id'   => $refId,
            'unit_cost'      => $unitCost,
            'notes'          => $notes,
        ]);
    }

    /**
     * Decrement stock and log the movement.
     * Called when an order is placed.
     */
    public function deductStock(
        int    $qty,
        string $refType = 'order',
        ?int   $refId   = null,
        string $notes   = ''
    ): StockMovement {
        $before = $this->stock;
        $after  = max(0, $before - $qty);

        $this->update(['stock' => $after]);

        return StockMovement::create([
            'product_id'     => $this->id,
            'type'           => 'out',
            'quantity'       => $qty,
            'stock_before'   => $before,
            'stock_after'    => $after,
            'reference_type' => $refType,
            'reference_id'   => $refId,
            'unit_cost'      => $this->cost_price,
            'notes'          => $notes,
        ]);
    }

    // ── Report helpers ───────────────────────────────────────────────

    public function totalUnitsSold(): int
    {
        return (int) $this->orderItems()
            ->whereHas('order', fn($q) => $q->where('status', '!=', 'cancelled'))
            ->sum('quantity');
    }

    public function totalRevenue(): float
    {
        return (float) $this->orderItems()
            ->whereHas('order', fn($q) => $q->where('status', '!=', 'cancelled'))
            ->sum('line_total');
    }

    public function totalProfit(): float
    {
        return (float) $this->orderItems()
            ->whereHas('order', fn($q) => $q->where('status', '!=', 'cancelled'))
            ->sum('line_profit');
    }

    public function totalStockPurchased(): int
    {
        return (int) $this->stockMovements()
            ->where('type', 'in')
            ->sum('quantity');
    }

    public function totalStockCost(): float
    {
        return (float) $this->stockMovements()
            ->where('type', 'in')
            ->selectRaw('SUM(quantity * unit_cost) as total')
            ->value('total') ?? 0;
   }
}