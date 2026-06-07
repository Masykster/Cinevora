@extends('layouts.app')
@section('title', $movie->title)
@section('meta_description', Str::limit($movie->synopsis, 160))

@section('content')
{{-- MOVIE DETAIL HEADER WITH BACKDROP --}}
<div style="position: relative; padding: 5rem 0 2.5rem 0; overflow: hidden; display: flex; align-items: flex-end; border-bottom: 1px solid var(--clr-border);">
    @php
        $bannerResp = \App\Helpers\ImageHelper::getResponsiveAttributes($movie->banner_url, 'banner');
    @endphp
    <img src="{{ $bannerResp['src'] }}" 
         @if($bannerResp['srcset']) srcset="{{ $bannerResp['srcset'] }}" sizes="{{ $bannerResp['sizes'] }}" @endif
         alt="{{ $movie->title }}" 
         style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;"
         loading="eager">
    {{-- High contrast gradients blending into absolute black --}}
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,1) 98%); z-index: 2;"></div>
    <div style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(0,0,0,0.95), rgba(0,0,0,0.5) 50%, transparent 100%); z-index: 2;"></div>
    
    <div class="container" style="position: relative; z-index: 10; display: flex; gap: 2.5rem; align-items: flex-end; flex-wrap: wrap; width: 100%;">
        {{-- POSTER --}}
        <div style="width: 160px; flex-shrink: 0; box-shadow: 0 15px 35px rgba(0,0,0,0.95);">
            <div style="border-radius: var(--radius); overflow: hidden; border: 1px solid rgba(255,255,255,0.08);">
                @php
                    $posterResp = \App\Helpers\ImageHelper::getResponsiveAttributes($movie->poster_url, 'poster');
                @endphp
                <img src="{{ $posterResp['src'] }}" 
                     @if($posterResp['srcset']) srcset="{{ $posterResp['srcset'] }}" sizes="{{ $posterResp['sizes'] }}" @endif
                     alt="{{ $movie->title }}" class="skeleton-img" style="width: 100%; display: block;">
            </div>
        </div>

        {{-- INFO --}}
        <div style="flex: 1; min-width: 280px; padding-bottom: 0.5rem;">
            <div style="display: flex; gap: 0.5rem; margin-bottom: 0.75rem; flex-wrap: wrap; align-items: center;">
                <span class="badge-age-dark">{{ $movie->age_rating }}</span>
                <span class="badge-format">2D</span>
                <span style="font-size: 0.8rem; color: var(--clr-text-muted); display: flex; align-items: center; gap: 0.3rem; margin-left: 0.5rem; font-family: var(--font-heading); text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class='bx bx-time' style="color: var(--clr-primary); font-size: 1.1rem; vertical-align: middle;"></i> {{ $movie->duration_formatted }}
                </span>
            </div>
            
            <h1 class="font-heading" style="font-size: 2.8rem; font-weight: 700; line-height: 1.1; margin-bottom: 0.75rem; color: #fff; text-shadow: 0 4px 15px rgba(0,0,0,0.9); letter-spacing: 0.5px; text-transform: uppercase;">
                {{ $movie->title }}
            </h1>
            
            <p style="font-size: 0.85rem; color: var(--clr-primary); font-weight: 700; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px; font-family: var(--font-heading);">
                {{ $movie->genre }}
            </p>
            
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <i class='bx bxs-star' style="color: var(--clr-primary); font-size: 1.2rem;"></i>
                <strong style="font-size: 1.2rem; color: #fff; font-family: var(--font-heading); letter-spacing: 0.5px;">{{ number_format($movie->rating, 1) }}</strong>
                <span class="text-xs text-muted" style="text-transform: uppercase; letter-spacing: 0.8px; font-weight: 600;">/ 10 Rating Cinevora</span>
            </div>
        </div>

        {{-- PLAY TRAILER BUTTON --}}
        @if($movie->trailer_url)
        <div style="flex-shrink: 0; display: flex; align-items: center; justify-content: center; margin-left: auto; padding-bottom: 1.5rem;">
            <button onclick="openTrailer()" class="play-trailer-btn" aria-label="Play Trailer">
                <div class="play-trailer-circle">
                    <i class='bx bx-play' style="font-size: 2.5rem; color: #fff; margin-left: 4px;"></i>
                </div>
                <span class="play-trailer-label">Play Trailer</span>
            </button>
        </div>
        @endif
    </div>
</div>

