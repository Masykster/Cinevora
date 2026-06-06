@extends('layouts.app')
@section('title', 'Daftar Bioskop - Cinevora')

@section('content')
<div class="container section" style="max-width: 1024px;">
    <div style="margin-bottom: 3rem; border-bottom: 2px solid var(--clr-border); padding-bottom: 0.75rem;">
        <h1 class="font-heading" style="font-size: 2.5rem; font-weight: 700; text-transform: uppercase; color: #fff; letter-spacing: 0.5px;">Daftar Bioskop</h1>
        <p class="text-muted text-xs" style="text-transform: uppercase; letter-spacing: 0.8px; font-weight: 600; margin-top: 0.25rem;">
            @if($selectedCity ?? false)
                Menampilkan bioskop di <span style="color: var(--clr-primary);">{{ $selectedCity }}</span>
            @else
                Temukan lokasi bioskop Cinevora terdekat di kota Anda
            @endif
        </p>
    </div>

    <div class="cinema-grid">
        @forelse($cinemas as $cinema)
            <div class="card cinema-card" style="padding: 2rem; background: var(--clr-surface-2); border: 1px solid rgba(255,255,255,0.06); display: flex; flex-direction: column; justify-content: space-between; border-radius: var(--radius); box-shadow: 0 8px 24px rgba(0,0,0,0.6);">
                <div>
                    <h2 class="font-heading" style="font-size: 1.4rem; font-weight: 700; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <i class='bx bxs-buildings' style="color: var(--clr-primary);"></i> {{ $cinema->name }}
                    </h2>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 2rem;">
                        <p style="font-size: 0.85rem; color: var(--clr-text-muted); display: flex; align-items: flex-start; gap: 0.6rem; line-height: 1.5; font-weight: 500;">
                            <i class='bx bx-map' style="color: var(--clr-primary); font-size: 1.15rem; margin-top: 0.1rem; flex-shrink: 0;"></i> 
                            <span>{{ $cinema->address }}, {{ $cinema->city }}</span>
                        </p>
                        <p style="font-size: 0.85rem; color: var(--clr-text-muted); display: flex; align-items: center; gap: 0.6rem; font-weight: 500;">
                            <i class='bx bx-phone' style="color: var(--clr-primary); font-size: 1.15rem; flex-shrink: 0;"></i> 
                            <span>{{ $cinema->phone ?? '-' }}</span>
                        </p>
                    </div>
                </div>
                
                <a href="{{ route('movies.index') }}" class="btn btn-outline btn-block" style="font-weight: 700; font-size: 0.8rem; padding: 0.6rem 1.25rem;">
                    Lihat Jadwal Film
                </a>
            </div>
        @empty
            <div style="grid-column: 1 / -1; padding: 5rem 2rem; text-align: center; border: 1px dashed var(--clr-border); border-radius: var(--radius); background: var(--clr-surface-2);">
                <i class='bx bx-building' style="font-size: 4rem; color: var(--clr-primary); margin-bottom: 1.5rem; display: block;"></i>
                <h3 class="font-heading font-bold" style="color: #fff; text-transform: uppercase; letter-spacing: 0.5px; font-size: 1.4rem;">Belum Ada Bioskop</h3>
                <p class="text-muted text-sm mt-2" style="font-weight: 500;">Kami akan segera hadir di kota Anda!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
    .cinema-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.75rem;
    }
    
    .cinema-card {
        transition: var(--transition);
    }
    .cinema-card:hover {
        transform: translateY(-4px);
        border-color: var(--clr-primary) !important;
        box-shadow: 0 12px 30px rgba(188, 163, 116, 0.15) !important;
    }
</style>
@endpush
