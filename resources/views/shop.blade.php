@extends('layouts.app')

@section('title', ($category ? $category->name . ' — ' : '') . 'Shop — ' . $siteName)
@section('meta_description', $category?->meta_description ?? $settings['meta_description'] ?? '')

@section('content')

{{-- ── Shop Header ───────────────────────────────────────── --}}
<div class="shop-header">
    <div class="container">

        {{-- Breadcrumb --}}
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span class="breadcrumb-sep">›</span>
            @if($category)
            <a href="{{ route('shop') }}">Shop</a>
            <span class="breadcrumb-sep">›</span>
            <span class="breadcrumb-current">{{ $category->name }}</span>
            @else
            <span class="breadcrumb-current">Shop</span>
            @endif
        </nav>

        {{-- Title --}}
        <div class="shop-title-row">
            <h1 class="display-sm">{{ $category ? $category->name : 'All Products' }}</h1>
            <span class="shop-count">{{ $products->total() }} {{ Str::plural('product', $products->total()) }}</span>
        </div>

        {{-- Filter bar --}}
        <div class="filter-bar-wrap">
            <div class="filter-bar" id="filterBar">

                {{-- Category pills --}}
                <a href="{{ route('shop') }}"
                    class="filter-pill{{ !request('category') && !request('filter') ? ' active' : '' }}">
                    All
                </a>

                @foreach($categories as $cat)
                <a href="{{ route('shop', ['category' => $cat->slug] + request()->except(['category','page'])) }}"
                    class="filter-pill{{ request('category') === $cat->slug ? ' active' : '' }}">
                    {{ $cat->name }}
                    @if(request('category') === $cat->slug)
                    <span class="pill-x">×</span>
                    @endif
                </a>
                @endforeach

                <div class="filter-divider"></div>

                <a href="{{ route('shop', ['filter' => 'new'] + request()->except(['filter','page'])) }}"
                    class="filter-pill{{ request('filter') === 'new' ? ' active' : '' }}">
                    New
                    @if(request('filter') === 'new') <span class="pill-x">×</span> @endif
                </a>
                <a href="{{ route('shop', ['filter' => 'sale'] + request()->except(['filter','page'])) }}"
                    class="filter-pill{{ request('filter') === 'sale' ? ' active' : '' }}">
                    On Sale
                    @if(request('filter') === 'sale') <span class="pill-x">×</span> @endif
                </a>

                {{-- Sort --}}
                <select class="sort-select" onchange="applySort(this.value)">
                    <option value="" {{ !request('sort') ? 'selected' : '' }}>Sort: Featured</option>
                    <option value="newest" {{ request('sort')==='newest' ? 'selected' : '' }}>Newest</option>
                    <option value="price_asc" {{ request('sort')==='price_asc' ? 'selected' : '' }}>Price: Low → High
                    </option>
                    <option value="price_desc" {{ request('sort')==='price_desc' ? 'selected' : '' }}>Price: High → Low
                    </option>
                    <option value="name" {{ request('sort')==='name' ? 'selected' : '' }}>Name A–Z</option>
                </select>

                {{-- Clear all filters --}}
                @if(request()->hasAny(['category','filter','sort','search','min_price','max_price']))
                <a href="{{ route('shop') }}" class="filter-pill"
                    style="background:rgba(220,38,38,0.08);color:#DC2626;border-color:rgba(220,38,38,0.3)">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                    Clear filters
                </a>
                @endif

            </div>
        </div>{{-- /filter-bar-wrap --}}
    </div>
</div>

{{-- ── Product grid ──────────────────────────────────────── --}}
<div class="shop-body">
    <div class="container">

        @if($products->count())
        <div class="product-grid">
            @foreach($products as $product)
            @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>

        {{-- Pagination --}}
        {{ $products->links() }}

        @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 8h32l6 16H10L16 8z" />
                    <rect x="4" y="24" width="56" height="32" rx="2" />
                    <line x1="32" y1="36" x2="32" y2="44" />
                    <line x1="28" y1="40" x2="36" y2="40" />
                </svg>
            </div>
            <h3>No products found</h3>
            <p>Try adjusting your filters or browse all products.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary">Clear Filters</a>
        </div>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
    function applySort(value) {
    const url = new URL(window.location.href);
    if (value) url.searchParams.set('sort', value);
    else        url.searchParams.delete('sort');
    url.searchParams.delete('page');
    window.location.href = url.toString();
}
</script>
@endpush