{{-- TRAILER MODAL --}}
@if($movie->trailer_url)
<div id="trailerModal" class="trailer-modal" onclick="closeTrailer(event)">
    <button class="trailer-modal-close" onclick="closeTrailer(event)" aria-label="Close Trailer">
        <i class='bx bx-x'></i>
    </button>
    <div class="trailer-modal-content" onclick="event.stopPropagation()">
        <iframe id="trailerIframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="width: 100%; height: 100%; border-radius: 8px;"></iframe>
    </div>
</div>
@endif

<div class="container section" style="padding-top: 2.5rem;">
    {{-- SYNOPSIS & ACTORS --}}
    <div style="margin-bottom: 3.5rem; background: var(--clr-surface-2); padding: 2rem; border-radius: var(--radius); border: 1px solid var(--clr-border); box-shadow: 0 8px 24px rgba(0,0,0,0.5);">
        <h3 class="font-heading" style="font-weight: 700; margin-bottom: 1rem; font-size: 1.2rem; text-transform: uppercase; letter-spacing: 1px; color: var(--clr-primary);">Sinopsis</h3>
        <p class="text-muted text-sm" style="line-height: 1.8; font-weight: 500; font-size: 0.9rem;">{{ $movie->synopsis }}</p>
        
        <div style="margin-top: 2rem; display: flex; gap: 4rem; flex-wrap: wrap; border-top: 1px solid var(--clr-border); padding-top: 1.5rem; margin-bottom: 2rem;">
            <div>
                <span class="text-xs text-muted" style="display: block; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 0.4rem;">Sutradara</span>
                <span style="font-size: 0.95rem; font-weight: 700; color: #fff; text-transform: uppercase; font-family: var(--font-heading); letter-spacing: 0.5px;">{{ $movie->director }}</span>
            </div>
        </div>

        @if(count($movie->cast_list) > 0)
        <div style="border-top: 1px solid var(--clr-border); padding-top: 1.5rem;">
            <h3 class="font-heading" style="font-weight: 700; margin-bottom: 1.5rem; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px; color: var(--clr-primary);">Pemeran</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 1.8rem; align-items: flex-start;">
                @foreach($movie->cast_list as $actor)
                    <div class="cast-member">
                        {{-- Avatar Circle --}}
                        @if(!empty($movie->cast_images) && isset($movie->cast_images[$actor]) && $movie->cast_images[$actor])
                            <div class="cast-avatar">
                                <img src="{{ $movie->cast_images[$actor] }}" alt="{{ $actor }}" class="skeleton-img" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            </div>
                        @else
                            <div class="cast-avatar cast-initials">
                                {{ App\Models\Movie::getInitials($actor) }}
                            </div>
                        @endif
                        {{-- Actor Name --}}
                        <span class="cast-name">
                            {{ $actor }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- SCHEDULES --}}
    @if($movie->is_now_playing && $cinemas->count() > 0)
    <div style="margin-top: 1.5rem;">
        <h2 class="font-heading" style="font-size: 1.8rem; font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 0.5px; color: #fff; border-left: 4px solid var(--clr-primary); padding-left: 0.75rem; line-height: 1;">Jadwal Tayang</h2>

        {{-- DATE TABS HORIZONTAL --}}
        @if($dates->count() > 0)
        <div style="display: flex; gap: 0.6rem; margin-bottom: 2.5rem; overflow-x: auto; padding-bottom: 0.75rem; scrollbar-width: none;" id="dateTabs">
            <style>#dateTabs::-webkit-scrollbar { display: none; }</style>
            @foreach($dates as $i => $date)
                <button onclick="showDate('{{ $date->format('Y-m-d') }}')"
                    class="btn {{ $i === 0 ? 'date-tab-active' : 'date-tab-inactive' }} date-tab"
                    data-date="{{ $date->format('Y-m-d') }}">
                    <span style="display: block; font-size: 0.65rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 0.15rem;">{{ $date->format('D') }}</span>
                    <span style="font-size: 1.1rem; font-weight: 700; font-family: var(--font-heading); letter-spacing: 0.5px; line-height: 1;">{{ $date->format('d M') }}</span>
                </button>
            @endforeach
        </div>
        @endif

        {{-- CINEMA SCHEDULES --}}
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            @foreach($cinemas as $cinema)
                <div class="card" style="border: 1px solid var(--clr-border); border-radius: var(--radius); background: var(--clr-surface); box-shadow: 0 10px 30px rgba(0,0,0,0.8);">
                    <div style="padding: 1.25rem 2rem; border-bottom: 1px solid var(--clr-border); background: var(--clr-surface-2);">
                        <h3 class="font-heading" style="font-weight: 700; font-size: 1.3rem; display: flex; align-items: center; gap: 0.6rem; color: #fff; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class='bx bxs-camera-movie' style="color: var(--clr-primary); font-size: 1.4rem;"></i> {{ $cinema->name }}
                        </h3>
                    </div>

                    <div style="padding: 2rem; display: flex; flex-direction: column; gap: 2rem;">
                        @foreach($cinema->studios as $studio)
                            <div style="border-bottom: 1px solid var(--clr-border); padding-bottom: 1.5rem; margin-bottom: 0.25rem;" class="studio-section">
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
                                    <span style="font-weight: 700; font-size: 0.95rem; color: #fff; text-transform: uppercase; font-family: var(--font-heading); letter-spacing: 0.5px;">{{ $studio->name }}</span>
                                    <span class="badge-studio-type badge-{{ $studio->type === 'imax' ? 'imax' : ($studio->type === 'vip' ? 'vip' : 'regular') }}">
                                        {{ $studio->type_label }}
                                    </span>
                                </div>
        
                                <div style="display: flex; gap: 0.85rem; flex-wrap: wrap;">
                                    @foreach($studio->schedules as $schedule)
                                        <div class="schedule-slot" data-date="{{ $schedule->show_date->format('Y-m-d') }}" style="{{ $schedule->show_date->format('Y-m-d') !== $dates->first()->format('Y-m-d') ? 'display:none;' : '' }}">
                                            @auth
                                                <a href="{{ route('booking.seats', $schedule) }}" class="time-btn">
                                                    {{ $schedule->show_time_formatted }}
                                                </a>
                                            @else
                                                <a href="{{ route('login') }}" class="time-btn">
                                                    {{ $schedule->show_time_formatted }}
                                                </a>
                                            @endauth
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @elseif(!$movie->is_now_playing && $movie->status === 'coming_soon')
    <div class="text-center" style="padding: 5rem 2rem; background: var(--clr-surface-2); border-radius: var(--radius); border: 1px dashed var(--clr-border);">
        <i class='bx bx-calendar' style="font-size: 4rem; margin-bottom: 1.5rem; color: var(--clr-primary); display: block;"></i>
        <h3 class="font-heading" style="font-weight: 700; color: #fff; font-size: 1.5rem; letter-spacing: 0.5px; text-transform: uppercase;">Segera Tayang</h3>
        <p class="text-muted mt-2 text-sm" style="font-weight: 500;">Menantikan penayangan perdana pada {{ $movie->release_date ? $movie->release_date->format('d M Y') : '-' }}</p>
    </div>
    @else
    <div class="text-center" style="padding: 5rem 2rem; background: var(--clr-surface-2); border-radius: var(--radius); border: 1px dashed var(--clr-border);">
        <i class='bx bx-info-circle' style="font-size: 4rem; margin-bottom: 1.5rem; color: var(--clr-primary); display: block;"></i>
        <h3 class="font-heading" style="font-weight: 700; color: #fff; font-size: 1.5rem; letter-spacing: 0.5px; text-transform: uppercase;">Tidak Ada Jadwal</h3>
        <p class="text-muted mt-2 text-sm" style="font-weight: 500;">Belum ada jadwal penayangan untuk film ini saat ini.</p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function showDate(date) {
    document.querySelectorAll('.date-tab').forEach(tab => {
        if (tab.dataset.date === date) {
            tab.classList.remove('date-tab-inactive');
            tab.classList.add('date-tab-active');
        } else {
            tab.classList.remove('date-tab-active');
            tab.classList.add('date-tab-inactive');
        }
    });
    document.querySelectorAll('.schedule-slot').forEach(slot => {
        slot.style.display = slot.dataset.date === date ? '' : 'none';
    });
}

