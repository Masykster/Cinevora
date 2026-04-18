<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Cinevora - Sistem Booking Cinema & Cafe Terbaik di Indonesia')">

    <title>@yield('title', 'Cinevora') - Cinema & Cafe</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --clr-bg: #0a0a12;
            --clr-surface: #12121e;
            --clr-surface-2: #1a1a2e;
            --clr-surface-3: #24243a;
            --clr-border: #2a2a44;
            --clr-primary: #7c3aed;
            --clr-primary-light: #a78bfa;
            --clr-accent: #f59e0b;
            --clr-accent-light: #fbbf24;
            --clr-text: #e2e8f0;
            --clr-text-muted: #94a3b8;
            --clr-success: #10b981;
            --clr-error: #ef4444;
            --clr-warning: #f59e0b;
            --radius: 12px;
            --radius-lg: 16px;
            --radius-full: 9999px;
            --font-heading: 'Outfit', sans-serif;
            --font-body: 'Inter', sans-serif;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-body);
            background: var(--clr-bg);
            color: var(--clr-text);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* === NAVBAR === */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: rgba(10, 10, 18, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--clr-border);
            padding: 0 2rem;
            height: 72px;
            display: flex; align-items: center; justify-content: space-between;
            transition: var(--transition);
        }

        .navbar-brand {
            font-family: var(--font-heading);
            font-size: 1.6rem; font-weight: 800;
            background: linear-gradient(135deg, var(--clr-primary-light), var(--clr-accent));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .navbar-links {
            display: flex; align-items: center; gap: 0.5rem;
            list-style: none;
        }

        .navbar-links a {
            color: var(--clr-text-muted);
            text-decoration: none; font-size: 0.9rem; font-weight: 500;
            padding: 0.5rem 1rem; border-radius: var(--radius);
            transition: var(--transition);
        }
        .navbar-links a:hover, .navbar-links a.active {
            color: var(--clr-text);
            background: var(--clr-surface-2);
        }

        .navbar-auth {
            display: flex; align-items: center; gap: 0.75rem;
        }

        .navbar-user {
            display: flex; align-items: center; gap: 0.5rem;
            color: var(--clr-text); font-size: 0.9rem; font-weight: 500;
            text-decoration: none; padding: 0.4rem 1rem;
            border-radius: var(--radius-full);
            background: var(--clr-surface-2); border: 1px solid var(--clr-border);
            transition: var(--transition);
        }
        .navbar-user:hover { border-color: var(--clr-primary); }

        .navbar-user .avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: linear-gradient(135deg, var(--clr-primary), var(--clr-accent));
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700; color: #fff;
        }

        /* === BUTTONS === */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.65rem 1.5rem; border-radius: var(--radius);
            font-family: var(--font-body); font-size: 0.875rem; font-weight: 600;
            text-decoration: none; cursor: pointer; border: none;
            transition: var(--transition);
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--clr-primary), #6d28d9);
            color: #fff; box-shadow: 0 4px 15px rgba(124, 58, 237, 0.3);
        }
        .btn-primary:hover {
            box-shadow: 0 6px 25px rgba(124, 58, 237, 0.5);
            transform: translateY(-1px);
        }
        .btn-accent {
            background: linear-gradient(135deg, var(--clr-accent), #d97706);
            color: #1a1a2e; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }
        .btn-accent:hover {
            box-shadow: 0 6px 25px rgba(245, 158, 11, 0.5);
            transform: translateY(-1px);
        }
        .btn-outline {
            background: transparent; color: var(--clr-text);
            border: 1px solid var(--clr-border);
        }
        .btn-outline:hover { border-color: var(--clr-primary); color: var(--clr-primary-light); }
        .btn-ghost {
            background: transparent; color: var(--clr-text-muted);
        }
        .btn-ghost:hover { color: var(--clr-text); background: var(--clr-surface-2); }
        .btn-danger {
            background: var(--clr-error); color: #fff;
        }
        .btn-danger:hover { background: #dc2626; transform: translateY(-1px); }
        .btn-sm { padding: 0.4rem 1rem; font-size: 0.8rem; }
        .btn-lg { padding: 0.85rem 2rem; font-size: 1rem; }
        .btn-block { width: 100%; }

        /* === CARDS === */
        .card {
            background: var(--clr-surface);
            border: 1px solid var(--clr-border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: var(--transition);
        }
        .card:hover {
            border-color: var(--clr-surface-3);
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        .card-glass {
            background: rgba(18, 18, 30, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.06);
        }

        /* === FORM ELEMENTS === */
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block; font-size: 0.85rem; font-weight: 600;
            color: var(--clr-text-muted); margin-bottom: 0.4rem;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 0.7rem 1rem;
            background: var(--clr-surface-2); border: 1px solid var(--clr-border);
            border-radius: var(--radius); color: var(--clr-text);
            font-family: var(--font-body); font-size: 0.9rem;
            transition: var(--transition); outline: none;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--clr-primary);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
        }
        .form-textarea { resize: vertical; min-height: 100px; }
        .form-error { color: var(--clr-error); font-size: 0.8rem; margin-top: 0.3rem; }
        .form-checkbox {
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.9rem; cursor: pointer;
        }
        .form-checkbox input[type="checkbox"] {
            width: 18px; height: 18px; accent-color: var(--clr-primary);
        }

        /* === ALERTS === */
        .alert {
            padding: 1rem 1.25rem; border-radius: var(--radius);
            font-size: 0.9rem; margin-bottom: 1rem;
            display: flex; align-items: center; gap: 0.75rem;
            animation: slideDown 0.3s ease;
        }
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399; }
        .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; }
        .alert-warning { background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); color: #fbbf24; }

        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        /* === BADGE === */
        .badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.2rem 0.6rem; border-radius: var(--radius-full);
            font-size: 0.7rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-primary { background: rgba(124, 58, 237, 0.15); color: var(--clr-primary-light); }
        .badge-accent { background: rgba(245, 158, 11, 0.15); color: var(--clr-accent-light); }
        .badge-success { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        .badge-error { background: rgba(239, 68, 68, 0.15); color: #f87171; }
        .badge-gray { background: rgba(148, 163, 184, 0.15); color: var(--clr-text-muted); }
        .badge-yellow { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
        .badge-blue { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
        .badge-green { background: rgba(16, 185, 129, 0.15); color: #34d399; }

        /* === LAYOUT === */
        .main-content { padding-top: 72px; min-height: 100vh; }
        .container { max-width: 1280px; margin: 0 auto; padding: 0 2rem; }
        .section { padding: 3rem 0; }

        /* === TABLE === */
        .table-wrapper { overflow-x: auto; border-radius: var(--radius-lg); border: 1px solid var(--clr-border); }
        .table { width: 100%; border-collapse: collapse; }
        .table th {
            text-align: left; padding: 0.9rem 1rem;
            background: var(--clr-surface-2); font-size: 0.8rem;
            font-weight: 600; color: var(--clr-text-muted);
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .table td {
            padding: 0.85rem 1rem; font-size: 0.9rem;
            border-top: 1px solid var(--clr-border);
        }
        .table tr:hover td { background: var(--clr-surface-2); }

        /* === PAGINATION === */
        .pagination {
            display: flex; align-items: center; justify-content: center; gap: 0.25rem;
            margin-top: 2rem; list-style: none;
        }
        .pagination li a, .pagination li span {
            display: flex; align-items: center; justify-content: center;
            min-width: 36px; height: 36px; padding: 0 0.5rem;
            border-radius: var(--radius); font-size: 0.85rem; font-weight: 500;
            color: var(--clr-text-muted); background: var(--clr-surface);
            border: 1px solid var(--clr-border); text-decoration: none;
            transition: var(--transition);
        }
        .pagination li a:hover { border-color: var(--clr-primary); color: var(--clr-primary-light); }
        .pagination li.active span {
            background: var(--clr-primary); color: #fff; border-color: var(--clr-primary);
        }
        .pagination li.disabled span { opacity: 0.4; cursor: not-allowed; }

        /* === FOOTER === */
        .footer {
            background: var(--clr-surface);
            border-top: 1px solid var(--clr-border);
            padding: 2.5rem 2rem 1.5rem;
            margin-top: 4rem;
        }
        .footer-content {
            max-width: 1280px; margin: 0 auto;
            display: flex; justify-content: space-between; align-items: flex-start;
            flex-wrap: wrap; gap: 2rem;
        }
        .footer-brand { font-family: var(--font-heading); font-size: 1.3rem; font-weight: 800; }
        .footer-text { color: var(--clr-text-muted); font-size: 0.85rem; margin-top: 0.5rem; }
        .footer-links { list-style: none; display: flex; gap: 1.5rem; }
        .footer-links a { color: var(--clr-text-muted); font-size: 0.85rem; text-decoration: none; transition: var(--transition); }
        .footer-links a:hover { color: var(--clr-text); }
        .footer-bottom {
            max-width: 1280px; margin: 1.5rem auto 0;
            padding-top: 1rem; border-top: 1px solid var(--clr-border);
            text-align: center; color: var(--clr-text-muted); font-size: 0.8rem;
        }

        /* === UTILITIES === */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-muted { color: var(--clr-text-muted); }
        .text-accent { color: var(--clr-accent); }
        .text-primary { color: var(--clr-primary-light); }
        .text-success { color: #34d399; }
        .text-error { color: #f87171; }
        .text-sm { font-size: 0.85rem; }
        .text-xs { font-size: 0.75rem; }
        .text-lg { font-size: 1.15rem; }
        .font-heading { font-family: var(--font-heading); }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        .mt-1 { margin-top: 0.5rem; } .mt-2 { margin-top: 1rem; } .mt-3 { margin-top: 1.5rem; } .mt-4 { margin-top: 2rem; }
        .mb-1 { margin-bottom: 0.5rem; } .mb-2 { margin-bottom: 1rem; } .mb-3 { margin-bottom: 1.5rem; } .mb-4 { margin-bottom: 2rem; }
        .flex { display: flex; } .flex-wrap { flex-wrap: wrap; } .items-center { align-items: center; } .justify-between { justify-content: space-between; }
        .gap-1 { gap: 0.5rem; } .gap-2 { gap: 1rem; } .gap-3 { gap: 1.5rem; }
        .grid { display: grid; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        .hidden { display: none; }

        /* Mobile menu */
        .mobile-toggle { display: none; background: none; border: none; color: var(--clr-text); font-size: 1.5rem; cursor: pointer; }

        @media (max-width: 768px) {
            .navbar { padding: 0 1rem; }
            .mobile-toggle { display: block; }
            .navbar-links {
                display: none; position: absolute; top: 72px; left: 0; right: 0;
                background: var(--clr-surface); border-bottom: 1px solid var(--clr-border);
                flex-direction: column; padding: 1rem;
            }
            .navbar-links.open { display: flex; }
            .container { padding: 0 1rem; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
            .footer-content { flex-direction: column; align-items: center; text-align: center; }
        }

        @media (max-width: 1024px) {
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- NAVBAR --}}
    <nav class="navbar" id="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">CINEVORA</a>

        <button class="mobile-toggle" onclick="document.getElementById('navLinks').classList.toggle('open')" aria-label="Toggle menu">☰</button>

        <ul class="navbar-links" id="navLinks">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('movies.index') }}" class="{{ request()->routeIs('movies.*') ? 'active' : '' }}">Movies</a></li>
            <li><a href="{{ route('cafe.menu') }}" class="{{ request()->routeIs('cafe.menu') ? 'active' : '' }}">Cafe Menu</a></li>
        </ul>

        <div class="navbar-auth">
            @auth
                <a href="{{ route('profile.index') }}" class="navbar-user">
                    <span class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    <span>{{ Auth::user()->name }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
            @endauth
        </div>
    </nav>

    {{-- FLASH MESSAGES --}}
    <div class="main-content">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success" style="margin-top: 1rem;">
                    <span>✓</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error" style="margin-top: 1rem;">
                    <span>✕</span> {{ session('error') }}
                </div>
            @endif
        </div>

        @yield('content')
    </div>

    {{-- FOOTER --}}
    <footer class="footer">
        <div class="footer-content">
            <div>
                <div class="footer-brand" style="background: linear-gradient(135deg, var(--clr-primary-light), var(--clr-accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">CINEVORA</div>
                <p class="footer-text">Cinema & Cafe Booking System</p>
            </div>
            <ul class="footer-links">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('movies.index') }}">Movies</a></li>
                <li><a href="{{ route('cafe.menu') }}">Cafe</a></li>
            </ul>
        </div>
        <div class="footer-bottom">
            © {{ date('Y') }} Cinevora. All rights reserved.
        </div>
    </footer>

    @stack('scripts')

    <script>
        // Auto-dismiss alerts after 5s
        document.querySelectorAll('.alert').forEach(el => {
            setTimeout(() => { el.style.opacity = '0'; el.style.transform = 'translateY(-10px)'; setTimeout(() => el.remove(), 300); }, 5000);
        });
    </script>
</body>
</html>
