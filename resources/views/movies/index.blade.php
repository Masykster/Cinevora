@extends('layouts.app')
@section('title', 'Movies')
@section('meta_description', 'Jelajahi film-film terbaik yang sedang tayang dan akan datang di Cinevora')

@section('content')
<section class="section">
    <div class="container">
        <div style="margin-bottom: 2rem;">
            <h1 class="font-heading" style="font-size: 2rem; font-weight: 800;">Semua Film</h1>
            <p class="text-muted mt-1">Temukan film favorit Anda</p>
        </div>

        {{-- FILTERS --}}
        <form method="GET" action="{{ route('movies.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2rem; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label class="form-label">Cari Film</label>
                <input type="text" name="search" class="form-input" placeholder="Judul atau sutradara..." value="{{ request('search') }}">
            </div>
            <div style="min-width: 150px;">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="now_playing" {{ request('status') === 'now_playing' ? 'selected' : '' }}>Sedang Tayang</option>
                    <option value="coming_soon" {{ request('status') === 'coming_soon' ? 'selected' : '' }}>Segera Tayang</option>
                </select>
            </div>
            <div style="min-width: 150px;">
                <label class="form-label">Genre</label>
                <select name="genre" class="form-select">
                    <option value="">Semua Genre</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre }}" {{ request('genre') === $genre ? 'selected' : '' }}>{{ $genre }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            @if(request()->hasAny(['search', 'status', 'genre']))
                <a href="{{ route('movies.index') }}" class="btn btn-ghost">Reset</a>
            @endif
        </form>

        {{-- MOVIE GRID --}}
        @if($movies->count() > 0)
            <div class="grid grid-4 gap-3">
                @foreach($movies as $movie)
                    <a href="{{ route('movies.show', $movie) }}" class="card" style="text-decoration: none; color: inherit; transition: var(--transition);" onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform='none'">
                        <div style="position: relative; aspect-ratio: 2/3; background: var(--clr-surface-2); overflow: hidden;">
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--clr-surface-2), var(--clr-surface-3)); display: flex; align-items: center; justify-content: center; font-size: 3rem;">🎬</div>
                            <div style="position: absolute; top: 0.75rem; right: 0.75rem;">
                                <span class="badge badge-accent">⭐ {{ number_format($movie->rating, 1) }}</span>
                            </div>
                            <div style="position: absolute; top: 0.75rem; left: 0.75rem;">
                                <span class="badge badge-primary">{{ $movie->age_rating }}</span>
                            </div>
                            @if($movie->status === 'coming_soon')
                                <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 0.5rem; background: rgba(124, 58, 237, 0.9); text-align: center;">
                                    <span style="font-size: 0.75rem; font-weight: 600; color: #fff;">COMING SOON · {{ $movie->release_date->format('d M') }}</span>
                                </div>
                            @endif
                        </div>
                        <div style="padding: 1rem;">
                            <h3 style="font-family: var(--font-heading); font-size: 0.95rem; font-weight: 700; margin-bottom: 0.3rem; line-height: 1.3;">{{ $movie->title }}</h3>
                            <p class="text-muted text-xs">{{ $movie->genre }}</p>
                            <p class="text-muted text-xs">{{ $movie->duration_formatted }} · {{ $movie->director }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="pagination">
                {{ $movies->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center" style="padding: 4rem 0;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">🎬</div>
                <h3 class="font-heading" style="font-weight: 700;">Tidak ada film ditemukan</h3>
                <p class="text-muted mt-1">Coba ubah filter pencarian Anda</p>
            </div>
        @endif
    </div>
</section>
@endsection