// Trailer Modal
function getYouTubeEmbedUrl(url) {
    if (!url) return '';
    let videoId = '';
    // Handle youtu.be/ID
    const shortMatch = url.match(/youtu\.be\/([\w-]+)/);
    if (shortMatch) { videoId = shortMatch[1]; }
    // Handle youtube.com/watch?v=ID
    const longMatch = url.match(/[?&]v=([\w-]+)/);
    if (longMatch) { videoId = longMatch[1]; }
    // Handle youtube.com/embed/ID
    const embedMatch = url.match(/embed\/([\w-]+)/);
    if (embedMatch) { videoId = embedMatch[1]; }
    if (!videoId) return url;
    return 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
}

function openTrailer() {
    const modal = document.getElementById('trailerModal');
    const iframe = document.getElementById('trailerIframe');
    const rawUrl = @json($movie->trailer_url ?? '');
    iframe.src = getYouTubeEmbedUrl(rawUrl);
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
</script>
@endpush

@push('styles')
<style>
    .badge-age-dark {
        background-color: rgba(0, 0, 0, 0.85); 
        color: white; 
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: var(--radius-sm); 
        padding: 2px 8px; 
        font-size: 0.75rem; 
        font-weight: 700;
        font-family: var(--font-body);
    }

    .badge-format {
        background-color: transparent;
        color: var(--clr-primary);
        border: 1px solid var(--clr-primary);
        border-radius: var(--radius-sm);
        padding: 2px 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        font-family: var(--font-body);
        text-align: center;
    }

    /* Date Selector Tabs */
    .date-tab {
        flex-direction: column; 
        padding: 0.75rem 1.75rem; 
        min-width: 90px;
        border-radius: var(--radius);
        transition: var(--transition);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    
    .date-tab-active {
        background: var(--clr-primary) !important;
        color: #000000 !important;
        border-color: var(--clr-primary) !important;
        box-shadow: 0 4px 15px rgba(188, 163, 116, 0.25) !important;
    }
    
    .date-tab-inactive {
        background: rgba(22, 22, 22, 0.9) !important;
        color: var(--clr-text-muted) !important;
    }
    .date-tab-inactive:hover {
        color: #ffffff !important;
        border-color: var(--clr-primary) !important;
        background: var(--clr-surface-3) !important;
    }

    /* Studio Badges */
    .badge-studio-type {
        display: inline-flex;
        padding: 3px 8px;
        border-radius: var(--radius-sm);
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-family: var(--font-body);
    }
    .badge-imax {
        background: #0072ce;
        color: #fff;
    }
    .badge-vip {
        background: var(--clr-primary);
        color: #000;
    }
    .badge-regular {
        background: var(--clr-surface-3);
        color: var(--clr-text-muted);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    /* Showtime Buttons */
    .time-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.6rem 1.5rem;
        background: rgba(22, 22, 22, 0.9);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: var(--radius);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 700;
        font-family: var(--font-heading);
        letter-spacing: 0.5px;
        transition: var(--transition);
    }
    .time-btn:hover {
        background: var(--clr-primary);
        color: #000000;
        border-color: var(--clr-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(188, 163, 116, 0.3);
    }

    /* Hide last border in studio loop */
    .studio-section:last-child {
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 0;
    }

    /* Play Trailer Button */
    .play-trailer-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        background: none;
        border: none;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    .play-trailer-btn:hover {
        transform: scale(1.08);
    }
    .play-trailer-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.7);
        background: rgba(188, 163, 116, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        backdrop-filter: blur(4px);
    }
    .play-trailer-btn:hover .play-trailer-circle {
        border-color: var(--clr-primary);
        background: rgba(188, 163, 116, 0.35);
        box-shadow: 0 0 30px rgba(188, 163, 116, 0.3);
    }
    .play-trailer-label {
        font-family: var(--font-heading);
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: rgba(255, 255, 255, 0.85);
    }
    .play-trailer-btn:hover .play-trailer-label {
        color: var(--clr-primary);
    }

    /* Trailer Modal */
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
        .play-trailer-btn {
            position: absolute;
            top: 50%;
            right: 2rem;
            transform: translateY(-50%);
        }
        .play-trailer-btn:hover {
            transform: translateY(-50%) scale(1.08);
        }
        .play-trailer-circle {
            width: 55px;
            height: 55px;
        }
        .play-trailer-circle i {
            font-size: 2rem !important;
        }
        .play-trailer-label {
            font-size: 0.65rem;
        }
        .trailer-modal-content {
            width: 95vw;
        }
    }

    /* Cast Section Styling */
    .cast-member {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 90px;
        text-align: center;
        gap: 0.6rem;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .cast-member:hover {
        transform: translateY(-4px);
    }
    .cast-avatar {
        width: 75px;
        height: 75px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        background: var(--clr-surface-3);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
    }
    .cast-avatar.cast-initials {
        background: #4b5563;
        font-weight: 700;
        color: #fff;
        font-size: 1.25rem;
        font-family: var(--font-heading);
    }
    .cast-member:hover .cast-avatar {
        border-color: var(--clr-primary) !important;
        box-shadow: 0 0 15px rgba(188, 163, 116, 0.35) !important;
    }
    .cast-name {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--clr-text-muted);
        line-height: 1.3;
        overflow-wrap: break-word;
        word-break: break-word;
        max-width: 100%;
        transition: color 0.25s ease;
    }
    .cast-member:hover .cast-name {
        color: #fff;
    }
</style>
@endpush
