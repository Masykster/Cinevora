@extends('layouts.app')
@section('title', 'Cinevora - Bioskop Terbaik')

@section('content')

{{-- HERO CAROUSEL --}}
@if($nowPlaying->count() > 0)
    @php
        $carouselMovies = $nowPlaying->take(5);
    @endphp
    <div class="netflix-hero-carousel">
        <div class="hero-slides-container">
            @foreach($carouselMovies as $index => $movie)
                <div class="hero-slide" style="background: url('{{ $movie->banner_url }}') center/cover no-repeat;">
                    <!-- Linear vignettes -->
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, #0c0c0c 0%, rgba(12,12,12,0.8) 15%, rgba(12,12,12,0) 60%, rgba(12,12,12,0.6) 100%);"></div>
                    <div style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(12,12,12,0.95) 0%, rgba(12,12,12,0.7) 20%, transparent 60%);"></div>
                    
                    <!-- Content container -->
                    <div class="container" style="position: absolute; bottom: 15%; left: 0; right: 0; padding: 0 2.5rem; z-index: 10; pointer-events: none;">
                        <div style="max-width: 650px; pointer-events: auto;">
                            <!-- Rating -->
                            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                                <span style="color: #fff; font-weight: 700; font-size: 0.9rem; font-family: var(--font-heading); text-shadow: 0 2px 4px rgba(0,0,0,0.8);">
                                    ⭐ {{ number_format($movie->rating, 1) }}
                                </span>
                            </div>
                            
                            <!-- Title -->
                            <h1 class="font-heading" style="font-size: 3.5rem; font-weight: 900; color: #fff; text-shadow: 2px 2px 8px rgba(0,0,0,0.9); line-height: 1.1; margin-bottom: 1.25rem; text-transform: uppercase; letter-spacing: -0.5px;">
                                {{ $movie->title }}
                            </h1>
                            
                            <!-- Synopsis -->
                            <p style="font-size: 0.95rem; color: #e5e5e5; text-shadow: 1px 1px 4px rgba(0,0,0,0.9); line-height: 1.5; margin-bottom: 2rem; font-weight: 500; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; max-width: 580px;">
                                {{ $movie->synopsis }}
                            </p>
                            
                            <!-- Buttons -->
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <!-- Lihat Trailer (White bg, black text, with play icon) -->
                                @if($movie->trailer_url)
                                    <button onclick="openTrailerFromUrl('{{ $movie->trailer_url }}')" class="hero-btn-play">
                                        <i class='bx bx-play' style="font-size: 1.8rem; margin-right: 2px;"></i> Lihat Trailer
                                    </button>
                                @endif
                                
                                <!-- Pesan Sekarang (Gray bg, white text, ticket icon) -->
                                <a href="{{ route('movies.show', $movie) }}" class="hero-btn-info">
                                    <i class='bx bx-ticket' style="font-size: 1.4rem;"></i> Pesan Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Navigation Chevrons -->
        <button class="hero-arrow prev" onclick="moveHeroSlide(-1)" aria-label="Previous Slide">
            <i class='bx bx-chevron-left'></i>
        </button>
        <button class="hero-arrow next" onclick="moveHeroSlide(1)" aria-label="Next Slide">
            <i class='bx bx-chevron-right'></i>
        </button>
        
        <!-- Dots Indicators -->
        <div style="position: absolute; bottom: 5%; right: 2.5rem; display: flex; gap: 0.5rem; z-index: 15;">
            @foreach($carouselMovies as $index => $movie)
                <div class="hero-dot {{ $index === 0 ? 'active' : '' }}" onclick="goHeroSlide({{ $index }})"></div>
            @endforeach
        </div>
    </div>
@else
    <div style="position: relative; height: 40vh; background: linear-gradient(135deg, #121212, #000000); display: flex; align-items: center; justify-content: center; text-align: center; border-bottom: 1px solid var(--clr-border);">
        <div class="container">
            <h1 class="font-heading" style="font-size: 3rem; font-weight: 700; color: var(--clr-primary); letter-spacing: 1px;">Cinevora</h1>
            <p class="text-muted" style="font-size: 1rem; font-weight: 500;">Temukan film bioskop terbaik dan pesan tiket dengan mudah.</p>
        </div>
    </div>
@endif

