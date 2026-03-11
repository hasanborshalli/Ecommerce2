@extends('layouts.app')

@section('title', ($product->meta_title ?? $product->name . ' — ' . $siteName))
@section('meta_description', $product->meta_description ?? $product->short_description ?? '')
@if($product->main_image)
@section('og_image', Storage::url($product->main_image))
@endif

@section('content')
<div class="container">

    {{-- Breadcrumb --}}
    <nav class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('shop') }}">Shop</a>
        <span class="breadcrumb-sep">›</span>
        @if($product->category)
        <a href="{{ route('shop', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
        <span class="breadcrumb-sep">›</span>
        @endif
        <span class="breadcrumb-current">{{ $product->name }}</span>
    </nav>

    {{-- ── Product layout ───────────────────────────────── --}}
    <div class="product-layout">

        {{-- Gallery --}}
        <div class="product-gallery">
            <div class="product-main-image">
                <img src="{{ $product->main_image ? Storage::url($product->main_image) : asset('images/placeholder-product.png') }}"
                    alt="{{ $product->name }}" id="mainProductImage">
            </div>

            @php
            $gallery = $product->gallery ?? [];
            @endphp

            @if($product->main_image || count($gallery) > 0)
            <div class="product-thumbs">
                {{-- Main image thumb --}}
                @if($product->main_image)
                <div class="product-thumb active" id="thumb-main"
                    onclick="switchImage('{{ Storage::url($product->main_image) }}', this)">
                    <img src="{{ Storage::url($product->main_image) }}" alt="{{ $product->name }}">
                </div>
                @endif
                {{-- Gallery thumbs --}}
                @foreach($gallery as $i => $img)
                <div class="product-thumb" id="thumb-{{ $i }}" onclick="switchImage('{{ Storage::url($img) }}', this)">
                    <img src="{{ Storage::url($img) }}" alt="{{ $product->name }} gallery {{ $i + 1 }}">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="product-info">

            @if($product->category)
            <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="product-category-link">{{
                $product->category->name }}</a>
            @endif

            <h1 class="product-title">{{ $product->name }}</h1>

            {{-- Price --}}
            <div class="product-price-row">
                <span class="product-price{{ $product->is_on_sale && $product->sale_price ? ' on-sale' : '' }}">
                    {{ $currencySymbol }}{{ number_format($product->effective_price, 2) }}
                </span>
                @if($product->is_on_sale && $product->sale_price)
                <span class="product-original-price">
                    {{ $currencySymbol }}{{ number_format($product->price, 2) }}
                </span>
                <span class="badge badge-sale">Save {{ $product->discount_percent }}%</span>
                @endif
            </div>

            {{-- Short description --}}
            @if($product->short_description)
            <p class="product-short-desc">{{ $product->short_description }}</p>
            @endif

            {{-- Stock notice --}}
            <div class="stock-notice">
                @if($product->is_out_of_stock)
                <span class="stock-dot red"></span>
                <span style="color:var(--danger);font-size:var(--text-sm)">Out of stock</span>
                @elseif($product->is_low_stock)
                <span class="stock-dot orange"></span>
                <span style="color:var(--warning);font-size:var(--text-sm)">Only {{ $product->stock }} left</span>
                @else
                <span class="stock-dot green"></span>
                <span style="color:var(--success);font-size:var(--text-sm)">In stock</span>
                @endif
            </div>

            {{-- Variant selectors --}}
            @if(!empty($product->variants))
            <div id="variantSelectors" style="margin-top:var(--sp-5)">
                @foreach($product->variants as $optionName => $optionValues)
                <div class="variant-group">
                    <div class="variant-label">
                        {{ $optionName }}
                        <span id="selected-{{ Str::slug($optionName) }}">— Select</span>
                    </div>
                    <div class="variant-options">
                        @foreach($optionValues as $val)
                        <button type="button" class="variant-btn" data-group="{{ Str::slug($optionName) }}"
                            data-option="{{ $optionName }}" data-value="{{ $val }}" onclick="selectVariant(this)">
                            {{ $val }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Qty + Add to cart --}}
            @if(!$product->is_out_of_stock)
            <div class="add-to-cart-row">
                <div class="qty-control">
                    <button type="button" onclick="changeQty(-1)" aria-label="Decrease quantity">−</button>
                    <input type="number" class="qty-input" id="productQty" value="1" min="1" max="{{ $product->stock }}"
                        readonly>
                    <button type="button" onclick="changeQty(1)" aria-label="Increase quantity">+</button>
                </div>
                <button class="btn btn-primary" id="addToCartBtn" onclick="handleAddToCart(this)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 01-8 0" />
                    </svg>
                    Add to Cart
                </button>
            </div>

            {{-- Variant validation message --}}
            @if(!empty($product->variants))
            <div id="variantError"
                style="display:none;color:var(--danger);font-size:var(--text-sm);margin-top:var(--sp-2)">
                Please select all options before adding to cart.
            </div>
            @endif

            @else
            <div style="margin-top:var(--sp-6)">
                <button class="btn btn-outline btn-full" disabled>Out of Stock</button>
                @if($product->show_when_out_of_stock)
                <p style="font-size:var(--text-xs);color:var(--text-muted);text-align:center;margin-top:var(--sp-2)">
                    Check back soon
                </p>
                @endif
            </div>
            @endif

            {{-- Trust badges --}}
            <div
                style="display:flex;gap:var(--sp-4);margin-top:var(--sp-6);padding-top:var(--sp-5);border-top:1px solid var(--border);flex-wrap:wrap">
                <div
                    style="display:flex;align-items:center;gap:var(--sp-2);font-size:var(--text-xs);color:var(--text-muted)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 3h15l-1.5 9h-12z" />
                        <circle cx="9" cy="20" r="2" />
                        <circle cx="17" cy="20" r="2" />
                        <path d="M16 12h5l2 7H15" />
                    </svg>
                    Free shipping over {{ $currencySymbol }}{{ number_format($freeShippingOver ?? 150) }}
                </div>
                <div
                    style="display:flex;align-items:center;gap:var(--sp-2);font-size:var(--text-xs);color:var(--text-muted)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="23 7 13.5 15.5 8.5 10.5 1 17" />
                        <polyline points="17 7 23 7 23 13" />
                    </svg>
                    30-day returns
                </div>
                <div
                    style="display:flex;align-items:center;gap:var(--sp-2);font-size:var(--text-xs);color:var(--text-muted)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" />
                        <line x1="1" y1="10" x2="23" y2="10" />
                    </svg>
                    Cash on delivery
                </div>
            </div>

            {{-- Product tabs --}}
            <div class="product-tabs">
                <div class="product-tab-list" role="tablist">
                    @if($product->description)
                    <button class="product-tab-btn active" role="tab"
                        onclick="switchTab(this, 'tab-desc')">Description</button>
                    @endif
                    <button class="product-tab-btn{{ $product->description ? '' : ' active' }}" role="tab"
                        onclick="switchTab(this, 'tab-shipping')">Shipping</button>
                    <button class="product-tab-btn" role="tab" onclick="switchTab(this, 'tab-returns')">Returns</button>
                </div>

                @if($product->description)
                <div class="product-tab-panel active" id="tab-desc" role="tabpanel">
                    {!! $product->description !!}
                </div>
                @endif

                <div class="product-tab-panel{{ $product->description ? '' : ' active' }}" id="tab-shipping"
                    role="tabpanel">
                    <p>We ship to all cities nationwide. Orders are processed within 1–2 business days.</p>
                    <ul>
                        <li>Standard shipping: 2–5 business days</li>
                        <li>Free shipping on orders over {{ $currencySymbol }}{{ number_format($freeShippingOver ?? 150)
                            }}</li>
                        <li>Cash on delivery available</li>
                    </ul>
                </div>

                <div class="product-tab-panel" id="tab-returns" role="tabpanel">
                    <p>We want you to love your purchase. If you're not completely satisfied, we accept returns within
                        30 days.</p>
                    <ul>
                        <li>Items must be unused and in original packaging</li>
                        <li>Contact us within 30 days of delivery</li>
                        <li>Refunds processed within 5–7 business days</li>
                    </ul>
                </div>
            </div>

        </div>{{-- /product-info --}}
    </div>{{-- /product-layout --}}

    {{-- ── Related products ─────────────────────────────── --}}
    @if($related->count())
    <section style="padding:var(--sp-12) 0 var(--sp-16)">
        <div style="border-top:1px solid var(--border);padding-top:var(--sp-10)">
            <div class="section-header" style="margin-bottom:var(--sp-6)">
                <h2 class="display-sm">You May Also Like</h2>
                @if($product->category)
                <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="btn btn-outline">
                    View Category
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6" />
                    </svg>
                </a>
                @endif
            </div>
            <div class="product-grid">
                @foreach($related as $rel)
                @include('partials.product-card', ['product' => $rel])
                @endforeach
            </div>
        </div>
    </section>
    @endif

