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
                <div class="hero-slide" style="position: relative;">
                    @php
                        $bannerResp = \App\Helpers\ImageHelper::getResponsiveAttributes($movie->banner_url, 'banner');
                    @endphp
                    <img src="{{ $bannerResp['src'] }}" 
                         @if($bannerResp['srcset']) srcset="{{ $bannerResp['srcset'] }}" sizes="{{ $bannerResp['sizes'] }}" @endif
                         alt="{{ $movie->title }}" 
                         style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;"
                         loading="eager">
                    <!-- Linear vignettes -->
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, #0c0c0c 0%, rgba(12,12,12,0.8) 15%, rgba(12,12,12,0) 60%, rgba(12,12,12,0.6) 100%); z-index: 2;"></div>
                    <div style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(12,12,12,0.95) 0%, rgba(12,12,12,0.7) 20%, transparent 60%); z-index: 2;"></div>
                    
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
            <img src="{{ asset('images/cinevora-logo.png') }}" alt="Cinevora Logo" style="height: 120px; object-fit: contain; margin-bottom: 1rem;">
            <p class="text-muted" style="font-size: 1rem; font-weight: 500;">Temukan film bioskop terbaik dan pesan tiket dengan mudah.</p>
        </div>
    </div>
@endif



<div class="container section" style="padding-top: 1rem;">
    {{-- PROMO NEWS CAROUSEL --}}
    <div class="promo-news-carousel-section" style="margin-bottom: 4rem; position: relative; overflow: visible;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.5rem; border-bottom: 1px solid var(--clr-border); padding-bottom: 0.75rem;">
            <div>
                <h2 class="font-heading section-title" style="font-size: 1.5rem; color: var(--clr-primary);">PROMO &amp; INFO TERBARU</h2>
                <p class="text-muted text-xs" style="text-transform: uppercase; letter-spacing: 0.8px; font-weight: 600; margin-top: 0.25rem;">Penawaran eksklusif dan kabar terbaru bioskop</p>
            </div>
        </div>
        
        @if($promos->count() > 0)
            <div class="promo-carousel-wrapper">
                <div class="promo-carousel-track">
                    @foreach($promos as $promo)
                        <div class="promo-carousel-slide">
                            @php
                                $promoResp = \App\Helpers\ImageHelper::getResponsiveAttributes($promo->image_url, 'promo');
                            @endphp
                            @if($promo->link_url)
                                <a href="{{ $promo->link_url }}" target="_blank" class="promo-slide-inner">
                                    <img src="{{ $promoResp['src'] }}" 
                                         @if($promoResp['srcset']) srcset="{{ $promoResp['srcset'] }}" sizes="{{ $promoResp['sizes'] }}" @endif
                                         alt="{{ $promo->title }}" class="skeleton-img promo-banner-img" loading="lazy">
                                </a>
                            @else
                                <div class="promo-slide-inner">
                                    <img src="{{ $promoResp['src'] }}" 
                                         @if($promoResp['srcset']) srcset="{{ $promoResp['srcset'] }}" sizes="{{ $promoResp['sizes'] }}" @endif
                                         alt="{{ $promo->title }}" class="skeleton-img promo-banner-img" loading="lazy">
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Navigation Arrows (outside cards, inside section) -->
            <button class="promo-carousel-arrow prev" onclick="movePromoSlide(-1)" aria-label="Previous Slide">
                <i class='bx bx-chevron-left'></i>
            </button>
            <button class="promo-carousel-arrow next" onclick="movePromoSlide(1)" aria-label="Next Slide">
                <i class='bx bx-chevron-right'></i>
            </button>
        @else
            <div style="width: 100%; padding: 4rem 2rem; text-align: center; border: 1px dashed var(--clr-border); border-radius: var(--radius); background: var(--clr-surface-2);">
                <p class="text-muted text-sm" style="font-weight: 500;">Belum ada promo terbaru.</p>
            </div>
        @endif
    </div>

    {{-- NOW PLAYING SECTION --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 1px solid var(--clr-border); padding-bottom: 0.75rem;">
        <div>
            <h2 class="font-heading section-title">SEDANG TAYANG</h2>
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
                        @php
                            $posterResp = \App\Helpers\ImageHelper::getResponsiveAttributes($movie->poster_url, 'poster');
                        @endphp
                        <img src="{{ $posterResp['src'] }}" 
                             @if($posterResp['srcset']) srcset="{{ $posterResp['srcset'] }}" sizes="{{ $posterResp['sizes'] }}" @endif
                             alt="{{ $movie->title }}" class="movie-poster-img skeleton-img" loading="lazy">
                        
                        {{-- Immersive Hover overlay with Gold button --}}
                        <div class="movie-card-overlay hide-on-mobile" style="flex-direction: column; gap: 0.5rem;">
                            @if($movie->trailer_url)
                                <span onclick="window.open('{{ $movie->trailer_url }}', '_blank'); event.preventDefault(); event.stopPropagation();" class="btn btn-outline btn-sm" style="font-weight: 700; font-size: 0.75rem; padding: 0.5rem 1.25rem; text-transform: uppercase; border-radius: 4px; display: inline-block; text-align: center; border-width: 2px; width: 130px; cursor: pointer; transition: var(--transition);">Lihat Trailer</span>
                            @endif
                            <span class="btn btn-primary btn-sm" style="font-weight: 700; font-size: 0.75rem; padding: 0.5rem 1.25rem; color: #000; width: 130px; text-align: center; text-transform: uppercase; border-radius: 4px;">Beli Tiket</span>
                        </div>
                        
                        {{-- Top Left Badges --}}
                        <div class="hide-on-mobile" style="position: absolute; top: 0.75rem; left: 0.75rem; display: flex; flex-direction: column; gap: 0.35rem; z-index: 5;">
                            <span class="badge-age-dark">{{ $movie->age_rating }}</span>
                            <span class="badge-format">2D</span>
                        </div>
                    </div>
                </a>
                
                {{-- Movie Info --}}
                <div style="padding: 0.75rem 0.25rem 0 0.25rem;">
                    {{-- Mobile Specific Info (Buttons below poster) --}}
                    <div class="show-on-mobile" style="margin-top: 0.25rem;">
                        <div style="display: flex; gap: 0.5rem; justify-content: center; margin-bottom: 0.75rem;">
                            @if($movie->trailer_url)
                                <a href="{{ $movie->trailer_url }}" target="_blank" class="btn btn-outline btn-sm" style="border-radius: 20px; font-size: 0.75rem; padding: 0.45rem 0.9rem; border-color: rgba(255,255,255,0.4); color: white;"><i class='bx bx-play' style="font-size:1.1rem;"></i> Lihat trailer</a>
                            @endif
                            <a href="{{ route('movies.show', $movie) }}" class="btn btn-primary btn-sm" style="border-radius: 20px; font-size: 0.75rem; padding: 0.45rem 1.1rem; background: #e5e5e5; color: black; border: none;"><i class='bx bx-ticket' style="font-size:1rem;"></i> Beli tiket</a>
                        </div>
                        <div style="display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 0.5rem; align-items: center;">
                            <span class="badge-format" style="border-color: rgba(255,255,255,0.2); color: rgba(255,255,255,0.7); font-size: 0.6rem; padding: 1px 6px;">2D</span>
                            <span class="badge-age-dark" style="background: rgba(255,255,0,0.15); border-color: transparent; color: #ffeb3b; font-size: 0.6rem; padding: 1px 6px;">{{ $movie->age_rating }}</span>
                            <span class="badge-format" style="border-color: transparent; color: rgba(255,255,255,0.6); font-size: 0.6rem; padding: 1px 0; text-transform: lowercase;">{{ floor($movie->duration / 60) }}h {{ $movie->duration % 60 }}m</span>
                        </div>
                        <h3 class="font-heading" style="text-align: center; font-size: 1rem; font-weight: 700; color: white; text-transform: uppercase; margin-bottom: 0;">
                            <a href="{{ route('movies.show', $movie) }}" style="color: inherit; text-decoration: none;">
                                {{ \Illuminate\Support\Str::limit($movie->title, 30) }}
                            </a>
                        </h3>
                    </div>

                    {{-- Desktop Original Info --}}
                    <div class="hide-on-mobile">
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
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 1px solid var(--clr-border); padding-bottom: 0.75rem;">
            <div>
                <h2 class="font-heading section-title">AKAN DATANG</h2>
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
                            @php
                                $csPosterResp = \App\Helpers\ImageHelper::getResponsiveAttributes($movie->poster_url, 'poster');
                            @endphp
                            <img src="{{ $csPosterResp['src'] }}" 
                                 @if($csPosterResp['srcset']) srcset="{{ $csPosterResp['srcset'] }}" sizes="{{ $csPosterResp['sizes'] }}" @endif
                                 alt="{{ $movie->title }}" class="movie-poster-img skeleton-img" loading="lazy">
                            
                            {{-- Top Badges --}}
                            <div class="hide-on-mobile" style="position: absolute; top: 0.75rem; left: 0.75rem; display: flex; gap: 0.25rem; z-index: 5;">
                                <span class="badge-age-dark" style="background: #252525; border-color: #3a3a3a;">CS</span>
                            </div>
                        </div>
                    </a>
                    <div style="padding: 0.75rem 0.25rem 0 0.25rem;">
                        {{-- Mobile Specific Info --}}
                        <div class="show-on-mobile" style="margin-top: 0.25rem;">
                            <div style="display: flex; gap: 0.5rem; justify-content: center; margin-bottom: 0.75rem;">
                                @if($movie->trailer_url)
                                    <a href="{{ $movie->trailer_url }}" target="_blank" class="btn btn-outline btn-sm" style="border-radius: 20px; font-size: 0.75rem; padding: 0.45rem 0.9rem; border-color: rgba(255,255,255,0.4); color: white;"><i class='bx bx-play' style="font-size:1.1rem;"></i> Lihat trailer</a>
                                @endif
                                <a href="{{ route('movies.show', $movie) }}" class="btn btn-primary btn-sm" style="border-radius: 20px; font-size: 0.75rem; padding: 0.45rem 1.1rem; background: #e5e5e5; color: black; border: none;"><i class='bx bx-info-circle' style="font-size:1rem;"></i> Info</a>
                            </div>
                            <div style="display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 0.5rem; align-items: center;">
                                <span class="badge-age-dark" style="background: #252525; border-color: #3a3a3a; font-size: 0.6rem; padding: 1px 6px;">COMING SOON</span>
                                @if($movie->release_date)
                                    <span class="badge-format" style="border-color: transparent; color: var(--clr-primary); font-size: 0.6rem; padding: 1px 0; text-transform: uppercase;">RILIS {{ $movie->release_date->format('d M') }}</span>
                                @endif
                            </div>
                            <h3 class="font-heading" style="text-align: center; font-size: 1rem; font-weight: 700; color: white; text-transform: uppercase; margin-bottom: 0;">
                                <a href="{{ route('movies.show', $movie) }}" style="color: inherit; text-decoration: none;">
                                    {{ \Illuminate\Support\Str::limit($movie->title, 30) }}
                                </a>
                            </h3>
                        </div>

                        {{-- Desktop Original Info --}}
                        <div class="hide-on-mobile">
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
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- MFOOD BANNER SECTION --}}
    <div class="mfood-promo-banner" style="margin-top: 5rem; background: linear-gradient(135deg, #1b1610 0%, #080604 100%); border: 1px solid var(--clr-border); border-radius: var(--radius-lg); padding: 2.5rem 3rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.6); position: relative; overflow: hidden;">
        <div style="position: absolute; right: -50px; bottom: -50px; width: 250px; height: 250px; border-radius: 50%; background: rgba(188, 163, 116, 0.04); filter: blur(50px);"></div>
        <div style="flex: 1; min-width: 280px; z-index: 2;">
            <span class="badge" style="background: rgba(188,163,116,0.15); color: var(--clr-primary); border: 1px solid var(--clr-primary); font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; padding: 0.3rem 0.75rem; border-radius: 4px; margin-bottom: 1rem;">Cinevora Cafe</span>
            <h2 class="font-heading" style="font-size: 2.2rem; font-weight: 800; color: #fff; line-height: 1.2; text-transform: uppercase; letter-spacing: -0.5px;">Makanan &amp; minuman enak siap nemenin nonton!</h2>
            <p style="color: var(--clr-text-muted); font-size: 0.95rem; margin-top: 0.75rem; font-weight: 500; max-width: 480px;">Pesan snack favoritmu lebih mudah secara online dan ambil tanpa antre di area Cinevora Cafe.</p>
            <div style="margin-top: 1.75rem;">
                <a href="{{ route('cafe.menu') }}" class="btn btn-primary" style="padding: 0.8rem 2.25rem; font-weight: 800; border-radius: 6px; color:#000; box-shadow: 0 4px 15px rgba(188,163,116,0.35);">
                    🍿 PESEN CAFE
                </a>
            </div>
        </div>
        <div class="mfood-visual" style="flex: 0 0 240px; display: flex; justify-content: center; align-items: center; position: relative; height: 180px; z-index: 2;">
            <span style="font-size: 8rem; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.5)); transform: rotate(-5deg); display: inline-block; animation: floatPopcorn 4s ease-in-out infinite;">🍿</span>
            <span style="font-size: 5rem; filter: drop-shadow(0 8px 15px rgba(0,0,0,0.4)); position: absolute; bottom: 0; right: 20px; transform: rotate(10deg); display: inline-block; animation: floatSoda 4s ease-in-out infinite alternate;">🥤</span>
        </div>
    </div>

    {{-- EXPERIENCE SECTION --}}
    <div style="margin-top: 5rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 1px solid var(--clr-border); padding-bottom: 0.75rem;">
            <div>
                <h2 class="font-heading section-title">CINEVORA EXPERIENCE</h2>
                <p class="text-muted text-xs" style="text-transform: uppercase; letter-spacing: 0.8px; font-weight: 600; margin-top: 0.25rem;">Rasakan sensasi menonton terbaik di studio kami</p>
            </div>
        </div>
        
        <div class="experience-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
            <!-- Deluxe -->
            <div class="experience-card" style="background: linear-gradient(180deg, #111 0%, #070707 100%); border: 1px solid var(--clr-border); border-radius: var(--radius-lg); overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; transition: var(--transition); box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
               <div style="padding: 2rem 2rem 1.25rem 2rem;">
                   <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                       <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid #10B981; font-weight: 800; font-size: 0.7rem; padding: 0.25rem 0.60rem; border-radius: 4px;">Deluxe</span>
                       <span style="font-size: 1.5rem;">🎬</span>
                   </div>
                   <h3 class="font-heading" style="font-size: 1.3rem; font-weight: 800; color: #fff; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">STUDIO DELUXE</h3>
                   <p style="color: var(--clr-text-muted); font-size: 0.85rem; line-height: 1.5; font-weight: 500;">Nikmati tontonan berkualitas tinggi dengan kursi yang nyaman, sound system Dolby Atmos, dan harga tiket yang sangat terjangkau.</p>
               </div>
               <div style="padding: 1.5rem 2rem; border-top: 1px solid rgba(255,255,255,0.02); background: rgba(255,255,255,0.01);">
                   <a href="#" class="btn btn-outline btn-sm btn-block" style="border-radius: 6px; font-weight: 700; text-transform: uppercase;" onclick="event.preventDefault(); alert('Studio Deluxe tersedia di semua cabang Cinevora.')">Baca Selengkapnya</a>
               </div>
            </div>
            
            <!-- Premiere -->
            <div class="experience-card" style="background: linear-gradient(180deg, #18130a 0%, #0d0a05 100%); border: 1px solid var(--clr-primary); border-radius: var(--radius-lg); overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; transition: var(--transition); box-shadow: 0 4px 20px rgba(188,163,116,0.15);">
               <div style="padding: 2rem 2rem 1.25rem 2rem;">
                   <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                       <span class="badge" style="background: rgba(188, 163, 116, 0.2); color: var(--clr-primary); border: 1px solid var(--clr-primary); font-weight: 800; font-size: 0.7rem; padding: 0.25rem 0.60rem; border-radius: 4px;">Premiere</span>
                       <span style="font-size: 1.5rem;">🛋️</span>
                   </div>
                   <h3 class="font-heading" style="font-size: 1.3rem; font-weight: 800; color: var(--clr-primary); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">THE PREMIERE</h3>
                   <p style="color: var(--clr-text-muted); font-size: 0.85rem; line-height: 1.5; font-weight: 500;">Rasakan kemewahan menonton dengan kursi kulit reclinable kelas satu, selimut lembut, serta pemesanan makanan premium langsung dari tempat duduk Anda.</p>
               </div>
               <div style="padding: 1.5rem 2rem; border-top: 1px solid rgba(188,163,116,0.1); background: rgba(188,163,116,0.02);">
                   <a href="#" class="btn btn-primary btn-sm btn-block" style="border-radius: 6px; font-weight: 700; text-transform: uppercase; color:#000;" onclick="event.preventDefault(); alert('The Premiere tersedia di bioskop-bioskop utama Cinevora.')">Pesan Premiere</a>
               </div>
            </div>
            
            <!-- IMAX -->
            <div class="experience-card" style="background: linear-gradient(180deg, #0d1624 0%, #050a11 100%); border: 1px solid #1e3a8a; border-radius: var(--radius-lg); overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; transition: var(--transition); box-shadow: 0 4px 20px rgba(30,58,138,0.15);">
               <div style="padding: 2rem 2rem 1.25rem 2rem;">
                   <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                       <span class="badge" style="background: rgba(59, 130, 246, 0.15); color: #3b82f6; border: 1px solid #3b82f6; font-weight: 800; font-size: 0.7rem; padding: 0.25rem 0.60rem; border-radius: 4px;">IMAX</span>
                       <span style="font-size: 1.5rem;">🔊</span>
                   </div>
                   <h3 class="font-heading" style="font-size: 1.3rem; font-weight: 800; color: #3b82f6; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">STUDIO IMAX</h3>
                   <p style="color: var(--clr-text-muted); font-size: 0.85rem; line-height: 1.5; font-weight: 500;">Rasakan visual raksasa berdefinisi tinggi yang luar biasa tajam dengan sistem proyeksi laser IMAX 4K dan sistem audio yang membuat kursi Anda bergetar.</p>
               </div>
               <div style="padding: 1.5rem 2rem; border-top: 1px solid rgba(59,130,246,0.1); background: rgba(59,130,246,0.02);">
                   <a href="#" class="btn btn-outline btn-sm btn-block" style="border-radius: 6px; font-weight: 700; text-transform: uppercase; border-color: #3b82f6; color: #3b82f6;" onclick="event.preventDefault(); alert('Studio IMAX tersedia eksklusif di Cinevora Central Mall.')">Lihat Jadwal IMAX</a>
               </div>
            </div>
        </div>
    </div>

    {{-- APP DOWNLOAD BANNER --}}
    <div class="app-download-banner" style="margin-top: 5rem; margin-bottom: 2rem; background: linear-gradient(90deg, #121212 0%, #1e1b15 100%); border: 1px solid var(--clr-border); border-radius: var(--radius-lg); padding: 2.5rem 3rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <div style="flex: 1; min-width: 280px;">
            <h3 class="font-heading" style="font-size: 1.8rem; font-weight: 800; color: #fff; line-height: 1.2; text-transform: uppercase; letter-spacing: -0.5px; margin-bottom: 0.5rem;">Download vora tix dan nikmati semua fitur dengan maksimal!</h3>
            <p style="color: var(--clr-text-muted); font-size: 0.9rem; font-weight: 500;">Beli tiket nonton, pesan f&amp;b, kumpulkan poin, dan nikmati diskon khusus eksklusif di aplikasi mobile kami.</p>
        </div>
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <a href="https://play.google.com/store" target="_blank" style="transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play Download" width="135" height="40" loading="lazy" style="height: 40px; display: block;">
            </a>
            <a href="https://www.apple.com/app-store" target="_blank" style="transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store Download" width="120" height="40" loading="lazy" style="height: 40px; display: block;">
            </a>
        </div>
    </div>
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

