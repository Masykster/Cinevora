<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Cinevora - Booking Cinema & Cafe')">

    <title>@yield('title', 'Cinevora')</title>
    <link rel="icon" href="{{ asset('images/cinevora-logo.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://utxnzonzcdwpefdqnykc.supabase.co" crossorigin>
    <link rel="preconnect" href="https://image.tmdb.org" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Using BoxIcons for mobile nav icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            /* High-Contrast Immersive Dark Theme */
            --clr-bg: #000000;
            --clr-surface: #0a0a0a;
            --clr-surface-2: #121212;
            --clr-surface-3: #1c1c1e;
            --clr-border: #1f1f23;
            --clr-border-dark: #2d2d30;
            
            /* Premium XXI-Inspired Gold Theme combined with Cinevora */
            --clr-primary: #BCA374; 
            --clr-primary-light: #d4bc90;
            --clr-primary-dim: rgba(188, 163, 116, 0.08);
            
            --clr-accent: #BCA374;
            --clr-accent-light: #d4bc90;
            
            /* Text Hierarchy */
            --clr-text: #ffffff;
            --clr-text-muted: #8d8f99;
            
            --clr-success: #10B981;
            --clr-error: #EF4444;
            
            /* Rectangular styling */
            --radius-sm: 4px;
            --radius: 8px;
            --radius-lg: 12px;
            --radius-full: 9999px;
            
            --font-heading: 'Oswald', sans-serif;
            --font-body: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif;
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            
            --nav-height: 72px;
            --bottom-nav-height: 64px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* Sleek custom scrollbars */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #000000;
        }
        ::-webkit-scrollbar-thumb {
            background: #2a2a2a;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--clr-primary);
        }

        body {
            font-family: var(--font-body);
            background: var(--clr-bg);
            color: var(--clr-text);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            width: 100%;
        }

        /* === TOP NAVBAR === */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: transparent;
            border-bottom: 1px solid transparent;
            height: var(--nav-height);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem;
            transition: var(--transition);
        }
        .navbar.navbar-scrolled {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .navbar-brand {
            font-family: var(--font-heading);
            font-size: 1.8rem; font-weight: 700;
            color: var(--clr-text);
            text-decoration: none;
            letter-spacing: 0.5px;
            text-transform: lowercase;
            display: flex; align-items: center; gap: 0.1rem;
        }
        
        .navbar-brand span {
            color: var(--clr-primary);
        }

        .location-pill {
            display: flex; align-items: center; gap: 0.4rem;
            background: var(--clr-surface-2); border: 1px solid var(--clr-border);
            padding: 0.45rem 1rem; border-radius: var(--radius);
            font-size: 0.75rem; font-weight: 700; color: var(--clr-text);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            user-select: none;
        }
        .location-pill:hover {
            background: var(--clr-surface-3);
            border-color: var(--clr-primary);
        }
        .location-pill .pill-chevron {
            font-size: 1rem;
            transition: transform 0.2s ease;
        }
        .location-pill.open .pill-chevron {
            transform: rotate(180deg);
        }

        /* City Dropdown */
        .city-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
            background: var(--clr-surface-2);
            border: 1px solid var(--clr-border);
            border-radius: var(--radius);
            min-width: 200px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.8);
            z-index: 200;
            opacity: 0;
            visibility: hidden;
            transform: translateX(-50%) translateY(-8px);
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
            overflow: hidden;
        }
        .city-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }
        .city-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.7rem 1.1rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--clr-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-family: var(--font-heading);
            cursor: pointer;
            transition: var(--transition);
            border-bottom: 1px solid var(--clr-border);
        }
        .city-dropdown-item:last-child {
            border-bottom: none;
        }
        .city-dropdown-item:hover {
            background: var(--clr-surface-3);
            color: #fff;
        }
        .city-dropdown-item.active {
            color: var(--clr-primary);
            background: var(--clr-primary-dim);
        }
        .city-dropdown-item .check-icon {
            font-size: 1rem;
            opacity: 0;
        }
        .city-dropdown-item.active .check-icon {
            opacity: 1;
        }

        /* Desktop specific nav right side */
        .desktop-nav {
            display: flex; align-items: center; gap: 1.5rem;
        }

        .desktop-nav a.nav-link {
            font-family: var(--font-heading);
            color: var(--clr-text-muted); text-decoration: none;
            font-weight: 600; font-size: 1.05rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            transition: var(--transition);
        }
        .desktop-nav a.nav-link:hover, .desktop-nav a.nav-link.active {
            color: var(--clr-primary);
        }

        /* === BUTTONS === */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.65rem 1.5rem; border-radius: var(--radius);
            font-family: var(--font-heading); font-size: 0.9rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1px;
            text-decoration: none; cursor: pointer; border: none;
            transition: var(--transition);
        }
        .btn-primary {
            background: var(--clr-primary); color: #000000;
        }
        .btn-primary:active, .btn-primary:hover { 
            background: var(--clr-primary-light); 
            box-shadow: 0 4px 20px rgba(188, 163, 116, 0.35);
        }
        
        .btn-outline {
            background: transparent; color: var(--clr-primary);
            border: 2px solid var(--clr-primary);
            padding: 0.55rem 1.4rem;
        }
        .btn-outline:active, .btn-outline:hover { 
            background: var(--clr-primary); 
            color: #000000; 
            box-shadow: 0 4px 15px rgba(188, 163, 116, 0.2);
        }
        
        .btn-ghost { background: transparent; color: var(--clr-text-muted); }
        .btn-ghost:active, .btn-ghost:hover { color: var(--clr-text); background: var(--clr-surface-2); }
        
        .btn-danger { background: var(--clr-error); color: #fff; }
        .btn-sm { padding: 0.45rem 1.15rem; font-size: 0.75rem; }
        .btn-block { width: 100%; }

        /* === CARDS === */
        .card {
            background: var(--clr-surface);
            border: 1px solid var(--clr-border);
            border-radius: var(--radius);
            box-shadow: 0 10px 30px rgba(0,0,0,0.8);
            overflow: hidden;
        }

        /* === FORM ELEMENTS === */
        .form-group { margin-bottom: 1rem; }
        .form-label {
            display: block; font-size: 0.75rem; font-weight: 700;
            color: var(--clr-text-muted); margin-bottom: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 0.8rem 1.1rem;
            background: var(--clr-surface-2); border: 1px solid var(--clr-border);
            border-radius: var(--radius); color: var(--clr-text);
            font-family: var(--font-body); font-size: 0.9rem; outline: none;
            transition: var(--transition);
        }
        .form-input:focus, .form-select:focus { 
            border-color: var(--clr-primary); 
            box-shadow: 0 0 0 2px var(--clr-primary-dim); 
        }

        /* === ALERTS === */
        .alert {
            padding: 1rem 1.25rem; border-radius: var(--radius);
            font-size: 0.85rem; margin-bottom: 1rem;
            display: flex; align-items: flex-start; gap: 0.75rem;
            border: 1px solid transparent;
        }
        .alert-success { background: #062419; border-color: #0d5339; color: #52e5a4; }
        .alert-error { background: #2a0808; border-color: #5c1818; color: #ff8c8c; }
        .badge-yellow { background: #3b1e06; border-color: #693c0d; color: #ffb66c; }

        /* === BADGE === */
        .badge {
            display: inline-flex; align-items: center; padding: 0.2rem 0.6rem;
            border-radius: var(--radius-sm); font-size: 0.7rem; font-weight: 700;
            background: var(--clr-surface-3); color: var(--clr-text);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-age {
            background: #111; color: #fff; border: 1px solid var(--clr-border-dark);
            border-radius: var(--radius-sm); padding: 2px 6px; font-weight: 800; font-size: 0.7rem;
        }
        .badge-rating {
            background: var(--clr-primary); color: #000; border-radius: var(--radius-sm);
        }

        /* === GRID SYSTEM === */
        .grid { display: grid; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        .gap-1 { gap: 0.5rem; }
        .gap-2 { gap: 1rem; }
        .gap-3 { gap: 1.5rem; }
        .gap-4 { gap: 2rem; }

        /* === LAYOUT === */
        .main-content { 
            padding-top: var(--nav-height); 
            min-height: calc(100vh - var(--nav-height) - var(--bottom-nav-height));
            padding-bottom: 3rem;
        }
        .container { width: 100%; max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }
        .section { padding-top: 2.5rem; padding-bottom: 2.5rem; }

        /* === MOBILE BOTTOM NAV === */
        .bottom-nav {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 100;
            background: #080808;
            border-top: 1px solid var(--clr-border);
            height: var(--bottom-nav-height);
            display: flex; justify-content: space-around; align-items: center;
            padding-bottom: env(safe-area-inset-bottom);
            display: none; /* Hidden on desktop */
        }

        .bottom-nav-item {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            color: var(--clr-text-muted); text-decoration: none;
            gap: 4px; width: 100%; height: 100%;
            transition: var(--transition);
        }
        .bottom-nav-item i { font-size: 1.35rem; }
        .bottom-nav-item span { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .bottom-nav-item.active { color: var(--clr-primary); }

        /* === UTILITIES === */
        .text-center { text-align: center; }
        .text-muted { color: var(--clr-text-muted); }
        .text-primary { color: var(--clr-primary); }
        .text-sm { font-size: 0.85rem; }
        .text-xs { font-size: 0.75rem; }
        .font-heading { font-family: var(--font-heading); }
        .font-bold { font-weight: 700; }
        .mt-1 { margin-top: 0.5rem; } .mb-1 { margin-bottom: 0.5rem; }
        .mt-2 { margin-top: 1rem; } .mb-2 { margin-bottom: 1rem; }
        .mt-3 { margin-top: 1.5rem; } .mb-3 { margin-bottom: 1.5rem; }
        
        /* Typography overrides */
        h1, h2, h3, h4 { color: var(--clr-text); font-family: var(--font-heading); text-transform: uppercase; letter-spacing: 0.5px; }

        /* Responsiveness */
        @media (max-width: 768px) {
            .desktop-nav { display: none; }
            .bottom-nav { display: flex; }
            .main-content { padding-bottom: calc(var(--bottom-nav-height) + 1.5rem); }
            .footer { display: none; } /* Hide heavy footer on mobile */
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }
        
        @media (min-width: 769px) {
            .container { padding: 0 2.5rem; }
            .section { padding-top: 3.5rem; padding-bottom: 3.5rem; }
            /* Footer for desktop */
            .footer {
                background: #070707; border-top: 1px solid var(--clr-border);
                padding: 4rem 2rem; margin-top: 4rem;
            }
            .footer-content {
                max-width: 1100px; margin: 0 auto; display: flex; justify-content: space-between;
            }
        }
        /* === SKELETON LOADING === */
        .skeleton-img {
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .skeleton-img.loaded {
            opacity: 1;
        }
        .skeleton-img-wrapper {
            position: relative;
            overflow: hidden;
        }
        .skeleton-img-wrapper::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, var(--clr-surface-2) 25%, var(--clr-surface-3) 50%, var(--clr-surface-2) 75%);
            background-size: 200% 100%;
            animation: skeleton-pulse 1.5s ease-in-out infinite;
            z-index: 1;
            border-radius: inherit;
            transition: opacity 0.4s ease;
        }
        .skeleton-img-wrapper.loaded::before {
            opacity: 0;
            pointer-events: none;
        }
        @keyframes skeleton-pulse {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- TOP NAVBAR --}}
    <nav class="navbar" id="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">
            <img src="{{ asset('images/cinevora-logo.png') }}" alt="Cinevora Logo" style="height: 64px; object-fit: contain;">
        </a>

        {{-- Desktop Menu --}}
        <div class="desktop-nav">
            <div class="location-pill" id="cityPillDesktop" onclick="toggleCityDropdown('desktop')">
                <i class='bx bx-map'></i>
                <span id="cityLabelDesktop">{{ request()->cookie('cinevora_city', 'Semua Kota') }}</span>
                <i class='bx bx-chevron-down pill-chevron'></i>
                <div class="city-dropdown" id="cityDropdownDesktop">
                    <div class="city-dropdown-item {{ !request()->cookie('cinevora_city') ? 'active' : '' }}" onclick="selectCity('', event)">
                        <i class='bx bx-check check-icon'></i> Semua Kota
                    </div>
                    @foreach($navCities as $city)
                    <div class="city-dropdown-item {{ request()->cookie('cinevora_city') === $city ? 'active' : '' }}" onclick="selectCity('{{ $city }}', event)">
                        <i class='bx bx-check check-icon'></i> {{ $city }}
                    </div>
                    @endforeach
                </div>
            </div>
            <div style="width: 1px; height: 20px; background: var(--clr-border); margin: 0 0.5rem;"></div>
            <a href="{{ route('cinemas.index') }}" class="nav-link {{ request()->routeIs('cinemas.*') ? 'active' : '' }}">Bioskop</a>
            <a href="{{ route('movies.index') }}" class="nav-link {{ request()->routeIs('movies.*') ? 'active' : '' }}">Film</a>
            <a href="{{ route('cafe.menu') }}" class="nav-link {{ request()->routeIs('cafe.menu') ? 'active' : '' }}">Cafe</a>
            
            <div style="width: 1px; height: 20px; background: var(--clr-border); margin: 0 0.5rem;"></div>
            
            @auth
                <a href="{{ route('profile.index') }}" class="nav-link" style="display:flex; align-items:center; gap:0.5rem;">
                    <div style="width:28px;height:28px;border-radius:50%;background:var(--clr-primary);color:white;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:bold;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    {{ Auth::user()->name }}
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Buat akun</a>
            @endauth
        </div>

        {{-- Mobile Top Right --}}
        <div class="desktop-nav" style="display: flex; gap: 0.5rem;" id="mobileTopRight">
             <div class="location-pill" id="cityPillMobile" onclick="toggleCityDropdown('mobile')" style="font-size: 0.7rem; padding: 0.3rem 0.6rem;">
                <i class='bx bx-map'></i>
                <span id="cityLabelMobile">{{ request()->cookie('cinevora_city', 'Semua Kota') }}</span>
                <i class='bx bx-chevron-down pill-chevron'></i>
                <div class="city-dropdown" id="cityDropdownMobile">
                    <div class="city-dropdown-item {{ !request()->cookie('cinevora_city') ? 'active' : '' }}" onclick="selectCity('', event)">
                        <i class='bx bx-check check-icon'></i> Semua Kota
                    </div>
                    @foreach($navCities as $city)
                    <div class="city-dropdown-item {{ request()->cookie('cinevora_city') === $city ? 'active' : '' }}" onclick="selectCity('{{ $city }}', event)">
                        <i class='bx bx-check check-icon'></i> {{ $city }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <style>
            @media (min-width: 769px) { #mobileTopRight { display: none !important; } }
        </style>
    </nav>

    {{-- FLASH MESSAGES --}}
    <main>
    <div class="main-content">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success" style="margin-top: 1rem;">
                    <i class='bx bx-check-circle'></i> <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error" style="margin-top: 1rem;">
                    <i class='bx bx-x-circle'></i> <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        @yield('content')
    </div>
    </main>

    {{-- MOBILE BOTTOM NAV --}}
    <nav class="bottom-nav">
        <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class='bx {{ request()->routeIs('home') ? 'bxs-home' : 'bx-home' }}'></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('cinemas.index') }}" class="bottom-nav-item {{ request()->routeIs('cinemas.*') ? 'active' : '' }}">
            <i class='bx {{ request()->routeIs('cinemas.*') ? 'bxs-building' : 'bx-building' }}'></i>
            <span>Bioskop</span>
        </a>
        <a href="{{ route('movies.index') }}" class="bottom-nav-item {{ request()->routeIs('movies.*') ? 'active' : '' }}">
            <i class='bx {{ request()->routeIs('movies.*') ? 'bxs-film' : 'bx-film' }}'></i>
            <span>Film</span>
        </a>
        <a href="{{ route('cafe.menu') }}" class="bottom-nav-item {{ request()->routeIs('cafe.menu') ? 'active' : '' }}">
            <i class='bx {{ request()->routeIs('cafe.menu') ? 'bxs-coffee-togo' : 'bx-coffee-togo' }}'></i>
            <span>civore</span>
        </a>
        <a href="{{ route(Auth::check() ? 'profile.index' : 'login') }}" class="bottom-nav-item {{ request()->routeIs('profile.*') || request()->routeIs('login') ? 'active' : '' }}">
            <i class='bx {{ (request()->routeIs('profile.*') || request()->routeIs('login')) ? 'bxs-user' : 'bx-user' }}'></i>
            <span>Akun</span>
        </a>
    </nav>

    {{-- DESKTOP FOOTER --}}
    <footer class="footer">
        <div class="footer-content">
            <div>
                <div style="margin-bottom: 0.5rem;">
                    <img src="{{ asset('images/cinevora-logo.png') }}" alt="Cinevora Logo" style="height: 64px; object-fit: contain;">
                </div>
                <p class="text-muted text-sm mt-1">Sistem Booking Cinema & Cafe</p>
                <p class="text-xs text-muted mt-2">© {{ date('Y') }} Cinevora. All rights reserved.</p>
            </div>
            <div style="display:flex; gap: 3rem;">
                <div>
                    <h4 class="font-bold mb-2" style="font-size: 0.95rem; color: #fff;">Menu</h4>
                    <ul style="list-style:none; padding:0; display:flex; flex-direction:column; gap:0.50rem; font-size:0.85rem;">
                        <li><a href="{{ route('home') }}" class="footer-link" style="color:var(--clr-text-muted); text-decoration:none; transition: var(--transition);">Bioskop</a></li>
                        <li><a href="{{ route('movies.index') }}" class="footer-link" style="color:var(--clr-text-muted); text-decoration:none; transition: var(--transition);">Film</a></li>
                        <li><a href="{{ route('cafe.menu') }}" class="footer-link" style="color:var(--clr-text-muted); text-decoration:none; transition: var(--transition);">Cafe Box</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <style>
            .footer-link:hover {
                color: var(--clr-primary) !important;
            }
        </style>
    </footer>

    {{-- City Selector Script --}}
    <script>
    function toggleCityDropdown(type) {
        const dropdown = document.getElementById('cityDropdown' + (type === 'desktop' ? 'Desktop' : 'Mobile'));
        const pill = document.getElementById('cityPill' + (type === 'desktop' ? 'Desktop' : 'Mobile'));
        const isOpen = dropdown.classList.contains('show');
        // Close all dropdowns first
        document.querySelectorAll('.city-dropdown').forEach(d => d.classList.remove('show'));
        document.querySelectorAll('.location-pill').forEach(p => p.classList.remove('open'));
        if (!isOpen) {
            dropdown.classList.add('show');
            pill.classList.add('open');
        }
    }

    function selectCity(city, event) {
        event.stopPropagation();
        // Set cookie (30 days)
        if (city) {
            document.cookie = 'cinevora_city=' + encodeURIComponent(city) + ';path=/;max-age=' + (30*86400) + ';SameSite=Lax';
        } else {
            document.cookie = 'cinevora_city=;path=/;max-age=0;SameSite=Lax';
        }
        // Reload to apply filter
        window.location.reload();
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.location-pill')) {
            document.querySelectorAll('.city-dropdown').forEach(d => d.classList.remove('show'));
            document.querySelectorAll('.location-pill').forEach(p => p.classList.remove('open'));
        }
    });
    </script>

    {{-- Skeleton Loading Script --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.skeleton-img').forEach(function(img) {
            var parent = img.parentElement;
            // For absolutely positioned images (like movie posters inside poster-wrapper),
            // add skeleton class to the parent instead of wrapping
            var computedPos = getComputedStyle(img).position;
            if (computedPos === 'absolute') {
                parent.classList.add('skeleton-img-wrapper');
            } else if (!parent.classList.contains('skeleton-img-wrapper')) {
                // For inline/block images, wrap in skeleton wrapper
                var wrapper = document.createElement('div');
                wrapper.className = 'skeleton-img-wrapper';
                wrapper.style.borderRadius = 'inherit';
                img.parentNode.insertBefore(wrapper, img);
                wrapper.appendChild(img);
            }
            var w = img.closest('.skeleton-img-wrapper');
            function onLoaded() {
                img.classList.add('loaded');
                if (w) w.classList.add('loaded');
            }
            if (img.complete && img.naturalWidth > 0) {
                onLoaded();
            } else {
                img.addEventListener('load', onLoaded);
                img.addEventListener('error', onLoaded);
            }
        });
    });
    </script>

    {{-- Navbar Scroll Script --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var navbar = document.querySelector('.navbar');
        if (navbar) {
            // Check initial scroll position
            if (window.scrollY > 10) {
                navbar.classList.add('navbar-scrolled');
            }
            window.addEventListener('scroll', function() {
                if (window.scrollY > 10) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
            });
        }
    });
    </script>

    @stack('scripts')
</body>
</html>
