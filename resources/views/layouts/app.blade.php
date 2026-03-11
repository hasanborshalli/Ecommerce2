<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $siteName ?? 'Vaulted')</title>
    <meta name="description" content="@yield('meta_description', $settings['meta_description'] ?? '')">
    <meta name="keywords" content="@yield('meta_keywords', '')">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('title', $siteName ?? 'Vaulted')">
    <meta property="og:description" content="@yield('meta_description', $settings['meta_description'] ?? '')">
    <meta property="og:type" content="website">
    @hasSection('og_image')
    <meta property="og:image" content="@yield('og_image')">
    @endif

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">

    @stack('styles')
</head>

<body>

    {{-- ── Announcement bar ──────────────────────────────────── --}}
    @if(!empty($settings['announcement_text']))
    <div class="announcement-bar">
        {{ $settings['announcement_text'] }}
        @if(!empty($settings['announcement_link']))
        <a href="{{ $settings['announcement_link'] }}">{{ $settings['announcement_link_text'] ?? 'Learn more' }}</a>
        @endif
    </div>
    @else
    <div class="announcement-bar">
        Free shipping on orders over {{ $currencySymbol ?? '$' }}{{ number_format($freeShippingOver ?? 150) }}
        &nbsp;·&nbsp; Cash on delivery available
    </div>
    @endif

    {{-- ── Navigation ───────────────────────────────────────── --}}
    <div class="nav-wrap" id="navWrap">
        <div class="container">
            <div class="nav-inner">

                {{-- Left: hamburger (mobile) / logo (desktop) --}}
                <div style="display:flex;align-items:center;gap:var(--sp-2)">
                    <button class="nav-hamburger" id="hamburgerBtn" aria-label="Open menu" aria-expanded="false">
                        <span></span><span></span><span></span>
                    </button>
                    <a href="{{ route('home') }}" class="nav-logo">
                        @if(!empty($settings['site_logo']))
                        <img src="{{ Storage::url($settings['site_logo']) }}" alt="{{ $siteName }}">
                        @else
                        <span class="nav-logo-text">{{ $siteName ?? 'Vaulted' }}</span><span
                            class="nav-logo-dot">.</span>
                        @endif
                    </a>
                </div>

                {{-- Center: desktop nav links --}}
                <nav class="nav-links" aria-label="Main navigation">
                    <a href="{{ route('home') }}"
                        class="nav-link{{ request()->routeIs('home') ? ' active' : '' }}">Home</a>

                    {{-- Shop with dropdown --}}
                    <div class="nav-dropdown">
                        <button class="nav-dropdown-trigger" type="button" aria-haspopup="true">
                            Shop
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </button>
                        <div class="nav-dropdown-panel" role="menu">
                            <a href="{{ route('shop') }}" class="nav-dropdown-item" role="menuitem">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2" />
                                    <line x1="8" y1="21" x2="16" y2="21" />
                                    <line x1="12" y1="17" x2="12" y2="21" />
                                </svg>
                                All Products
                            </a>
                            @foreach($navCategories as $navCat)
                            <a href="{{ route('shop', ['category' => $navCat->slug]) }}" class="nav-dropdown-item"
                                role="menuitem">
                                {{ $navCat->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <a href="{{ route('about') }}"
                        class="nav-link{{ request()->routeIs('about') ? ' active' : '' }}">About</a>
                    <a href="{{ route('contact') }}"
                        class="nav-link{{ request()->routeIs('contact') ? ' active' : '' }}">Contact</a>
                </nav>

                {{-- Right: cart --}}
                <div class="nav-actions">
                    <a href="{{ route('cart.index') }}" class="nav-icon-btn" aria-label="Cart">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 01-8 0" />
                        </svg>
                        @if(($cartCount ?? 0) > 0)
                        <span class="cart-badge" id="cartBadge">{{ $cartCount }}</span>
                        @else
                        <span class="cart-badge" id="cartBadge" style="display:none">0</span>
                        @endif
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- ── Mobile menu ───────────────────────────────────────── --}}
    <div class="mobile-menu" id="mobileMenu" role="dialog" aria-modal="true" aria-label="Navigation menu">
        <div class="mobile-menu-overlay" id="mobileOverlay"></div>
        <div class="mobile-menu-panel">
            <div class="mobile-menu-header">
                <span style="font-weight:700;font-size:var(--text-lg)">{{ $siteName ?? 'Vaulted' }}<span
                        style="color:var(--blue)">.</span></span>
                <button class="mobile-menu-close" id="mobileClose" aria-label="Close menu">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
            <nav class="mobile-menu-nav">
                <a href="{{ route('home') }}" class="mobile-nav-link">Home</a>
                <div>
                    <a href="{{ route('shop') }}" class="mobile-nav-link" id="mobileCatsToggle" style="cursor:pointer">
                        Shop
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" id="mobileCatsIcon">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </a>
                    <div class="mobile-nav-sub" id="mobileCatsSub">
                        @foreach($navCategories as $navCat)
                        <a href="{{ route('shop', ['category' => $navCat->slug]) }}">{{ $navCat->name }}</a>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('about') }}" class="mobile-nav-link">About</a>
                <a href="{{ route('contact') }}" class="mobile-nav-link">Contact</a>
                <a href="{{ route('cart.index') }}" class="mobile-nav-link">
                    Cart
                    @if(($cartCount ?? 0) > 0)
                    <span class="badge badge-new" style="font-size:10px">{{ $cartCount }}</span>
                    @endif
                </a>
            </nav>
        </div>
    </div>

    {{-- ── Page content ──────────────────────────────────────── --}}
    <main id="main-content">
        @yield('content')
    </main>

    {{-- ── Footer ────────────────────────────────────────────── --}}
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">

                {{-- Brand column --}}
                <div>
                    <div class="footer-brand-name">{{ $siteName ?? 'Vaulted' }}<span class="dot">.</span></div>
                    <p class="footer-about">
                        {{ $settings['footer_about'] ?? 'Premium goods for people who value quality over quantity.' }}
                    </p>
                    <div class="footer-social">
                        @if(!empty($socialLinks['instagram']))
                        <a href="{{ $socialLinks['instagram'] }}" target="_blank" rel="noopener" aria-label="Instagram">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="2" y="2" width="20" height="20" rx="5" />
                                <circle cx="12" cy="12" r="4" />
                                <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" />
                            </svg>
                        </a>
                        @endif
                        @if(!empty($socialLinks['facebook']))
                        <a href="{{ $socialLinks['facebook'] }}" target="_blank" rel="noopener" aria-label="Facebook">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                            </svg>
                        </a>
                        @endif
                        @if(!empty($socialLinks['twitter']))
                        <a href="{{ $socialLinks['twitter'] }}" target="_blank" rel="noopener" aria-label="Twitter/X">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Shop column --}}
                <div class="footer-col">
                    <h4>Shop</h4>
                    <ul>
                        <li><a href="{{ route('shop') }}">All Products</a></li>
                        @foreach($navCategories->take(5) as $navCat)
                        <li><a href="{{ route('shop', ['category' => $navCat->slug]) }}">{{ $navCat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                {{-- Company column --}}
                <div class="footer-col">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>

                {{-- Contact column --}}
                <div class="footer-col">
                    <h4>Get in Touch</h4>
                    <ul>
                        @if(!empty($settings['site_phone']))
                        <li><a href="tel:{{ $settings['site_phone'] }}">{{ $settings['site_phone'] }}</a></li>
                        @endif
                        @if(!empty($settings['site_email']))
                        <li><a href="mailto:{{ $settings['site_email'] }}">{{ $settings['site_email'] }}</a></li>
                        @endif
                        @if(!empty($settings['site_address']))
                        <li><span style="color:rgba(255,255,255,0.5);font-size:var(--text-sm)">{{
                                $settings['site_address'] }}</span></li>
                        @endif
                    </ul>
                </div>

            </div>

            <div class="footer-bottom">
                <span>© {{ date('Y') }} {{ $siteName ?? 'Vaulted' }}. All rights reserved.</span>
                <span class="powered-by">Powered by <span>brndng.</span></span>
            </div>
        </div>
    </footer>

    {{-- ── Toast notification ────────────────────────────────── --}}
    <div class="site-toast" id="siteToast" role="status" aria-live="polite">
        <span class="site-toast-icon">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                <polyline points="20 6 9 17 4 12" />
            </svg>
        </span>
        <span id="siteToastMsg">Added to cart</span>
    </div>

    {{-- ── JavaScript ────────────────────────────────────────── --}}
    <script>
        (function() {

    // ── Toast ──────────────────────────────────────────────
    window.showToast = function(msg, duration) {
        const toast = document.getElementById('siteToast');
        const msgEl = document.getElementById('siteToastMsg');
        if (!toast || !msgEl) return;
        msgEl.textContent = msg || 'Done';
        toast.classList.add('show');
        clearTimeout(toast._timer);
        toast._timer = setTimeout(() => toast.classList.remove('show'), duration || 2800);
    };

    // ── Cart badge update ──────────────────────────────────
    window.updateCartBadge = function(count) {
        const badge = document.getElementById('cartBadge');
        if (!badge) return;
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    };

    // ── Add to cart (AJAX) ─────────────────────────────────
    window.addToCart = function(productId, quantity, variant, btn) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (btn) {
            btn.disabled = true;
            btn.dataset.originalText = btn.textContent;
            btn.textContent = 'Adding…';
        }
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                product_id: productId,
                quantity:   quantity || 1,
                variant:    variant  || {},
            }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Added to cart ✓');
                updateCartBadge(data.cart_count);
            } else {
                showToast(data.message || 'Could not add to cart');
            }
        })
        .catch(() => showToast('Something went wrong'))
        .finally(() => {
            if (btn) {
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = btn.dataset.originalText || 'Add to Cart';
                }, 600);
            }
        });
    };

    // ── Sticky nav shadow ──────────────────────────────────
    const navWrap = document.getElementById('navWrap');
    if (navWrap) {
        window.addEventListener('scroll', () => {
            navWrap.classList.toggle('scrolled', window.scrollY > 40);
        }, { passive: true });
    }

    // ── Mobile menu ────────────────────────────────────────
    const mobileMenu    = document.getElementById('mobileMenu');
    const hamburgerBtn  = document.getElementById('hamburgerBtn');
    const mobileClose   = document.getElementById('mobileClose');
    const mobileOverlay = document.getElementById('mobileOverlay');

    function openMobileMenu() {
        mobileMenu?.classList.add('open');
        document.body.style.overflow = 'hidden';
        hamburgerBtn?.setAttribute('aria-expanded', 'true');
    }
    function closeMobileMenu() {
        mobileMenu?.classList.remove('open');
        document.body.style.overflow = '';
        hamburgerBtn?.setAttribute('aria-expanded', 'false');
    }

    hamburgerBtn?.addEventListener('click', openMobileMenu);
    mobileClose?.addEventListener('click', closeMobileMenu);
    mobileOverlay?.addEventListener('click', closeMobileMenu);

    // Mobile categories sub-toggle
    const catsToggle = document.getElementById('mobileCatsToggle');
    const catsSub    = document.getElementById('mobileCatsSub');
    const catsIcon   = document.getElementById('mobileCatsIcon');
    catsToggle?.addEventListener('click', (e) => {
        if (e.target.tagName === 'A' && !e.target.closest('.mobile-nav-sub')) {
            // allow navigation on "Shop" text itself only if clicked directly
        }
        catsSub?.classList.toggle('open');
        if (catsIcon) catsIcon.style.transform = catsSub?.classList.contains('open')
            ? 'rotate(180deg)' : 'rotate(0)';
    });

    // ESC to close
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeMobileMenu();
    });

})();
    </script>

    @stack('scripts')

</body>

</html>