// Promo Carousel Logic
let promoCurrentIndex = 0;
let promoInterval;

function getPromoVisibleItems() {
    if (window.innerWidth >= 1024) return 3;
    if (window.innerWidth >= 640) return 2;
    return 1;
}

function updatePromoCarousel() {
    const track = document.querySelector('.promo-carousel-track');
    if (!track) return;
    
    const visibleItems = getPromoVisibleItems();
    const totalItems = {{ isset($promos) ? $promos->count() : 0 }};
    const maxIndex = Math.max(0, totalItems - visibleItems);
    
    // Clamp index
    if (promoCurrentIndex > maxIndex) {
        promoCurrentIndex = 0; // Wrap to beginning
    } else if (promoCurrentIndex < 0) {
        promoCurrentIndex = maxIndex; // Wrap to end
    }
    
    track.style.transform = `translateX(calc(-${promoCurrentIndex} * (100% + 1.5rem) / ${visibleItems}))`;
    
    const prevBtn = document.querySelector('.promo-carousel-arrow.prev');
    const nextBtn = document.querySelector('.promo-carousel-arrow.next');
    if (totalItems <= visibleItems) {
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
        track.style.transform = 'translateX(0)';
    } else {
        if (prevBtn) prevBtn.style.display = 'flex';
        if (nextBtn) nextBtn.style.display = 'flex';
    }
}

