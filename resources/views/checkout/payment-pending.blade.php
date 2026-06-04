@extends('layouts.app')
@section('title', 'Menunggu Pembayaran - Cinevora')

@section('content')
<section class="section" style="max-width: 600px; margin: 0 auto;">
    <div class="container">
        <div class="text-center mb-3">
            <div class="payment-spinner"></div>
            <h1 class="font-heading" style="font-size: 1.8rem; font-weight: 800; text-transform: uppercase; color: #fff; letter-spacing: -0.5px; margin-top: 1.5rem;">Menunggu Pembayaran</h1>
            <p class="text-muted text-sm" style="margin-top: 0.5rem;">Selesaikan pembayaran Anda di halaman Xendit</p>
        </div>

        <div class="card" style="overflow: hidden; border: 1px solid var(--clr-border); background: var(--clr-surface);">
            {{-- Transaction Info --}}
            <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--clr-border); background: var(--clr-surface-2);">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <div>
                        <span class="text-xs text-muted" style="text-transform: uppercase; font-weight: 700;">Invoice</span>
                        <p style="font-family: monospace; font-size: 0.9rem; font-weight: 800; color: #fff;">{{ $transaction->invoice_number }}</p>
                    </div>
                    <div style="text-align: right;">
                        <span class="text-xs text-muted" style="text-transform: uppercase; font-weight: 700;">Total</span>
                        <p class="font-heading" style="font-size: 1.3rem; font-weight: 800; color: var(--clr-primary);">
                            Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            @php $firstTicket = $transaction->tickets->first(); @endphp
            @if($firstTicket)
            <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--clr-border);">
                <h4 class="font-heading" style="font-weight: 800; font-size: 1.1rem; color: #fff; margin-bottom: 0.5rem;">{{ $firstTicket->schedule->movie->title }}</h4>
                <p class="text-muted text-sm">{{ $firstTicket->schedule->studio->cinema->name }} · {{ $firstTicket->schedule->show_date->format('d M Y') }} · {{ $firstTicket->schedule->show_time_formatted }}</p>
                <div style="display: flex; gap: 0.4rem; flex-wrap: wrap; margin-top: 0.75rem;">
                    @foreach($transaction->tickets as $ticket)
                        <span class="badge" style="background: var(--clr-surface-3); border: 1px solid var(--clr-border-dark); color: #fff; padding: 0.3rem 0.6rem; font-weight: 700; font-size: 0.7rem;">{{ $ticket->seat->code }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <div style="padding: 2rem; text-align: center;">
                <div id="statusMessage" style="margin-bottom: 1.5rem;">
                    <div class="badge" style="background: rgba(247, 148, 30, 0.15); color: var(--clr-primary); font-weight: 800; padding: 0.5rem 1rem; font-size: 0.8rem; border: 1px solid var(--clr-primary);">
                        ⏳ MENUNGGU PEMBAYARAN
                    </div>
                </div>

                <a href="{{ $transaction->xendit_invoice_url }}" target="_blank" class="btn btn-primary btn-block" style="padding: 0.9rem; border-radius: 4px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; font-size: 0.85rem; margin-bottom: 0.75rem;">
                    💳 Lanjutkan Pembayaran
                </a>

                <form method="POST" action="{{ route('checkout.cancel', $transaction) }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-block btn-sm" style="color: var(--clr-error); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;" onclick="return confirm('Batalkan transaksi booking ini?')">
                        Batalkan Transaksi
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-xs text-muted mt-3" style="line-height: 1.6;">
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
            document.getElementById('statusMessage').innerHTML = '<div class="badge" style="background: rgba(16, 185, 129, 0.15); color: #10B981; font-weight: 800; padding: 0.5rem 1rem; font-size: 0.8rem; border: 1px solid #10B981;">✓ PEMBAYARAN BERHASIL</div>';
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
    .payment-spinner {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: 4px solid var(--clr-border);
        border-top-color: var(--clr-primary);
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endpush
