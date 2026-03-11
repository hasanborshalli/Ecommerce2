@extends('layouts.app')

@section('title', ($settings['meta_title'] ?? ($siteName . ' — ' . ($settings['site_tagline'] ?? 'Premium Goods'))))
@section('meta_description', $settings['meta_description'] ?? '')

@section('content')

{{-- ── Hero Slider ───────────────────────────────────────── --}}
<section class="hero-slider">
    <div class="hero-slides">

        @forelse($heroSlides as $i => $slide)
        <div class="hero-slide{{ $i === 0 ? ' active' : '' }}" id="slide{{ $i }}">
            <div class="hero" style="min-height:640px">
                @if($slide->image)
                <div class="hero-bg" style="background-image:url('{{ Storage::url($slide->image) }}')"></div>
                @else
                {{-- Fallback gradient --}}
                <div class="hero-bg"
                    style="background:linear-gradient(135deg,var(--navy) 0%,var(--navy-mid) 50%,var(--navy-light) 100%)">
                </div>
                @endif
                <div class="hero-overlay" style="background:{{ $slide->overlay_color ?? 'rgba(11,22,41,0.55)' }}"></div>

                <div class="container">
                    <div class="hero-content">
                        <div class="hero-eyebrow">
                            <span class="hero-eyebrow-line"></span>
                            New Collection
                        </div>
                        <h1 class="hero-title">{{ $slide->headline }}</h1>
                        @if($slide->subheadline)
                        <p class="hero-subtitle">{{ $slide->subheadline }}</p>
                        @endif
                        <div class="hero-actions">
                            @if($slide->button_url && $slide->button_text)
                            <a href="{{ $slide->button_url }}" class="btn btn-blue btn-lg">
                                {{ $slide->button_text }}
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                    <polyline points="12 5 19 12 12 19" />
                                </svg>
                            </a>
                            @endif
                            <a href="{{ route('shop') }}" class="btn hero-btn-secondary btn-lg">
                                Browse All
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        {{-- Default hero if no slides seeded --}}
        <div class="hero-slide active">
            <div class="hero" style="min-height:640px">
                <div class="hero-bg"
                    style="background:linear-gradient(135deg,var(--navy) 0%,var(--navy-mid) 60%,#1a3a5c 100%)"></div>
                <div class="hero-overlay" style="background:rgba(11,22,41,0.3)"></div>
                <div class="container">
                    <div class="hero-content">
                        <div class="hero-eyebrow"><span class="hero-eyebrow-line"></span> New Collection</div>
                        <h1 class="hero-title">Built <em>Different</em>.</h1>
                        <p class="hero-subtitle">Premium products engineered for people who refuse to settle.</p>
                        <div class="hero-actions">
                            <a href="{{ route('shop') }}" class="btn btn-blue btn-lg">Shop Now <svg width="16"
                                    height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                    <polyline points="12 5 19 12 12 19" />
                                </svg></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforelse

    </div>

    {{-- Dot controls (only if more than 1 slide) --}}
    @if($heroSlides->count() > 1)
    <div class="hero-controls" id="heroControls">
        @foreach($heroSlides as $i => $slide)
        <button class="hero-dot{{ $i === 0 ? ' active' : '' }}" onclick="goToSlide({{ $i }})"
            aria-label="Go to slide {{ $i + 1 }}"></button>
        @endforeach
    </div>
    @endif
</section>

{{-- ── Category strip ────────────────────────────────────── --}}
@if($categories->count())
<section class="category-strip">
    <div class="container">
        <div class="category-strip-grid">
            <a href="{{ route('shop') }}" class="category-chip{{ !request('category') ? ' active' : '' }}">
                <div class="category-chip-img" style="display:flex;align-items:center;justify-content:center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                    </svg>
                </div>
                <span class="category-chip-name">All</span>
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="category-chip">
                @if($cat->image)
                <img src="{{ Storage::url($cat->image) }}" alt="{{ $cat->name }}" class="category-chip-img">
                @else
                <div class="category-chip-img" style="display:flex;align-items:center;justify-content:center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                    </svg>
                </div>
                @endif
                <span class="category-chip-name">{{ $cat->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Featured Products ─────────────────────────────────── --}}
