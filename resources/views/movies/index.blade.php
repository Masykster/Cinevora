@extends('layouts.app')
@section('title', 'Semua Film - Cinevora')

@section('content')
<div class="container section" style="max-width: 1024px;">
    
    <div style="display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 2.5rem;">
        <h1 class="font-heading" style="font-size: 2.5rem; font-weight: 700; text-transform: uppercase; color: #fff; letter-spacing: 0.5px;">Daftar Film</h1>
        
        {{-- FILTER PILLS (Horizontal Scroll on Mobile) --}}
        <div style="display: flex; gap: 0.75rem; overflow-x: auto; padding-bottom: 0.75rem; scrollbar-width: none; -ms-overflow-style: none; border-bottom: 2px solid var(--clr-border);">
            <style>div::-webkit-scrollbar { display: none; }</style>
            
            <a href="{{ route('movies.index') }}" class="pill {{ request('status') != 'coming_soon' ? 'pill-active' : '' }}">
                ⚡ Sedang Tayang
            </a>
            <a href="{{ route('movies.index', ['status' => 'coming_soon']) }}" class="pill {{ request('status') == 'coming_soon' ? 'pill-active' : '' }}">
                📅 Akan Datang
            </a>
        </div>
    </div>

    {{-- SEARCH --}}
    <form action="{{ route('movies.index') }}" method="GET" style="margin-bottom: 3.5rem;">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <div style="position: relative; max-width: 500px;">
            <i class='bx bx-search' style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: var(--clr-text-muted); font-size: 1.3rem;"></i>
            <input type="text" name="search" value="{{ request('search') }}" class="form-input search-input" placeholder="CARI JUDUL FILM..." style="padding-left: 3.25rem; border-radius: var(--radius); background: var(--clr-surface-2); border-color: rgba(255,255,255,0.08); height: 48px; text-transform: uppercase; font-weight: 600; font-size: 0.85rem; letter-spacing: 0.5px;">
        </div>
    </form>

    {{-- MOVIE GRID --}}
    <div class="movie-grid">
        @forelse($movies as $movie)
            <div class="movie-card">
                <a href="{{ route('movies.show', $movie) }}" class="card-link">
                    <div class="movie-poster-wrapper">
                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="movie-poster-img">
                        
                        {{-- Hover overlay matching MyVue style --}}
                        <div class="movie-card-overlay">
                            <span class="btn btn-primary btn-sm" style="font-weight: 700; font-size: 0.75rem; padding: 0.5rem 1.25rem;">Beli Tiket</span>
                        </div>
                        
                        {{-- Top Badges --}}
                        <div style="position: absolute; top: 0.75rem; left: 0.75rem; display: flex; flex-direction: column; gap: 0.35rem; z-index: 5;">
                            <span class="badge-age-dark">{{ $movie->age_rating }}</span>
                            <span class="badge-format">2D</span>
                        </div>
                    </div>
                </a>
                
                <div style="padding: 0.75rem 0 0 0;">
                    <h3 class="font-heading movie-title-heading">
                        <a href="{{ route('movies.show', $movie) }}" style="color: inherit; text-decoration: none;">
                            {{ $movie->title }}
                        </a>
                    </h3>
                    <p style="font-size: 0.75rem; color: var(--clr-text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">{{ $movie->genre }}</p>
                    <div style="display: flex; align-items: center; gap: 0.25rem; margin-top: 0.35rem;">
                        <i class='bx bxs-star' style="color: var(--clr-primary); font-size: 0.9rem;"></i>
                        <span style="font-size: 0.8rem; font-weight: 700; color: #fff;">{{ number_format($movie->rating, 1) }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; padding: 5rem 2rem; text-align: center; background: var(--clr-surface-2); border-radius: var(--radius); border: 1px dashed var(--clr-border);">
                <i class='bx bx-film' style="font-size: 4rem; color: var(--clr-primary); margin-bottom: 1.5rem; display: block;"></i>
                <h3 class="font-heading font-bold" style="color: #fff; font-size: 1.4rem; text-transform: uppercase; letter-spacing: 0.5px;">Film tidak ditemukan</h3>
                <p class="text-muted text-sm mt-2" style="font-weight: 500;">Coba gunakan kata kunci pencarian lain.</p>
                @if(request('search'))
                    <a href="{{ route('movies.index', ['status' => request('status')]) }}" class="btn btn-outline btn-sm mt-3" style="font-weight: 700;">Reset Pencarian</a>
                @endif
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($movies->hasPages())
        <div style="margin-top: 4rem; display: flex; justify-content: center;">
            {{ $movies->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .pill {
        display: inline-block;
        padding: 0.6rem 1.6rem;
        border-radius: var(--radius);
        font-size: 0.85rem;
        font-weight: 700;
        font-family: var(--font-heading);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--clr-text-muted);
        background: rgba(22, 22, 22, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.08);
        text-decoration: none;
        white-space: nowrap;
        transition: var(--transition);
    }
    .pill:hover {
        color: #fff;
        border-color: var(--clr-primary);
        background: var(--clr-surface-3);
    }
    
    .pill-active {
        background: var(--clr-primary) !important;
        color: #000000 !important;
        border-color: var(--clr-primary) !important;
        box-shadow: 0 4px 15px rgba(255, 90, 0, 0.3);
    }
    
    .search-input:focus {
        border-color: var(--clr-primary) !important;
        box-shadow: 0 0 0 2px rgba(255, 90, 0, 0.15) !important;
    }
    
    .movie-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.75rem;
    }
    
    .movie-poster-wrapper {
        position: relative; 
        padding-bottom: 145%; 
        border-radius: var(--radius); 
        overflow: hidden; 
        background: var(--clr-surface-2); 
        margin-bottom: 0.25rem; 
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 8px 24px rgba(0,0,0,0.6);
        transition: var(--transition);
    }

    .movie-poster-img {
        position: absolute; 
        inset: 0; 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
        transition: transform 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .movie-card-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.25s cubic-bezier(0.2, 0.8, 0.2, 1);
        z-index: 3;
    }

    .movie-card:hover .movie-poster-img {
        transform: scale(1.06);
    }

    .movie-card:hover .movie-card-overlay {
        opacity: 1;
    }
    
    .movie-card:hover .movie-poster-wrapper {
        border-color: var(--clr-primary);
        box-shadow: 0 10px 28px rgba(255, 90, 0, 0.15);
    }

    .badge-age-dark {
        background-color: rgba(0, 0, 0, 0.85); 
        color: white; 
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: var(--radius-sm); 
        padding: 2px 6px; 
        font-size: 0.65rem; 
        font-weight: 700;
        font-family: var(--font-body);
    }

    .badge-format {
        background-color: transparent;
        color: var(--clr-primary);
        border: 1px solid var(--clr-primary);
        border-radius: var(--radius-sm);
        padding: 2px 6px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        font-family: var(--font-body);
        text-align: center;
    }

    .movie-title-heading {
        font-size: 1.05rem; 
        font-weight: 600; 
        line-height: 1.25; 
        margin-bottom: 0.25rem; 
        display: -webkit-box; 
        -webkit-line-clamp: 2; 
        -webkit-box-orient: vertical; 
        overflow: hidden;
        color: #ffffff;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        transition: var(--transition);
    }
    
    .movie-title-heading a:hover {
        color: var(--clr-primary) !important;
    }

    .card-link {
        display: block; 
        text-decoration: none; 
        color: inherit;
    }

    @media (min-width: 640px) {
        .movie-grid { grid-template-columns: repeat(3, 1fr); gap: 1.75rem; }
    }
    
    @media (min-width: 1024px) {
        .movie-grid { grid-template-columns: repeat(5, 1fr); gap: 2rem; }
    }
    
    /* Override bootstrap pagination to match dark theme */
    .pagination { display: flex; list-style: none; gap: 0.5rem; padding: 0; }
    .page-item .page-link {
        display: flex; align-items: center; justify-content: center;
        width: 40px; height: 40px; border-radius: var(--radius);
        background: rgba(22, 22, 22, 0.9); color: var(--clr-text);
        border: 1px solid rgba(255, 255, 255, 0.08);
        text-decoration: none; font-size: 0.9rem; font-weight: 700;
        font-family: var(--font-heading);
        transition: var(--transition);
    }
    .page-item .page-link:hover {
        border-color: var(--clr-primary);
        color: var(--clr-primary);
        background: var(--clr-surface-3);
    }
    .page-item.active .page-link {
        background: var(--clr-primary); color: #000000; border-color: var(--clr-primary);
        box-shadow: 0 4px 15px rgba(255, 90, 0, 0.3);
    }
    .page-item.disabled .page-link {
        color: var(--clr-text-muted); background: var(--clr-surface-2); border-color: rgba(255, 255, 255, 0.05); opacity: 0.4; cursor: not-allowed;
    }
</style>
@endpush
