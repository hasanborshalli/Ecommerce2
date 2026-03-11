{{--
Partial: product-card
Usage: @include('partials.product-card', ['product' => $product])
Expects: $product (Product model), $currencySymbol (from ViewServiceProvider)
--}}

@php
$effectivePrice = $product->effective_price;
$isOnSale = $product->is_on_sale && $product->sale_price;
$isOutOfStock = $product->is_out_of_stock;
$isLowStock = $product->is_low_stock;
$hasVariants = !empty($product->variants);
$currSym = $currencySymbol ?? '$';
@endphp

<div class="product-card">
    {{-- Image --}}
    <a href="{{ route('product.show', $product->slug) }}" class="product-card-image">
        @if($product->main_image)
        <img src="{{ Storage::url($product->main_image) }}" alt="{{ $product->name }}" loading="lazy">
        @else
        <img src="{{ asset('images/placeholder-product.png') }}" alt="{{ $product->name }}" loading="lazy">
        @endif

        {{-- Badges --}}
        <div class="product-card-badges">
            @if($isOnSale)
            <span class="badge badge-sale">−{{ $product->discount_percent }}%</span>
            @endif
            @if($product->is_new && !$isOnSale)
            <span class="badge badge-new">New</span>
            @endif
            @if($isOutOfStock)
            <span class="badge badge-out">Out of stock</span>
            @elseif($isLowStock)
            <span class="badge badge-low-stock">Low stock</span>
            @endif
        </div>

        {{-- Quick add overlay --}}
        @if($isOutOfStock)
        <div class="product-card-quick out-of-stock">Out of Stock</div>
        @elseif($hasVariants)
        <div class="product-card-quick">
            Select Options
        </div>
        @else
        <button class="product-card-quick" onclick="event.preventDefault(); addToCart({{ $product->id }}, 1, {}, this)"
            type="button">
            Add to Cart
        </button>
        @endif
    </a>

    {{-- Body --}}
    <div class="product-card-body">
        <div class="product-card-category">{{ $product->category->name ?? '' }}</div>
        <div class="product-card-name">
            <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
        </div>
        <div class="product-card-price">
            <span class="price-current{{ $isOnSale ? ' price-sale' : '' }}">
                {{ $currSym }}{{ number_format($effectivePrice, 2) }}
            </span>
            @if($isOnSale)
            <span class="price-original">{{ $currSym }}{{ number_format($product->price, 2) }}</span>
            @endif
            @if($isOutOfStock)
            <span class="price-out-of-stock">— Out of stock</span>
            @endif
        </div>
    </div>
</div>