@if($featured->count())
<section class="section-pad">
    <div class="container">
        <div class="section-header">
            <div>
                <div class="label-overline">Handpicked</div>
                <h2 class="display-sm" style="margin-top:var(--sp-1)">Featured Products</h2>
            </div>
            <a href="{{ route('shop') }}" class="btn btn-outline">
                View All
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6" />
                </svg>
            </a>
        </div>
        <div class="product-grid">
            @foreach($featured as $product)
            @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Promo Banner ──────────────────────────────────────── --}}
<section class="promo-banner">
    <div class="container">
        <h2>Free Shipping Over {{ $currencySymbol }}{{ number_format($freeShippingOver ?? 150) }}</h2>
        <p>Every order ships fast. Cash on delivery available nationwide.</p>
        <a href="{{ route('shop') }}" class="btn btn-promo">Start Shopping</a>
    </div>
</section>

{{-- ── New Arrivals ──────────────────────────────────────── --}}
@if($newArrivals->count())
<section class="section-pad">
    <div class="container">
        <div class="section-header">
            <div>
                <div class="label-overline">Just dropped</div>
                <h2 class="display-sm" style="margin-top:var(--sp-1)">New Arrivals</h2>
            </div>
            <a href="{{ route('shop', ['filter' => 'new']) }}" class="btn btn-outline">
                See All New
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6" />
                </svg>
            </a>
        </div>
        <div class="product-grid">
            @foreach($newArrivals as $product)
            @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── On Sale ───────────────────────────────────────────── --}}
@if($onSale->count())
<section class="section-pad" style="background:var(--gray-50)">
    <div class="container">
        <div class="section-header">
            <div>
                <div class="label-overline">Limited time</div>
                <h2 class="display-sm" style="margin-top:var(--sp-1)">On Sale</h2>
            </div>
            <a href="{{ route('shop', ['filter' => 'sale']) }}" class="btn btn-outline">
                View All Deals
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6" />
                </svg>
            </a>
        </div>
        <div class="product-grid">
            @foreach($onSale as $product)
            @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Trust strip ───────────────────────────────────────── --}}
<section style="background:var(--navy);padding:var(--sp-10) 0">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:var(--sp-6);text-align:center">

            @php
            $trustItems = [
            ['icon'=>'M5 12h14M12 5l7 7-7 7','label'=>'Fast Shipping','sub'=>'2–5 business days'],
            ['icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','label'=>'Quality Guarantee','sub'=>'Every product
            tested'],
            ['icon'=>'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003
            3z','label'=>'Cash on Delivery','sub'=>'Pay when it arrives'],
            ['icon'=>'M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z','label'=>'Easy
            Returns','sub'=>'30-day return policy'],
            ];
            @endphp

            @foreach($trustItems as $trust)
            <div>
                <div
                    style="width:48px;height:48px;background:rgba(37,99,235,0.15);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;margin:0 auto var(--sp-3)">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="1.75"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="{{ $trust['icon'] }}" />
                    </svg>
                </div>
                <div
                    style="font-size:var(--text-sm);font-weight:var(--weight-semibold);color:var(--white);margin-bottom:var(--sp-1)">
                    {{ $trust['label'] }}</div>
                <div style="font-size:var(--text-xs);color:rgba(255,255,255,0.5)">{{ $trust['sub'] }}</div>
            </div>
            @endforeach

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Hero slider
(function() {
    let current = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.hero-dot');
    if (slides.length <= 1) return;

    window.goToSlide = function(n) {
        slides[current]?.classList.remove('active');
        dots[current]?.classList.remove('active');
        current = n;
        slides[current]?.classList.add('active');
        dots[current]?.classList.add('active');
    };

    // Auto-advance every 5s
    setInterval(() => goToSlide((current + 1) % slides.length), 5000);
})();
</script>
@endpush