</div>{{-- /container --}}
@endsection

@push('scripts')
<script>
    const productId   = {{ $product->id }};
const hasVariants = {{ !empty($product->variants) ? 'true' : 'false' }};
const maxStock    = {{ $product->stock }};

// ── Gallery ──────────────────────────────────────────────
function switchImage(src, thumbEl) {
    document.getElementById('mainProductImage').src = src;
    document.querySelectorAll('.product-thumb').forEach(t => t.classList.remove('active'));
    thumbEl?.classList.add('active');
}

// ── Qty ──────────────────────────────────────────────────
function changeQty(delta) {
    const input = document.getElementById('productQty');
    if (!input) return;
    const newVal = Math.min(Math.max(1, parseInt(input.value) + delta), maxStock);
    input.value = newVal;
}

// ── Variants ─────────────────────────────────────────────
const selectedVariants = {};

function selectVariant(btn) {
    const group  = btn.dataset.group;
    const option = btn.dataset.option;
    const value  = btn.dataset.value;

    // Deselect others in same group
    document.querySelectorAll(`.variant-btn[data-group="${group}"]`)
        .forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    selectedVariants[option] = value;

    const label = document.getElementById('selected-' + group);
    if (label) label.textContent = '— ' + value;
}

function getSelectedVariants() {
    return selectedVariants;
}

function allVariantsSelected() {
    if (!hasVariants) return true;
    const groups = document.querySelectorAll('[data-group]');
    const groupNames = new Set([...groups].map(g => g.dataset.option));
    for (const name of groupNames) {
        if (!selectedVariants[name]) return false;
    }
    return true;
}

// ── Add to cart ───────────────────────────────────────────
function handleAddToCart(btn) {
    const errEl = document.getElementById('variantError');
    if (!allVariantsSelected()) {
        if (errEl) errEl.style.display = 'block';
        return;
    }
    if (errEl) errEl.style.display = 'none';
    const qty = parseInt(document.getElementById('productQty')?.value ?? 1);
    addToCart(productId, qty, getSelectedVariants(), btn);
}

// ── Tabs ─────────────────────────────────────────────────
function switchTab(btn, panelId) {
    document.querySelectorAll('.product-tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.product-tab-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById(panelId)?.classList.add('active');
}
</script>
@endpush