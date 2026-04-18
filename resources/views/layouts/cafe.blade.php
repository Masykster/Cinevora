<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cafe Admin') - Cinevora Cafe</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --clr-bg: #0a0a12; --clr-surface: #12121e; --clr-surface-2: #1a1a2e; --clr-surface-3: #24243a;
            --clr-border: #2a2a44; --clr-primary: #7c3aed; --clr-primary-light: #a78bfa;
            --clr-accent: #f59e0b; --clr-accent-light: #fbbf24; --clr-text: #e2e8f0;
            --clr-text-muted: #94a3b8; --clr-success: #10b981; --clr-error: #ef4444;
            --radius: 12px; --radius-lg: 16px; --radius-full: 9999px;
            --font-heading: 'Outfit', sans-serif; --font-body: 'Inter', sans-serif;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-body); background: var(--clr-bg); color: var(--clr-text); min-height: 100vh; }

        .cafe-header {
            background: var(--clr-surface); border-bottom: 1px solid var(--clr-border);
            padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between;
        }
        .cafe-brand { font-family: var(--font-heading); font-size: 1.3rem; font-weight: 800; }
        .cafe-brand span { background: linear-gradient(135deg, var(--clr-primary-light), var(--clr-accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .cafe-brand small { display: block; font-size: 0.65rem; color: var(--clr-text-muted); letter-spacing: 1px; text-transform: uppercase; font-weight: 500; -webkit-text-fill-color: initial; }

        .cafe-body { padding: 2rem; max-width: 1400px; margin: 0 auto; }

        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.65rem 1.5rem; border-radius: var(--radius); font-family: var(--font-body); font-size: 0.875rem; font-weight: 600; text-decoration: none; cursor: pointer; border: none; transition: var(--transition); }
        .btn-primary { background: linear-gradient(135deg, var(--clr-primary), #6d28d9); color: #fff; }
        .btn-accent { background: linear-gradient(135deg, var(--clr-accent), #d97706); color: #1a1a2e; }
        .btn-outline { background: transparent; color: var(--clr-text); border: 1px solid var(--clr-border); }
        .btn-outline:hover { border-color: var(--clr-primary); }
        .btn-ghost { background: transparent; color: var(--clr-text-muted); }
        .btn-ghost:hover { color: var(--clr-text); background: var(--clr-surface-2); }
        .btn-sm { padding: 0.4rem 1rem; font-size: 0.8rem; }
        .btn-block { width: 100%; }

        .card { background: var(--clr-surface); border: 1px solid var(--clr-border); border-radius: var(--radius-lg); overflow: hidden; transition: var(--transition); }
        .badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: var(--radius-full); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-yellow { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
        .badge-blue { background: rgba(59, 130, 246, 0.15); color: #60a5fa; }
        .badge-green { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        .badge-gray { background: rgba(148, 163, 184, 0.15); color: var(--clr-text-muted); }
        .badge-accent { background: rgba(245, 158, 11, 0.15); color: var(--clr-accent-light); }
        .badge-primary { background: rgba(124, 58, 237, 0.15); color: var(--clr-primary-light); }

        .alert { padding: 1rem 1.25rem; border-radius: var(--radius); font-size: 0.9rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.75rem; }
        .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399; }
        .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; }

        .text-muted { color: var(--clr-text-muted); }
        .text-sm { font-size: 0.85rem; }
        .text-xs { font-size: 0.75rem; }
        .grid { display: grid; }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .gap-3 { gap: 1.5rem; }
        .mt-1 { margin-top: 0.5rem; }

        .pagination { display: flex; align-items: center; justify-content: center; gap: 0.25rem; margin-top: 2rem; list-style: none; }
        .pagination li a, .pagination li span { display: flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 0.5rem; border-radius: var(--radius); font-size: 0.85rem; color: var(--clr-text-muted); background: var(--clr-surface); border: 1px solid var(--clr-border); text-decoration: none; }
        .pagination li.active span { background: var(--clr-primary); color: #fff; border-color: var(--clr-primary); }

        @media (max-width: 768px) { .grid-3 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <header class="cafe-header">
        <div class="cafe-brand"><span>CINEVORA</span><small>Cafe Admin</small></div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span class="text-sm">☕ {{ Auth::user()->name }}</span>
            <a href="{{ route('home') }}" class="btn btn-ghost btn-sm">🌐 Site</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">@csrf<button class="btn btn-ghost btn-sm">Logout</button></form>
        </div>
    </header>

    <div class="cafe-body">
        @if(session('success'))
            <div class="alert alert-success"><span>✓</span> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><span>✕</span> {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
