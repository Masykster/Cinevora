@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
{{-- STAT CARDS --}}
<div class="grid grid-4 gap-3 mb-3">
    <div class="card stat-card">
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value text-accent">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="card stat-card">
        <div class="stat-label">Pendapatan Tiket</div>
        <div class="stat-value" style="color: var(--clr-primary-light);">Rp {{ number_format($ticketRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="card stat-card">
        <div class="stat-label">Pendapatan F&B</div>
        <div class="stat-value" style="color: #34d399;">Rp {{ number_format($fnbRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="card stat-card">
        <div class="stat-label">Total Transaksi</div>
        <div class="stat-value">{{ $totalTransactions }}</div>
        <div class="stat-change text-muted">👥 {{ $userCount }} users terdaftar</div>
    </div>
</div>

<div class="grid grid-2 gap-3">
    {{-- BEST SELLING MOVIES --}}
    <div class="card">
        <div style="padding: 1.25rem; border-bottom: 1px solid var(--clr-border);">
            <h3 style="font-family: var(--font-heading); font-weight: 700;">🏆 Film Terlaris</h3>
        </div>
        <div style="padding: 0.5rem;">
            @forelse($bestMovies as $i => $movie)
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem;">
                    <span style="width: 24px; height: 24px; border-radius: 50%; background: {{ $i === 0 ? 'var(--clr-accent)' : 'var(--clr-surface-3)' }}; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; color: {{ $i === 0 ? '#1a1a2e' : 'var(--clr-text-muted)' }};">{{ $i + 1 }}</span>
                    <div style="flex: 1;">
                        <span style="font-weight: 600; font-size: 0.9rem;">{{ $movie->title }}</span>
                        <p class="text-muted text-xs">{{ $movie->genre }}</p>
                    </div>
                    <span class="badge badge-primary">{{ $movie->tickets_sold ?? 0 }} tiket</span>
                </div>
            @empty
                <p class="text-muted text-center" style="padding: 2rem;">Belum ada data</p>
            @endforelse
        </div>
    </div>

    {{-- RECENT TRANSACTIONS --}}
    <div class="card">
        <div style="padding: 1.25rem; border-bottom: 1px solid var(--clr-border);">
            <h3 style="font-family: var(--font-heading); font-weight: 700;">📋 Transaksi Terbaru</h3>
        </div>
        <div style="padding: 0.5rem;">
            @forelse($recentTransactions as $tx)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; border-bottom: 1px solid var(--clr-border);">
                    <div>
                        <span style="font-weight: 600; font-size: 0.85rem;">{{ $tx->user->name }}</span>
                        <p class="text-muted text-xs">{{ $tx->invoice_number }} · {{ $tx->paid_at?->diffForHumans() }}</p>
                    </div>
                    <span class="text-accent font-semibold text-sm">Rp {{ number_format($tx->grand_total, 0, ',', '.') }}</span>
                </div>
            @empty
                <p class="text-muted text-center" style="padding: 2rem;">Belum ada transaksi</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
