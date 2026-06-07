<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Cinevora Admin</title>
    <link rel="icon" href="{{ asset('images/cinevora-logo.png') }}" type="image/png">

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
        }

        .admin-layout { display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            width: 260px; background: var(--clr-surface);
            border-right: 1px solid var(--clr-border);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0;
            z-index: 50; transition: var(--transition);
        }

        .sidebar-brand {
            padding: 1.5rem; border-bottom: 1px solid var(--clr-border);
        }
        .sidebar-brand a {
            font-family: var(--font-heading); font-size: 1.4rem; font-weight: 800;
            background: linear-gradient(135deg, var(--clr-primary-light), var(--clr-accent));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            text-decoration: none;
        }
        .sidebar-brand small { display: block; color: var(--clr-text-muted); font-size: 0.7rem; margin-top: 0.2rem; letter-spacing: 1px; text-transform: uppercase; }

        .sidebar-nav { flex: 1; padding: 1rem 0; overflow-y: auto; }
        .sidebar-section { padding: 0 1rem; margin-bottom: 0.5rem; }
        .sidebar-section-title {
            font-size: 0.65rem; font-weight: 700; color: var(--clr-text-muted);
            text-transform: uppercase; letter-spacing: 1.5px; padding: 0.5rem 0.75rem;
        }

        .sidebar-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.65rem 0.75rem; margin: 0.15rem 0.5rem;
            border-radius: var(--radius); color: var(--clr-text-muted);
            text-decoration: none; font-size: 0.875rem; font-weight: 500;
            transition: var(--transition);
        }
        .sidebar-link:hover { background: var(--clr-surface-2); color: var(--clr-text); }
        .sidebar-link.active {
            background: rgba(124, 58, 237, 0.1); color: var(--clr-primary-light);
            border-left: 3px solid var(--clr-primary);
        }
        .sidebar-link .icon { font-size: 1.1rem; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 1rem; border-top: 1px solid var(--clr-border);
            display: flex; align-items: center; gap: 0.75rem;
        }
        .sidebar-footer .avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, var(--clr-primary), var(--clr-accent));
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; font-weight: 700; color: #fff;
        }
        .sidebar-footer .info { flex: 1; }
        .sidebar-footer .info .name { font-size: 0.85rem; font-weight: 600; }
        .sidebar-footer .info .role { font-size: 0.7rem; color: var(--clr-text-muted); }

        /* MAIN CONTENT */
        .admin-main { flex: 1; margin-left: 260px; }

        .admin-header {
            padding: 1.5rem 2rem; background: var(--clr-surface);
            border-bottom: 1px solid var(--clr-border);
            display: flex; align-items: center; justify-content: space-between;
        }

        .admin-header h1 {
            font-family: var(--font-heading); font-size: 1.5rem; font-weight: 700;
        }

        .admin-body { padding: 2rem; }

        /* Shared styles from app layout */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.65rem 1.5rem; border-radius: var(--radius); font-family: var(--font-body); font-size: 0.875rem; font-weight: 600; text-decoration: none; cursor: pointer; border: none; transition: var(--transition); }
        .btn-primary { background: linear-gradient(135deg, var(--clr-primary), #6d28d9); color: #fff; box-shadow: 0 4px 15px rgba(124, 58, 237, 0.3); }
        .btn-primary:hover { box-shadow: 0 6px 25px rgba(124, 58, 237, 0.5); transform: translateY(-1px); }
        .btn-accent { background: linear-gradient(135deg, var(--clr-accent), #d97706); color: #1a1a2e; }
        .btn-outline { background: transparent; color: var(--clr-text); border: 1px solid var(--clr-border); }
        .btn-outline:hover { border-color: var(--clr-primary); color: var(--clr-primary-light); }
        .btn-danger { background: var(--clr-error); color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-sm { padding: 0.4rem 1rem; font-size: 0.8rem; }
        .btn-ghost { background: transparent; color: var(--clr-text-muted); }
        .btn-ghost:hover { color: var(--clr-text); background: var(--clr-surface-2); }

        .card { background: var(--clr-surface); border: 1px solid var(--clr-border); border-radius: var(--radius-lg); overflow: hidden; }

        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--clr-text-muted); margin-bottom: 0.4rem; }
        .form-input, .form-select, .form-textarea { width: 100%; padding: 0.7rem 1rem; background: var(--clr-surface-2); border: 1px solid var(--clr-border); border-radius: var(--radius); color: var(--clr-text); font-family: var(--font-body); font-size: 0.9rem; transition: var(--transition); outline: none; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--clr-primary); box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15); }
        .form-textarea { resize: vertical; min-height: 100px; }
        .form-error { color: var(--clr-error); font-size: 0.8rem; margin-top: 0.3rem; }

        .table-wrapper { overflow-x: auto; border-radius: var(--radius-lg); border: 1px solid var(--clr-border); }
        .table { width: 100%; border-collapse: collapse; }
        .table th { text-align: left; padding: 0.9rem 1rem; background: var(--clr-surface-2); font-size: 0.8rem; font-weight: 600; color: var(--clr-text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .table td { padding: 0.85rem 1rem; font-size: 0.9rem; border-top: 1px solid var(--clr-border); }
        .table tr:hover td { background: var(--clr-surface-2); }

        .alert { padding: 1rem 1.25rem; border-radius: var(--radius); font-size: 0.9rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem; animation: slideDown 0.3s ease; }
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399; }
        .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        .badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: var(--radius-full); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-primary { background: rgba(124, 58, 237, 0.15); color: var(--clr-primary-light); }
        .badge-accent { background: rgba(245, 158, 11, 0.15); color: var(--clr-accent-light); }
        .badge-success { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        .badge-error { background: rgba(239, 68, 68, 0.15); color: #f87171; }
        .badge-gray { background: rgba(148, 163, 184, 0.15); color: var(--clr-text-muted); }

        .stat-card { padding: 1.5rem; }
        .stat-card .stat-label { font-size: 0.8rem; color: var(--clr-text-muted); font-weight: 500; }
        .stat-card .stat-value { font-family: var(--font-heading); font-size: 1.8rem; font-weight: 700; margin-top: 0.3rem; }
        .stat-card .stat-change { font-size: 0.75rem; margin-top: 0.3rem; }

        .pagination { display: flex; align-items: center; justify-content: center; gap: 0.25rem; margin-top: 2rem; list-style: none; }
        .pagination li a, .pagination li span { display: flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 0.5rem; border-radius: var(--radius); font-size: 0.85rem; font-weight: 500; color: var(--clr-text-muted); background: var(--clr-surface); border: 1px solid var(--clr-border); text-decoration: none; transition: var(--transition); }
        .pagination li a:hover { border-color: var(--clr-primary); color: var(--clr-primary-light); }
        .pagination li.active span { background: var(--clr-primary); color: #fff; border-color: var(--clr-primary); }

        .text-muted { color: var(--clr-text-muted); }
        .text-sm { font-size: 0.85rem; }
        .text-xs { font-size: 0.75rem; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-1 { gap: 0.5rem; }
        .gap-2 { gap: 1rem; }
        .gap-3 { gap: 1.5rem; }
        .grid { display: grid; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        .mt-2 { margin-top: 1rem; }
        .mt-3 { margin-top: 1.5rem; }
        .mb-2 { margin-bottom: 1rem; }
        .mb-3 { margin-bottom: 1.5rem; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        {{-- SIDEBAR --}}
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('images/cinevora-logo.png') }}" alt="Cinevora Logo" style="max-width: 100%; height: 28px; object-fit: contain; display: block; margin-bottom: 0.25rem;">
                </a>
                <small>Admin Panel</small>
            </div>

            <nav class="sidebar-nav">
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Overview</div>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="icon">📊</span> Dashboard
                    </a>
                </div>

                <div class="sidebar-section">
                    <div class="sidebar-section-title">Content</div>
                    <a href="{{ route('admin.movies.index') }}" class="sidebar-link {{ request()->routeIs('admin.movies.*') ? 'active' : '' }}">
                        <span class="icon">🎬</span> Movies
                    </a>
                    <a href="{{ route('admin.cinemas.index') }}" class="sidebar-link {{ request()->routeIs('admin.cinemas.*') ? 'active' : '' }}">
                        <span class="icon">🏢</span> Cinemas
                    </a>
                    <a href="{{ route('admin.schedules.index') }}" class="sidebar-link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                        <span class="icon">📅</span> Schedules
                    </a>
                </div>

                <div class="sidebar-section">
                    <div class="sidebar-section-title">Marketing</div>
                    <a href="{{ route('admin.vouchers.index') }}" class="sidebar-link {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}">
                        <span class="icon">🎫</span> Vouchers
                    </a>
                </div>

                <div class="sidebar-section">
                    <div class="sidebar-section-title">Management</div>
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span class="icon">👥</span> Users
                    </a>
                </div>

                <div class="sidebar-section" style="margin-top: 1rem; border-top: 1px solid var(--clr-border); padding-top: 1rem;">
                    <a href="{{ route('home') }}" class="sidebar-link">
                        <span class="icon">🌐</span> View Site
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="info">
                    <div class="name">{{ Auth::user()->name }}</div>
                    <div class="role">Cinema Admin</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm" title="Logout">🚪</button>
                </form>
            </div>
        </aside>

        {{-- MAIN --}}
        <main class="admin-main">
            <header class="admin-header">
                <h1>@yield('title', 'Dashboard')</h1>
                @yield('header-actions')
            </header>

            <div class="admin-body">
                @if(session('success'))
                    <div class="alert alert-success"><span>✓</span> {{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error"><span>✕</span> {{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
    <script>
        document.querySelectorAll('.alert').forEach(el => {
            setTimeout(() => { el.style.opacity = '0'; setTimeout(() => el.remove(), 300); }, 5000);
        });
    </script>
</body>
</html>
