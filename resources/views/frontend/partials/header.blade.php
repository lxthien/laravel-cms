{{-- ============================================================
HEADER – 3-tier structure
1. Top Bar : Contact info + Social links
2. Brand Row : Logo + Company name/address + Phone CTA
3. Nav Bar : Main menu + Search (sticky)
============================================================ --}}
<header>
    {{-- ── 1. TOP BAR ──────────────────────────────────────────── --}}
    <div class="header-topbar">
        <div class="topbar-inner">
            {{-- Contact info --}}
            <div class="topbar-contact">
                <a href="tel:{{ setting('phone', '0123456789') }}" class="topbar-link">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>{{ setting('contact_phone') }}</span>
                </a>

                <a href="mailto:{{ setting('contact_email') }}" class="topbar-link topbar-link--email">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2z" />
                    </svg>
                    <span>{{ setting('contact_email') }}</span>
                </a>
            </div>

            {{-- Social icons --}}
            <div class="topbar-social" aria-label="Mạng xã hội">
                @if(setting('facebook_url'))
                    <a href="{{ setting('facebook_url') }}" target="_blank" rel="noopener noreferrer" class="social-icon"
                        aria-label="Facebook">
                        <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                        </svg>
                    </a>
                @endif
                @if(setting('instagram_url'))
                    <a href="{{ setting('instagram_url') }}" target="_blank" rel="noopener noreferrer" class="social-icon"
                        aria-label="Instagram">
                        <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                        </svg>
                    </a>
                @endif
                @if(setting('linkedin_url'))
                    <a href="{{ setting('linkedin_url') }}" target="_blank" rel="noopener noreferrer" class="social-icon"
                        aria-label="LinkedIn">
                        <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                        </svg>
                    </a>
                @endif
                @if(setting('tiktok_url'))
                    <a href="{{ setting('tiktok_url') }}" target="_blank" rel="noopener noreferrer" class="social-icon"
                        aria-label="TikTok">
                        <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12.525.02c1.31-.02 2.61-.01 3.91.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 2.78-1.15 5.54-3.33 7.35-2.22 1.83-5.27 2.65-8.1 1.95-2.81-.69-5.18-2.67-6.28-5.35C-1.82 18.06-1.55 14.86.35 12.37c1.47-1.92 3.86-3.14 6.27-3.33 1.01-.08 2.03.04 3.02.24v4.06c-.84-.13-1.7-.1-2.52.09-1.39.31-2.58 1.25-3.18 2.53-.78 1.63-.37 3.65 1.03 4.8 1.43 1.15 3.51 1.34 5.12.44 1.34-.73 2.15-2.15 2.15-3.66 0-5.39.01-10.79-.02-16.18.31-.01.63-.02.95-.02z" />
                        </svg>
                    </a>
                @endif
                @if(setting('twitter_url'))
                    <a href="{{ setting('twitter_url') }}" target="_blank" rel="noopener noreferrer" class="social-icon"
                        aria-label="Twitter">
                        <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- ── 2. BRAND ROW ────────────────────────────────────────── --}}
    <div class="header-brand">
        <div class="brand-inner">
            {{-- Logo + Company identity --}}
            <div class="brand-identity">
                <a href="{{ route('home') }}" class="brand-logo" aria-label="Trang chủ">
                    <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="{{ config('app.name') }}">
                </a>
                <div class="brand-info">
                    <a href="{{ route('home') }}" class="brand-name">
                        {{ config('app.name') }}
                    </a>
                    @if(setting('contact_address'))
                        <address class="brand-address">
                            {{ setting('contact_address', 'Hà Nội, Việt Nam') }}
                        </address>
                    @endif
                </div>
            </div>

            {{-- CTA Phone button --}}
            <a href="tel:{{ setting('contact_phone', '0123456789') }}" class="brand-cta">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span>{{ setting('contact_phone', '0123 456 789') }}</span>
            </a>
        </div>
    </div>

    {{-- ── 3. STICKY NAV BAR ───────────────────────────────────── --}}
    <nav class="header-navbar" aria-label="Menu chính">
        <div class="navbar-inner">
            {{-- Desktop menu --}}
            <div class="navbar-desktop">
                <x-menu :items="$headerMenu ? $headerMenu->rootItems : collect()" class="nav-menu" />
            </div>

            {{-- Search box --}}
            <div class="navbar-search-desktop" id="search-container">
                <div class="navbar-search">
                    <form action="{{ route('search') }}" method="GET" class="search-form">
                        <label for="header-search-input" class="sr-only">Tìm kiếm</label>
                        <input type="text" name="q" id="header-search-input" placeholder="Tìm kiếm..."
                            autocomplete="off" data-suggestions-url="{{ route('search.suggestions') }}"
                            data-search-url="{{ route('search') }}" class="search-input">
                        <button type="submit" class="search-btn" aria-label="Tìm kiếm">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>

                    {{-- Search suggestions dropdown --}}
                    <div id="search-suggestions" class="search-suggestions is-hidden">
                        <div id="suggestions-loading" class="suggestion-status is-hidden">
                            Đang tìm kiếm...
                        </div>
                        <div id="suggestions-list"></div>
                        <div id="suggestions-empty" class="suggestion-status is-hidden">
                            Không tìm thấy kết quả
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mobile: hamburger + search --}}
            <div class="mobile-bar">
                <button type="button" id="mobile-menu-toggle" class="mobile-toggle" aria-label="Mở menu"
                    aria-expanded="false" aria-controls="mobile-menu">
                    <svg class="icon" id="mobile-menu-icon-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="icon is-hidden" id="mobile-menu-icon-close" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="navbar-search">
                    <form action="{{ route('search') }}" method="GET" class="search-form">
                        <label for="mobile-search-input" class="sr-only">Tìm kiếm</label>
                        <input type="text" name="q" id="mobile-search-input" placeholder="Tìm kiếm..."
                            class="search-input" style="width: 9rem;">
                        <button type="submit" class="search-btn" aria-label="Tìm kiếm">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Mobile menu panel --}}
        <div id="mobile-menu" class="mobile-menu is-hidden">
            <div class="mobile-menu-inner">
                <x-menu :items="$headerMenu ? $headerMenu->rootItems : collect()" class="nav-menu-mobile" />
            </div>
        </div>
    </nav>
</header>