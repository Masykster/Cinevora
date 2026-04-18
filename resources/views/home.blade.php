@extends('layouts.app')
@section('title', 'Cinevora')
@section('meta_description', 'Cinevora - Booking tiket bioskop & pesan makanan kafe dalam satu aplikasi. Nikmati pengalaman menonton film terbaik.')

@section('content')
{{-- HERO SECTION --}}
<section style="position: relative; padding: 6rem 2rem 4rem; overflow: hidden;">
    <div style="position: absolute; inset: 0; background: radial-gradient(ellipse at 50% 0%, rgba(124, 58, 237, 0.15), transparent 70%);"></div>
    <div style="position: absolute; inset: 0; background: radial-gradient(ellipse at 80% 50%, rgba(245, 158, 11, 0.08), transparent 50%);"></div>

    <div class="container" style="position: relative; text-align: center; max-width: 800px;">
        <div style="display: inline-block; padding: 0.3rem 1rem; border-radius: var(--radius-full); background: rgba(124, 58, 237, 0.15); color: var(--clr-primary-light); font-size: 0.8rem; font-weight: 600; margin-bottom: 1.5rem; border: 1px solid rgba(124, 58, 237, 0.2);">
            🎬 Pengalaman Bioskop Premium
        </div>
        <h1 class="font-heading" style="font-size: 3.5rem; font-weight: 900; line-height: 1.1; letter-spacing: -1.5px;">
            Nonton Film Favorit,<br>
            <span style="background: linear-gradient(135deg, var(--clr-primary-light), var(--clr-accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Pesan dari Satu Tempat
            </span>
        </h1>
        <p class="text-muted" style="font-size: 1.15rem; margin-top: 1.5rem; line-height: 1.7; max-width: 600px; margin-left: auto; margin-right: auto;">
            Booking tiket bioskop & pesan makanan kafe dalam satu transaksi. Pilih film, pilih kursi, tambah snack — semudah itu.
        </p>
        <div style="margin-top: 2.5rem; display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('movies.index') }}" class="btn btn-primary btn-lg">
                🎬 Lihat Film
            </a>
            <a href="{{ route('cafe.menu') }}" class="btn btn-outline btn-lg">
                ☕ Menu Kafe
            </a>
        </div>
    </div>
</section>

{{-- NOW PLAYING --}}
@if($nowPlaying->count() > 0)
<section class="section">
    <div class="container">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
            <div>
                <h2 class="font-heading" style="font-size: 1.8rem; font-weight: 700;">
                    <span style="color: var(--clr-accent);">▶</span> Sedang Tayang
                </h2>
                <p class="text-muted text-sm mt-1">Film-film terbaik yang sedang tayang di bioskop kami</p>
            </div>
            <a href="{{ route('movies.index', ['status' => 'now_playing']) }}" class="btn btn-ghost btn-sm">Lihat Semua →</a>
        </div>

        <div class="grid grid-4 gap-3">
            @foreach($nowPlaying as $movie)
                <a href="{{ route('movies.show', $movie) }}" class="card" style="text-decoration: none; color: inherit; transition: var(--transition);" onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform='none'">
                    <div style="position: relative; aspect-ratio: 2/3; background: var(--clr-surface-2); overflow: hidden;">
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--clr-surface-2), var(--clr-surface-3)); display: flex; align-items: center; justify-content: center; font-size: 3rem;">
                            🎬
                        </div>
                        <div style="position: absolute; top: 0.75rem; right: 0.75rem;">
                            <span class="badge badge-accent">⭐ {{ number_format($movie->rating, 1) }}</span>
                        </div>
                        <div style="position: absolute; top: 0.75rem; left: 0.75rem;">
                            <span class="badge badge-primary">{{ $movie->age_rating }}</span>
                        </div>
                    </div>
                    <div style="padding: 1rem;">
                        <h3 style="font-family: var(--font-heading); font-size: 1rem; font-weight: 700; margin-bottom: 0.3rem; line-height: 1.3;">{{ $movie->title }}</h3>
                        <p class="text-muted text-xs">{{ $movie->genre }} · {{ $movie->duration_formatted }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- COMING SOON --}}
@if($comingSoon->count() > 0)
<section class="section">
    <div class="container">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
            <div>
                <h2 class="font-heading" style="font-size: 1.8rem; font-weight: 700;">
                    <span style="color: var(--clr-primary-light);">🎭</span> Segera Tayang
                </h2>
                <p class="text-muted text-sm mt-1">Yang akan datang, jangan sampai ketinggalan!</p>
            </div>
            <a href="{{ route('movies.index', ['status' => 'coming_soon']) }}" class="btn btn-ghost btn-sm">Lihat Semua →</a>
        </div>

        <div class="grid grid-3 gap-3">
            @foreach($comingSoon as $movie)
                <a href="{{ route('movies.show', $movie) }}" class="card" style="text-decoration: none; color: inherit; display: flex; transition: var(--transition);" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='none'">
                    <div style="width: 120px; min-height: 160px; background: linear-gradient(135deg, var(--clr-surface-2), var(--clr-surface-3)); display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0;">
                        🎬
                    </div>
                    <div style="padding: 1.25rem; flex: 1;">
                        <span class="badge badge-primary" style="margin-bottom: 0.5rem;">Coming Soon</span>
                        <h3 style="font-family: var(--font-heading); font-size: 1rem; font-weight: 700; margin-bottom: 0.3rem;">{{ $movie->title }}</h3>
                        <p class="text-muted text-xs mb-1">{{ $movie->genre }} · {{ $movie->duration_formatted }}</p>
                        <p class="text-muted text-xs">📅 {{ $movie->release_date->format('d M Y') }}</p>
                        <div style="margin-top: 0.75rem;">
                            <span class="badge badge-accent">⭐ {{ number_format($movie->rating, 1) }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- WHY CINEVORA --}}
<section class="section" style="border-top: 1px solid var(--clr-border);">
    <div class="container text-center">
        <h2 class="font-heading" style="font-size: 1.8rem; font-weight: 700; margin-bottom: 0.5rem;">Kenapa Cinevora?</h2>
        <p class="text-muted mb-4" style="max-width: 500px; margin-left: auto; margin-right: auto;">Pengalaman booking yang simpel, cepat, dan menyenangkan</p>

        <div class="grid grid-3 gap-3">
            <div class="card" style="padding: 2rem; text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">🎟️</div>
                <h3 class="font-heading" style="font-weight: 700; margin-bottom: 0.5rem;">Pilih Kursi Sendiri</h3>
                <p class="text-muted text-sm">Pilih kursi favoritmu secara visual. Lihat langsung mana yang tersedia.</p>
            </div>
            <div class="card" style="padding: 2rem; text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">🍿</div>
                <h3 class="font-heading" style="font-weight: 700; margin-bottom: 0.5rem;">Satu Keranjang</h3>
                <p class="text-muted text-sm">Tiket + makanan dalam satu transaksi. Tidak perlu antre dua kali.</p>
            </div>
            <div class="card" style="padding: 2rem; text-align: center;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">📱</div>
                <h3 class="font-heading" style="font-weight: 700; margin-bottom: 0.5rem;">E-Ticket Instan</h3>
                <p class="text-muted text-sm">Dapatkan e-ticket digital langsung setelah pembayaran. Tinggal scan & masuk.</p>
            </div>
        </div>
    </div>
</section>
@endsection
