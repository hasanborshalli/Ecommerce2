<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — Admin · {{ $siteName ?? 'Vaulted' }}</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
</head>

<body>

    <div class="admin-layout">

        {{-- ── Sidebar overlay (mobile) ──────────────────────── --}}
        <div class="admin-sidebar-overlay" id="adminOverlay" onclick="closeSidebar()"></div>
        {{-- ── Sidebar ─────────────────────────────────────────── --}}
        <aside class="admin-sidebar" id="adminSidebar">

            {{-- Logo --}}
            <a href="{{ route('admin.dashboard') }}" class="admin-logo">
                <div class="admin-logo-mark">V</div>
                <span class="admin-logo-name">{{ $siteName ?? 'Vaulted' }}</span>
                <span class="admin-logo-badge">ADMIN</span>
            </a>

            {{-- Nav --}}
            <nav class="admin-nav">

                {{-- Overview --}}
                <div class="admin-nav-section">
                    <div class="admin-nav-label">Overview</div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="admin-nav-item{{ request()->routeIs('admin.dashboard') ? ' active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.75">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                        Dashboard
                    </a>
                </div>

                {{-- Catalogue --}}
                <div class="admin-nav-section">
                    <div class="admin-nav-label">Catalogue</div>
                    <a href="{{ route('admin.products.index') }}"
                        class="admin-nav-item{{ request()->routeIs('admin.products.*') ? ' active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.75">
                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 01-8 0" />
                        </svg>
                        Products
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                        class="admin-nav-item{{ request()->routeIs('admin.categories.*') ? ' active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.75">
                            <path d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        Categories
                    </a>
                </div>

                {{-- Sales --}}
                <div class="admin-nav-section">
                    <div class="admin-nav-label">Sales</div>
                    <a href="{{ route('admin.orders.index') }}"
                        class="admin-nav-item{{ request()->routeIs('admin.orders.*') ? ' active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.75">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                            <polyline points="10 9 9 9 8 9" />
                        </svg>
                        Orders
                    </a>
                </div>

                {{-- Inventory --}}
                <div class="admin-nav-section">
                    <div class="admin-nav-label">Inventory</div>
                    <a href="{{ route('admin.stock.index') }}"
                        class="admin-nav-item{{ request()->routeIs('admin.stock.*') ? ' active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.75">
                            <line x1="8" y1="6" x2="21" y2="6" />
                            <line x1="8" y1="12" x2="21" y2="12" />
                            <line x1="8" y1="18" x2="21" y2="18" />
                            <line x1="3" y1="6" x2="3.01" y2="6" />
                            <line x1="3" y1="12" x2="3.01" y2="12" />
                            <line x1="3" y1="18" x2="3.01" y2="18" />
                        </svg>
                        Stock
                        @php
                        $lowStockCount = \App\Models\Product::lowStock()->count();
                        @endphp
                        @if($lowStockCount > 0)
                        <span class="nav-count warning">{{ $lowStockCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.purchase_orders.index') }}"
                        class="admin-nav-item{{ request()->routeIs('admin.purchase_orders.*') ? ' active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.75">
                            <rect x="1" y="3" width="15" height="13" />
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                            <circle cx="5.5" cy="18.5" r="2.5" />
                            <circle cx="18.5" cy="18.5" r="2.5" />
                        </svg>
                        Purchase Orders
                    </a>
                    <a href="{{ route('admin.suppliers.index') }}"
                        class="admin-nav-item{{ request()->routeIs('admin.suppliers.*') ? ' active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.75">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 00-3-3.87" />
                            <path d="M16 3.13a4 4 0 010 7.75" />
                        </svg>
                        Suppliers
                    </a>
                </div>

                {{-- Insights --}}
                <div class="admin-nav-section">
                    <div class="admin-nav-label">Insights</div>
                    <a href="{{ route('admin.reports.index') }}"
                        class="admin-nav-item{{ request()->routeIs('admin.reports.*') ? ' active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.75">
                            <line x1="18" y1="20" x2="18" y2="10" />
                            <line x1="12" y1="20" x2="12" y2="4" />
                            <line x1="6" y1="20" x2="6" y2="14" />
                        </svg>
                        Reports
                    </a>
                </div>

                {{-- Admin --}}
                <div class="admin-nav-section">
                    <div class="admin-nav-label">Admin</div>
                    <a href="{{ route('admin.messages.index') }}"
                        class="admin-nav-item{{ request()->routeIs('admin.messages.*') ? ' active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.75">
                            <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
                        </svg>
                        Messages
                        @if(($unreadMessages ?? 0) > 0)
                        <span class="nav-count">{{ $unreadMessages }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.settings') }}"
                        class="admin-nav-item{{ request()->routeIs('admin.settings') ? ' active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.75">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                        </svg>
                        Settings
                    </a>
                    <a href="{{ route('home') }}" target="_blank" class="admin-nav-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.75">
                            <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6" />
                            <polyline points="15 3 21 3 21 9" />
                            <line x1="10" y1="14" x2="21" y2="3" />
                        </svg>
                        View Store
                    </a>
                </div>

            </nav>

            {{-- Sidebar footer --}}
            <div class="admin-sidebar-footer">
                <div class="admin-user-row">
                    <div class="admin-avatar">A</div>
                    <div>
                        <div class="admin-user-name">Administrator</div>
                        <div class="admin-user-role">{{ config('admin.email') }}</div>
                    </div>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-logout">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>

        </aside>

        {{-- ── Main ──────────────────────────────────────────────── --}}
        <div class="admin-main">

            {{-- Topbar --}}
            <header class="admin-topbar">
                <div style="display:flex;align-items:center;gap:var(--sp-3)">
                    {{-- Mobile toggle --}}
                    <button type="button" class="admin-mobile-toggle" id="sidebarToggle" onclick="toggleSidebar()"
                        aria-label="Toggle sidebar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                    </button>
                    <div>
                        <div class="admin-page-title">@yield('page_title', 'Dashboard')</div>
                        @hasSection('breadcrumb')
                        <div class="admin-breadcrumb">@yield('breadcrumb')</div>
                        @endif
                    </div>
                </div>

                <div class="admin-topbar-right">
                    {{-- Low stock alert --}}
                    @php $lowCount = \App\Models\Product::lowStock()->count(); @endphp
                    @if($lowCount > 0)
                    <a href="{{ route('admin.stock.index') }}" class="topbar-alert">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            <line x1="12" y1="9" x2="12" y2="13" />
                            <line x1="12" y1="17" x2="12.01" y2="17" />
                        </svg>
                        {{ $lowCount }} low stock
                    </a>
                    @endif
                    {{-- Unread messages --}}
                    @if(($unreadMessages ?? 0) > 0)
                    <a href="{{ route('admin.messages.index') }}" class="topbar-alert"
                        style="background:var(--blue-light);border-color:var(--blue-mid);color:var(--blue)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z" />
                        </svg>
                        {{ $unreadMessages }} new
                    </a>
                    @endif
                </div>
            </header>

            {{-- Page content --}}
            <main class="admin-content">
                @include('partials.flash')
                @yield('content')
            </main>

        </div>{{-- /admin-main --}}

    </div>{{-- /admin-layout --}}

    {{-- ── Admin JS ──────────────────────────────────────────── --}}
    @stack('scripts')

    <script>
        const adminSidebar = document.getElementById('adminSidebar');
    const adminOverlay = document.getElementById('adminOverlay');

    function openSidebar() {
        adminSidebar.classList.add('open');
        adminOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        adminSidebar.classList.remove('open');
        adminOverlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    function toggleSidebar() {
        if (adminSidebar.classList.contains('open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    }

    window.addEventListener('resize', function () {
        if (window.innerWidth > 900) {
            closeSidebar();
        }
    });
    </script>

</body>

</html>