{{-- QUICK BOOKING WIDGET --}}
<div class="container" style="margin-top: -3rem; position: relative; z-index: 10; margin-bottom: 3.5rem;">
    <div class="booking-widget-card" style="padding: 1.5rem; border-radius: var(--radius-lg); border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 20px 40px rgba(0,0,0,0.95);">
        <form action="" method="GET" id="quickBookForm" onsubmit="event.preventDefault(); navigateToMovie();" style="display: flex; gap: 1.25rem; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 240px;">
                <label class="form-label" style="color: var(--clr-primary); font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">1. Pilih Bioskop</label>
                <select id="quickCinema" class="form-select widget-select" style="height: 46px; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <option value="">Semua Bioskop</option>
                    @foreach($cinemas as $cinema)
                        <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1; min-width: 240px;">
                <label class="form-label" style="color: var(--clr-primary); font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">2. Pilih Film</label>
                <select id="quickMovie" class="form-select widget-select" style="height: 46px; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;" required>
                    <option value="" disabled selected>Pilih Film...</option>
                    @foreach($nowPlaying as $movie)
                        <option value="{{ $movie->id }}" data-url="{{ route('movies.show', $movie) }}">{{ $movie->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" style="height: 46px; padding: 0 2.5rem; font-weight: 700; font-size: 0.95rem;">
                    Cari Tiket <i class='bx bx-right-arrow-alt' style="font-size: 1.3rem; margin-left: 0.25rem;"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="container section" style="padding-top: 1rem;">
    {{-- NOW PLAYING SECTION --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 2px solid var(--clr-border); padding-bottom: 0.75rem;">
        <div>
            <h2 class="font-heading section-title">Lagi Tayang</h2>
            <p class="text-muted text-xs" style="text-transform: uppercase; letter-spacing: 0.8px; font-weight: 600; margin-top: 0.25rem;">Film menarik yang sedang diputar hari ini</p>
        </div>
        <a href="{{ route('movies.index') }}" class="btn btn-outline btn-sm" style="font-weight: 700; font-size: 0.75rem; padding: 0.4rem 1.15rem;">
            Lihat semua <i class='bx bx-chevron-right' style="font-size: 0.9rem;"></i>
        </a>
    </div>

    {{-- MOVIE LISTING GRID --}}
    <div class="scroll-container">
        @forelse($nowPlaying as $movie)
            <div class="movie-card">
                <a href="{{ route('movies.show', $movie) }}" class="card-link">
                    <div class="movie-poster-wrapper">
                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="movie-poster-img">
                        
                        {{-- Immersive Hover overlay with Solar Orange button --}}
                        <div class="movie-card-overlay">
                            <span class="btn btn-primary btn-sm" style="font-weight: 700; font-size: 0.75rem; padding: 0.5rem 1.25rem;">Beli Tiket</span>
                        </div>
                        
                        {{-- Top Left Badges styled after myvue.com --}}
                        <div style="position: absolute; top: 0.75rem; left: 0.75rem; display: flex; flex-direction: column; gap: 0.35rem; z-index: 5;">
                            <span class="badge-age-dark">{{ $movie->age_rating }}</span>
                            <span class="badge-format">2D</span>
                        </div>
                    </div>
                </a>
                
                {{-- Movie Info --}}
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
            <div style="width: 100%; padding: 4rem 2rem; text-align: center; border: 1px dashed var(--clr-border); border-radius: var(--radius); grid-column: 1 / -1; background: var(--clr-surface-2);">
                <p class="text-muted text-sm" style="font-weight: 500;">Belum ada film yang sedang tayang.</p>
            </div>
        @endforelse
    </div>

    {{-- COMING SOON SECTION --}}
    @if($comingSoon->count() > 0)
    <div style="margin-top: 5rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 2px solid var(--clr-border); padding-bottom: 0.75rem;">
            <div>
                <h2 class="font-heading section-title">Akan Datang</h2>
                <p class="text-muted text-xs" style="text-transform: uppercase; letter-spacing: 0.8px; font-weight: 600; margin-top: 0.25rem;">Segera hadir di bioskop Cinevora</p>
            </div>
            <a href="{{ route('movies.index', ['status' => 'coming_soon']) }}" class="btn btn-outline btn-sm" style="font-weight: 700; font-size: 0.75rem; padding: 0.4rem 1.15rem;">
                Lihat semua <i class='bx bx-chevron-right' style="font-size: 0.9rem;"></i>
            </a>
        </div>
        
        <div class="scroll-container">
            @foreach($comingSoon as $movie)
                <div class="movie-card">
                    <a href="{{ route('movies.show', $movie) }}" class="card-link">
                        <div class="movie-poster-wrapper">
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="movie-poster-img">
                            
                            {{-- Top Badges --}}
                            <div style="position: absolute; top: 0.75rem; left: 0.75rem; display: flex; gap: 0.25rem; z-index: 5;">
                                <span class="badge-age-dark" style="background: #252525; border-color: #3a3a3a;">CS</span>
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
                        @if($movie->release_date)
                            <p style="font-size: 0.75rem; color: var(--clr-primary); font-weight: 700; margin-top: 0.35rem; text-transform: uppercase; letter-spacing: 0.5px;">Rilis {{ $movie->release_date->format('d M Y') }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- TRAILER MODAL --}}
<div id="trailerModal" class="trailer-modal" onclick="closeTrailer(event)">
    <button class="trailer-modal-close" onclick="closeTrailer(event)" aria-label="Close Trailer">
        <i class='bx bx-x'></i>
    </button>
    <div class="trailer-modal-content" onclick="event.stopPropagation()">
        <iframe id="trailerIframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="width: 100%; height: 100%; border-radius: 8px;"></iframe>
    </div>
</div>
@endsection

@push('scripts')
<script>
function navigateToMovie() {
    const select = document.getElementById('quickMovie');
    const selectedOption = select.options[select.selectedIndex];
    if (selectedOption && selectedOption.dataset.url) {
        window.location.href = selectedOption.dataset.url;
    }
}

// Netflix Hero Slider Logic
let currentSlideIndex = 0;
const totalSlides = {{ isset($carouselMovies) ? $carouselMovies->count() : 0 }};
let carouselInterval;

function startCarouselTimer() {
    stopCarouselTimer();
    carouselInterval = setInterval(() => {
        moveHeroSlide(1);
    }, 7000);
}

function stopCarouselTimer() {
    if (carouselInterval) {
        clearInterval(carouselInterval);
    }
}

function showHeroSlide(index) {
    if (totalSlides === 0) return;
    
    if (index >= totalSlides) {
        currentSlideIndex = 0;
    } else if (index < 0) {
        currentSlideIndex = totalSlides - 1;
    } else {
        currentSlideIndex = index;
    }

    // Slide transition translation
    const slidesContainer = document.querySelector('.hero-slides-container');
    if (slidesContainer) {
        slidesContainer.style.transform = `translateX(-${currentSlideIndex * 100}%)`;
    }

    // Dot indicators active class toggle
    document.querySelectorAll('.hero-dot').forEach((dot, i) => {
        if (i === currentSlideIndex) {
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });
}

function moveHeroSlide(direction) {
    showHeroSlide(currentSlideIndex + direction);
    startCarouselTimer();
}

function goHeroSlide(index) {
    showHeroSlide(index);
    startCarouselTimer();
}

// Trailer Modal helpers
function getYouTubeEmbedUrl(url) {
    if (!url) return '';
    let videoId = '';
    const shortMatch = url.match(/youtu\.be\/([\w-]+)/);
    if (shortMatch) { videoId = shortMatch[1]; }
    const longMatch = url.match(/[?&]v=([\w-]+)/);
    if (longMatch) { videoId = longMatch[1]; }
    const embedMatch = url.match(/embed\/([\w-]+)/);
    if (embedMatch) { videoId = embedMatch[1]; }
    if (!videoId) return url;
    return 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
}

function openTrailerFromUrl(url) {
    const modal = document.getElementById('trailerModal');
    const iframe = document.getElementById('trailerIframe');
    iframe.src = getYouTubeEmbedUrl(url);
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeTrailer(e) {
    if (e) e.stopPropagation();
    const modal = document.getElementById('trailerModal');
    const iframe = document.getElementById('trailerIframe');
    modal.classList.remove('active');
    iframe.src = '';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeTrailer();
});

// Start autoplay
document.addEventListener('DOMContentLoaded', () => {
    if (totalSlides > 0) {
        startCarouselTimer();
    }
});
</script>
@endpush

@push('styles')
<style>
    /* Premium frosted glass card for quick book */
    .booking-widget-card {
        background: rgba(10, 10, 10, 0.75);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    
    .widget-select {
        background-color: rgba(22, 22, 22, 0.9) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
        transition: var(--transition);
        color: #ffffff;
    }
    
    .widget-select:focus {
        border-color: var(--clr-primary) !important;
        box-shadow: 0 0 0 2px rgba(255, 90, 0, 0.15) !important;
    }

    /* Netflix Hero Carousel Styles */
    .netflix-hero-carousel {
        position: relative;
        width: 100%;
        height: 75vh;
        min-height: 520px;
        background: #000;
        overflow: hidden;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--clr-border);
    }
    
    .hero-slides-container {
        display: flex;
        width: 100%;
        height: 100%;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .hero-slide {
        flex: 0 0 100%;
        width: 100%;
        height: 100%;
        position: relative;
    }
    
    .hero-btn-play {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.65rem 1.8rem;
        border-radius: 4px;
        background: #ffffff;
        color: #000000;
        font-family: var(--font-heading);
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.15);
    }
    .hero-btn-play:hover {
        background: rgba(255, 255, 255, 0.85);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 255, 255, 0.25);
    }
    .hero-btn-play:active {
        transform: translateY(0);
    }
    
    .hero-btn-info {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.65rem 1.8rem;
        border-radius: 4px;
        background: rgba(109, 109, 110, 0.7);
        color: #ffffff;
        font-family: var(--font-heading);
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        border: none;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        letter-spacing: 0.5px;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
    .hero-btn-info:hover {
        background: rgba(109, 109, 110, 0.45);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
    }
    .hero-btn-info:active {
        transform: translateY(0);
    }

    .hero-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.3);
        border: none;
        color: rgba(255, 255, 255, 0.7);
        font-size: 2.8rem;
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 15;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(4px);
    }
    .hero-arrow:hover {
        background: rgba(0,0,0,0.7);
        color: #ffffff;
        transform: translateY(-50%) scale(1.1);
    }
    .hero-arrow.prev {
        left: 1.5rem;
    }
    .hero-arrow.next {
        right: 1.5rem;
    }
    
    .hero-dot {
        width: 20px;
        height: 3px;
        background: rgba(255, 255, 255, 0.3);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 1.5px;
    }
    .hero-dot.active {
        background: #ffffff;
        width: 32px;
    }
    
    /* Trailer Modal styles for Home */
    .trailer-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0, 0, 0, 0.92);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .trailer-modal.active {
        opacity: 1;
        visibility: visible;
    }
    .trailer-modal-close {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #fff;
        font-size: 1.5rem;
        transition: all 0.2s ease;
        z-index: 10;
    }
    .trailer-modal-close:hover {
        background: var(--clr-primary);
        border-color: var(--clr-primary);
        color: #000;
    }
    .trailer-modal-content {
        width: 90vw;
        max-width: 1100px;
        aspect-ratio: 16 / 9;
    }
    
    @media (max-width: 768px) {
        .netflix-hero-carousel {
            height: 60vh;
        }
        .hero-slide h1 {
            font-size: 2.2rem !important;
        }
        .hero-slide p {
            font-size: 0.85rem !important;
            max-width: 100% !important;
        }
        .hero-arrow {
            width: 40px;
            height: 40px;
            font-size: 2rem;
        }
        .hero-arrow.prev { left: 0.5rem; }
        .hero-arrow.next { right: 0.5rem; }
    }

    /* Section titles styling */
    .section-title {
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #ffffff;
        text-transform: uppercase;
        line-height: 1;
    }

    /* Clean horizontal scroll styling */
    .scroll-container {
        display: flex;
        overflow-x: auto;
        gap: 1.5rem;
        padding-bottom: 1.5rem;
        scroll-snap-type: x mandatory;
        scrollbar-width: none; /* Firefox */
    }
    
    .scroll-container::-webkit-scrollbar {
        display: none; /* Safari and Chrome */
    }
    
    .movie-card {
        flex: 0 0 calc(50vw - 2.25rem);
        max-width: 190px;
        scroll-snap-align: start;
        transition: var(--transition);
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
        .movie-card { flex: 0 0 calc(33.333vw - 2.5rem); }
    }
    
    @media (min-width: 1024px) {
        .scroll-container { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1.75rem; overflow-x: visible; }
        .movie-card { flex: auto; max-width: none; }
    }

    /* Responsive adjustments for Carousel */
    @media (max-width: 768px) {
        .carousel-wrapper {
            aspect-ratio: 1.8 / 1 !important;
            min-height: 200px !important;
        }
        .carousel-container {
            padding: 0 1rem !important;
        }
        .carousel-arrow {
            display: none !important; /* Hide arrows on mobile */
        }
        .carousel-info-slide {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 1rem !important;
        }
        .carousel-book-btn {
            width: 100%;
        }
    }
</style>
@endpush

