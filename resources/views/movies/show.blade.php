@extends('layouts.app')
@section('title', $movie->title)
@section('meta_description', Str::limit($movie->synopsis, 160))

@section('content')
{{-- MOVIE HEADER --}}
<section style="position: relative; padding: 3rem 0; overflow: hidden;">
    <div style="position: absolute; inset: 0; background: radial-gradient(ellipse at 30% 50%, rgba(124, 58, 237, 0.1), transparent 60%);"></div>

    <div class="container" style="position: relative;">
        <div style="display: flex; gap: 2.5rem; flex-wrap: wrap;">
            {{-- POSTER --}}
            <div style="width: 280px; flex-shrink: 0;">
                <div style="aspect-ratio: 2/3; background: linear-gradient(135deg, var(--clr-surface-2), var(--clr-surface-3)); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; font-size: 5rem; border: 1px solid var(--clr-border);">
                    🎬
                </div>
            </div>

            {{-- INFO --}}
            <div style="flex: 1; min-width: 300px;">
                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
                    <span class="badge badge-{{ $movie->status === 'now_playing' ? 'success' : 'primary' }}">
                        {{ $movie->status === 'now_playing' ? 'Sedang Tayang' : 'Coming Soon' }}
                    </span>
                    <span class="badge badge-primary">{{ $movie->age_rating }}</span>
                </div>

                <h1 class="font-heading" style="font-size: 2.5rem; font-weight: 800; line-height: 1.1; letter-spacing: -0.5px;">
                    {{ $movie->title }}
                </h1>

                <div style="display: flex; gap: 1.5rem; margin-top: 1rem; flex-wrap: wrap; color: var(--clr-text-muted); font-size: 0.9rem;">
                    <span>⭐ <strong style="color: var(--clr-accent); font-size: 1.1rem;">{{ number_format($movie->rating, 1) }}</strong>/10</span>
                    <span>⏱ {{ $movie->duration_formatted }}</span>
                    <span>📅 {{ $movie->release_date->format('d M Y') }}</span>
                </div>

                <div style="margin-top: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach(explode(', ', $movie->genre) as $genre)
                        <span style="padding: 0.3rem 0.8rem; border-radius: var(--radius-full); background: var(--clr-surface-2); border: 1px solid var(--clr-border); font-size: 0.8rem; color: var(--clr-text-muted);">
                            {{ $genre }}
                        </span>
                    @endforeach
                </div>

                <div style="margin-top: 1.5rem;">
                    <h3 style="font-weight: 600; margin-bottom: 0.5rem;">Sinopsis</h3>
                    <p class="text-muted" style="line-height: 1.7;">{{ $movie->synopsis }}</p>
                </div>

                <div style="margin-top: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; max-width: 400px;">
                    <div>
                        <span class="text-muted text-xs" style="display: block;">Sutradara</span>
                        <span style="font-weight: 600;">{{ $movie->director }}</span>
                    </div>
                    @if($movie->cast)
                    <div>
                        <span class="text-muted text-xs" style="display: block;">Pemeran</span>
                        <span style="font-weight: 600; font-size: 0.85rem;">{{ $movie->cast }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SCHEDULES --}}
@if($movie->status === 'now_playing' && $cinemas->count() > 0)
<section class="section" style="border-top: 1px solid var(--clr-border);">
    <div class="container">
        <h2 class="font-heading" style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Jadwal & Tiket</h2>
        <p class="text-muted text-sm mb-3">Pilih bioskop dan jadwal untuk membeli tiket</p>

        {{-- DATE TABS --}}
        @if($dates->count() > 0)
        <div style="display: flex; gap: 0.5rem; margin-bottom: 2rem; overflow-x: auto; padding-bottom: 0.5rem;" id="dateTabs">
            @foreach($dates as $i => $date)
                <button onclick="showDate('{{ $date->format('Y-m-d') }}')"
                    class="btn {{ $i === 0 ? 'btn-primary' : 'btn-outline' }} btn-sm date-tab"
                    data-date="{{ $date->format('Y-m-d') }}">
                    <span style="display: block; font-size: 0.7rem; opacity: 0.8;">{{ $date->format('D') }}</span>
                    {{ $date->format('d M') }}
                </button>
            @endforeach
        </div>
        @endif

        {{-- CINEMA SCHEDULES --}}
        @foreach($cinemas as $cinema)
            <div class="card" style="margin-bottom: 1.5rem;">
                <div style="padding: 1.25rem; border-bottom: 1px solid var(--clr-border); display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h3 class="font-heading" style="font-weight: 700;">{{ $cinema->name }}</h3>
                        <p class="text-muted text-xs">{{ $cinema->city }} · {{ $cinema->address }}</p>
                    </div>
                </div>

                @foreach($cinema->studios as $studio)
                    <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--clr-border);">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                            <span style="font-weight: 600; font-size: 0.9rem;">{{ $studio->name }}</span>
                            <span class="badge badge-{{ $studio->type === 'imax' ? 'accent' : ($studio->type === 'vip' ? 'primary' : 'gray') }}">
                                {{ $studio->type_label }}
                            </span>
                        </div>

                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            @foreach($studio->schedules as $schedule)
                                <div class="schedule-slot" data-date="{{ $schedule->show_date->format('Y-m-d') }}" style="{{ $schedule->show_date->format('Y-m-d') !== $dates->first()->format('Y-m-d') ? 'display:none;' : '' }}">
                                    @auth
                                        <a href="{{ route('booking.seats', $schedule) }}"
                                            class="btn btn-outline btn-sm"
                                            style="flex-direction: column; padding: 0.5rem 1rem; min-width: 80px;">
                                            <span style="font-weight: 700;">{{ $schedule->show_time_formatted }}</span>
                                            <span style="font-size: 0.65rem; color: var(--clr-accent);">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}"
                                            class="btn btn-outline btn-sm"
                                            style="flex-direction: column; padding: 0.5rem 1rem; min-width: 80px;">
                                            <span style="font-weight: 700;">{{ $schedule->show_time_formatted }}</span>
                                            <span style="font-size: 0.65rem; color: var(--clr-accent);">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                                        </a>
                                    @endauth
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</section>
@elseif($movie->status === 'coming_soon')
<section class="section" style="border-top: 1px solid var(--clr-border);">
    <div class="container text-center" style="padding: 3rem 0;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">🎭</div>
        <h3 class="font-heading" style="font-weight: 700;">Coming Soon</h3>
        <p class="text-muted mt-1">Film ini akan tayang pada {{ $movie->release_date->format('d M Y') }}</p>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
function showDate(date) {
    // Update tab styles
    document.querySelectorAll('.date-tab').forEach(tab => {
        tab.className = tab.dataset.date === date ? 'btn btn-primary btn-sm date-tab' : 'btn btn-outline btn-sm date-tab';
    });

    // Show/hide schedule slots
    document.querySelectorAll('.schedule-slot').forEach(slot => {
        slot.style.display = slot.dataset.date === date ? '' : 'none';
    });
}
</script>
@endpush
