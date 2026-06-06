@extends('layouts.app')
@section('title', 'Menunggu Pembayaran - Cinevora')

@section('content')
<section class="section" style="max-width: 480px; margin: 0 auto; padding-top: 1.5rem;">
    <div class="container">
        {{-- PENDING HEADER --}}
        <div class="text-center" style="margin-bottom: 2rem;">
            <div class="pending-pulse-ring">
                <div class="pending-icon-inner">
                    <i class='bx bx-time-five' style="font-size: 2rem; color: var(--clr-primary);"></i>
                </div>
            </div>
            <h1 class="font-heading" style="font-size: 1.5rem; font-weight: 800; text-transform: uppercase; color: #fff; letter-spacing: 1px; margin-top: 1.25rem;">Menunggu Pembayaran</h1>
            <p class="text-muted" style="font-size: 0.78rem; margin-top: 0.35rem; font-weight: 500;">Selesaikan pembayaran Anda untuk mendapatkan e-ticket</p>
        </div>

        {{-- TRANSACTION CARD --}}
        <div class="pending-card">
            {{-- Invoice + Total Header --}}
            <div class="pending-card-header">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <span class="pending-label">Invoice</span>
                        <p style="font-family: 'Courier New', monospace; font-size: 0.85rem; font-weight: 800; color: #fff; margin-top: 0.15rem;">{{ $transaction->invoice_number }}</p>
                    </div>
                    <div style="text-align: right;">
                        <span class="pending-label">Total Bayar</span>
                        <p class="font-heading" style="font-size: 1.4rem; font-weight: 800; color: var(--clr-primary); margin-top: 0.15rem;">
                            Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Movie Info --}}
            @php $firstTicket = $transaction->tickets->first(); @endphp
            @if($firstTicket)
            <div class="pending-movie-info">
                <h4 class="font-heading" style="font-weight: 800; font-size: 1.05rem; color: #fff; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px;">{{ $firstTicket->schedule->movie->title }}</h4>
                <p class="text-muted" style="font-size: 0.8rem; font-weight: 500; display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap;">
                    <span>{{ $firstTicket->schedule->studio->cinema->name }}</span>
                    <span style="color: var(--clr-primary);">·</span>
                    <span>{{ $firstTicket->schedule->show_date->format('d M Y') }}</span>
                    <span style="color: var(--clr-primary);">·</span>
                    <span>{{ $firstTicket->schedule->show_time_formatted }}</span>
                </p>
                <div style="display: flex; gap: 0.35rem; flex-wrap: wrap; margin-top: 0.65rem;">
                    @foreach($transaction->tickets as $ticket)
                        <span class="pending-seat-badge">{{ $ticket->seat->code }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Status & Actions --}}
            <div style="padding: 1.75rem 1.5rem; text-align: center;">
                <div id="statusMessage" style="margin-bottom: 1.25rem;">
                    <div class="pending-status-badge">
                        <span class="pending-dot"></span>
                        MENUNGGU PEMBAYARAN
                    </div>
                </div>

                <a href="{{ $transaction->xendit_invoice_url }}" target="_blank" class="btn btn-primary btn-block" style="padding: 0.9rem; border-radius: var(--radius); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem; margin-bottom: 0.65rem;">
                    <i class='bx bx-credit-card' style="font-size: 1.1rem;"></i> Lanjutkan Pembayaran
                </a>

                <form method="POST" action="{{ route('checkout.cancel', $transaction) }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-block btn-sm" style="color: var(--clr-error); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;" onclick="return confirm('Batalkan transaksi booking ini?')">
                        Batalkan Transaksi
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-xs text-muted mt-3" style="line-height: 1.7;">
            Halaman ini akan otomatis redirect setelah pembayaran berhasil.<br>
            Jangan tutup halaman ini.
        </p>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Poll payment status every 5 seconds
const checkInterval = setInterval(async () => {
    try {
        const res = await fetch(`/checkout/{{ $transaction->id }}/check-status`);
        const data = await res.json();
        if (data.status === 'paid') {
            clearInterval(checkInterval);
            document.getElementById('statusMessage').innerHTML = '<div class="pending-status-badge" style="background: rgba(16, 185, 129, 0.1); color: #10B981; border-color: rgba(16, 185, 129, 0.3);"><span class="pending-dot" style="background: #10B981;"></span>PEMBAYARAN BERHASIL</div>';
            setTimeout(() => {
                window.location.href = `/checkout/{{ $transaction->id }}/invoice`;
            }, 1500);
        } else if (data.status === 'cancelled') {
            clearInterval(checkInterval);
            window.location.href = '/';
        }
    } catch (e) { /* ignore */ }
}, 5000);
</script>
@endpush

@push('styles')
<style>
    /* Pulse Ring Animation */
    .pending-pulse-ring {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        position: relative;
    }
    .pending-pulse-ring::before,
    .pending-pulse-ring::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 2px solid var(--clr-primary);
        animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    .pending-pulse-ring::after {
        animation-delay: 1s;
    }
    .pending-icon-inner {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: rgba(188, 163, 116, 0.08);
        border: 1px solid rgba(188, 163, 116, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }
    @keyframes pulse-ring {
        0% { transform: scale(1); opacity: 0.6; }
        100% { transform: scale(1.6); opacity: 0; }
    }

    /* Card Styles */
    .pending-card {
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--clr-border);
        background: var(--clr-surface);
        box-shadow: 0 16px 50px rgba(0,0,0,0.6);
    }
    .pending-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--clr-border);
        background: var(--clr-surface-2);
    }
    .pending-label {
        font-size: 0.65rem;
        font-weight: 700;
        color: rgba(255,255,255,0.4);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .pending-movie-info {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--clr-border);
    }
    .pending-seat-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.55rem;
        background: var(--clr-surface-3);
        border: 1px solid var(--clr-border-dark);
        border-radius: var(--radius-sm);
        font-size: 0.7rem;
        font-weight: 700;
        color: #fff;
        font-family: var(--font-heading);
        letter-spacing: 0.5px;
    }

    /* Status Badge */
    .pending-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(188, 163, 116, 0.08);
        color: var(--clr-primary);
        font-weight: 800;
        padding: 0.55rem 1.25rem;
        font-size: 0.75rem;
        border: 1px solid rgba(188, 163, 116, 0.2);
        border-radius: var(--radius);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .pending-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--clr-primary);
        animation: blink 1.5s ease-in-out infinite;
    }
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }
</style>
@endpush