function movePromoSlide(direction) {
    promoCurrentIndex += direction;
    updatePromoCarousel();
    startPromoTimer();
}

function startPromoTimer() {
    stopPromoTimer();
    const visibleItems = getPromoVisibleItems();
    const totalItems = {{ isset($promos) ? $promos->count() : 0 }};
    if (totalItems <= visibleItems) return;
    
    promoInterval = setInterval(() => {
        promoCurrentIndex++;
        updatePromoCarousel();
    }, 5000);
}

function stopPromoTimer() {
    if (promoInterval) {
        clearInterval(promoInterval);
    }
}

window.addEventListener('resize', () => {
    updatePromoCarousel();
});

document.addEventListener('DOMContentLoaded', () => {
    const totalItems = {{ isset($promos) ? $promos->count() : 0 }};
    if (totalItems > 0) {
        updatePromoCarousel();
        startPromoTimer();
        
        const wrapper = document.querySelector('.promo-carousel-wrapper');
        if (wrapper) {
            wrapper.addEventListener('mouseenter', stopPromoTimer);
            wrapper.addEventListener('mouseleave', startPromoTimer);
        }
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
        box-shadow: 0 0 0 2px rgba(188, 163, 116, 0.15) !important;
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
        display: none; /* hidden on mobile/tablet */
        opacity: 0;
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
        -webkit-overflow-scrolling: touch;
    }
    
    .scroll-container::-webkit-scrollbar {
        display: none; /* Safari and Chrome */
    }
    
    .promo-carousel-wrapper {
        position: relative;
        overflow: hidden;
        width: 100%;
        border-radius: 12px;
    }
    
    .promo-carousel-track {
        display: flex;
        transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        gap: 1.5rem;
    }
    
    .promo-carousel-slide {
        flex: 0 0 100%;
        max-width: 100%;
        transition: transform 0.3s ease;
    }
    
    .promo-slide-inner {
        display: block;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,0.6);
        border: 1px solid rgba(255,255,255,0.05);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .promo-banner-img {
        width: 100%;
        aspect-ratio: 21 / 9;
        object-fit: cover;
        display: block;
        border-radius: 12px;
        transition: transform 0.4s ease;
    }
    
    @media (min-width: 640px) {
        .promo-carousel-slide {
            flex: 0 0 calc((100% - 1.5rem) / 2);
        }
    }
    
    @media (min-width: 1024px) {
        .promo-carousel-slide {
            flex: 0 0 calc((100% - 3.0rem) / 3);
        }
    }
    
    .promo-carousel-slide .promo-slide-inner:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(188, 163, 116, 0.25) !important;
    }
    
    .promo-carousel-slide .promo-slide-inner:hover img {
        transform: scale(1.03);
    }
    
    /* Arrows: hidden by default, only show on desktop + section hover */
    .promo-carousel-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(10, 10, 10, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.9);
        font-size: 2.2rem;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 15;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: none; /* hidden by default (mobile/tablet) */
        opacity: 0;
    }
    
    /* Desktop only: show on section hover */
    @media (min-width: 1024px) {
        .promo-news-carousel-section .promo-carousel-arrow {
            display: flex; /* always flex on desktop, but invisible */
            opacity: 0;
            pointer-events: none;
        }
        .promo-news-carousel-section:hover .promo-carousel-arrow {
            opacity: 1;
            pointer-events: auto;
        }

        .netflix-hero-carousel .hero-arrow {
            display: flex; /* always flex on desktop, but invisible */
            opacity: 0;
            pointer-events: none;
        }
        .netflix-hero-carousel:hover .hero-arrow {
            opacity: 1;
            pointer-events: auto;
        }
    }
    
    .promo-carousel-arrow:hover {
        background: var(--clr-primary);
        color: #000;
        border-color: var(--clr-primary);
        box-shadow: 0 0 15px rgba(188, 163, 116, 0.4);
    }
    
    .promo-carousel-arrow.prev {
        left: -8px;
    }
    
    .promo-carousel-arrow.next {
        right: -8px;
    }
    
    .movie-card {
        flex: 0 0 calc(46vw - 1.5rem);
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
        box-shadow: 0 10px 28px rgba(188, 163, 116, 0.15);
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
        font-size: 1rem; 
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

    .experience-card:hover {
        border-color: var(--clr-primary) !important;
        transform: translateY(-4px);
    }

    @keyframes floatPopcorn {
        0%, 100% { transform: translateY(0) rotate(-5deg); }
        50% { transform: translateY(-10px) rotate(-2deg); }
    }
    @keyframes floatSoda {
        0%, 100% { transform: translateY(0) rotate(10deg); }
        50% { transform: translateY(-8px) rotate(15deg); }
    }

        .show-on-mobile { display: none !important; }

    @media (max-width: 768px) {
        .hide-on-mobile { display: none !important; }
        .show-on-mobile { display: block !important; }
        .show-on-mobile.flex { display: flex !important; }

        .scroll-container {
            padding-left: 15vw;
            padding-right: 15vw;
            margin-left: -1rem;
            margin-right: -1rem;
            scroll-snap-type: x mandatory;
            gap: 1.25rem;
        }
        
        .movie-card {
            flex: 0 0 70vw !important;
            max-width: none !important;
            scroll-snap-align: center;
            text-align: center;
        }
        .mfood-promo-banner {
            padding: 2rem 1.5rem !important;
        }
        .mfood-visual {
            display: none !important;
        }
        .app-download-banner {
            padding: 2rem 1.5rem !important;
        }
        .experience-grid {
            grid-template-columns: 1fr !important;
        }
    }

    @media (min-width: 640px) {
        .movie-card { flex: 0 0 calc(33.333vw - 2.5rem); }

    }
    
    @media (min-width: 1024px) {
        .scroll-container { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1.75rem; overflow-x: visible; }

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

