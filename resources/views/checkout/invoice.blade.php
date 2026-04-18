@extends('layouts.app')
@section('title', 'Invoice')

@section('content')
<section class="section">
    <div class="container" style="max-width: 700px;">
        <div class="text-center mb-4">
            <div style="font-size: 4rem; margin-bottom: 0.5rem;">✅</div>
            <h1 class="font-heading" style="font-size: 2rem; font-weight: 800;">Pembayaran Berhasil!</h1>
            <p class="text-muted">Simpan halaman ini sebagai bukti transaksi Anda</p>
        </div>

        <div class="card" style="overflow: hidden;">
            {{-- INVOICE HEADER --}}
            <div style="padding: 2rem; background: linear-gradient(135deg, rgba(124, 58, 237, 0.15), rgba(245, 158, 11, 0.1)); border-bottom: 1px solid var(--clr-border);">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <span class="font-heading" style="font-size: 1.2rem; font-weight: 800; background: linear-gradient(135deg, var(--clr-primary-light), var(--clr-accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">CINEVORA</span>
                        <p class="text-muted text-xs mt-1">Invoice</p>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-family: monospace; font-size: 0.85rem; font-weight: 700;">{{ $transaction->invoice_number }}</p>
                        <p class="text-muted text-xs">{{ $transaction->paid_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- CUSTOMER INFO --}}
            <div style="padding: 1.25rem 2rem; border-bottom: 1px solid var(--clr-border); background: var(--clr-surface-2);">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <span class="text-muted text-xs">Customer</span>
                        <p style="font-weight: 600;">{{ $transaction->user->name }}</p>
                    </div>
                    <div>
                        <span class="text-muted text-xs">Email</span>
                        <p style="font-weight: 600; font-size: 0.9rem;">{{ $transaction->user->email }}</p>
                    </div>
                </div>
            </div>

            {{-- TICKETS --}}
            @if($transaction->tickets->count() > 0)
            @php $firstTicket = $transaction->tickets->first(); @endphp
            <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--clr-border);">
                <h3 style="font-weight: 700; margin-bottom: 1rem; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">🎟️ E-TICKET</h3>

                <div style="background: var(--clr-surface-2); border-radius: var(--radius); padding: 1.25rem; border: 1px dashed var(--clr-border);">
                    <h4 style="font-family: var(--font-heading); font-weight: 700; font-size: 1.1rem;">{{ $firstTicket->schedule->movie->title }}</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-top: 0.75rem;">
                        <div><span class="text-muted text-xs">Bioskop</span><p class="text-sm font-semibold">{{ $firstTicket->schedule->studio->cinema->name }}</p></div>
                        <div><span class="text-muted text-xs">Studio</span><p class="text-sm font-semibold">{{ $firstTicket->schedule->studio->name }} ({{ $firstTicket->schedule->studio->type_label }})</p></div>
                        <div><span class="text-muted text-xs">Tanggal</span><p class="text-sm font-semibold">{{ $firstTicket->schedule->show_date->format('l, d M Y') }}</p></div>
                        <div><span class="text-muted text-xs">Jam</span><p class="text-sm font-semibold">{{ $firstTicket->schedule->show_time_formatted }}</p></div>
                    </div>

                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed var(--clr-border);">
                        <span class="text-muted text-xs">Kursi</span>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.3rem;">
                            @foreach($transaction->tickets as $ticket)
                                <span class="badge badge-primary" style="font-size: 0.8rem; padding: 0.3rem 0.8rem;">
                                    {{ $ticket->seat->code }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- BARCODE SIMULATION --}}
                    <div style="margin-top: 1.25rem; text-align: center;">
                        <div style="display: inline-block; padding: 1rem; background: #fff; border-radius: var(--radius);">
                            <div style="display: flex; gap: 2px; align-items: flex-end; height: 50px;">
                                @for($i = 0; $i < 40; $i++)
                                    <div style="width: {{ rand(1,3) }}px; height: {{ rand(25,50) }}px; background: #000;"></div>
                                @endfor
                            </div>
                            <p style="font-family: monospace; font-size: 0.65rem; margin-top: 0.3rem; color: #333;">{{ $transaction->tickets->first()->barcode }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- F&B ORDER --}}
            @if($transaction->orderItems->count() > 0)
            <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--clr-border);">
                <h3 style="font-weight: 700; margin-bottom: 1rem; font-size: 0.9rem;">🍿 PESANAN F&B</h3>
                @foreach($transaction->orderItems as $item)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span class="text-sm">{{ $item->product->name }} <span class="text-muted">x{{ $item->quantity }}</span></span>
                        <span class="text-sm">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach

                @if($transaction->cafeOrder)
                    <div style="margin-top: 1rem; padding: 0.75rem; background: rgba(245, 158, 11, 0.1); border-radius: var(--radius); border: 1px solid rgba(245, 158, 11, 0.2);">
                        <span class="text-sm text-accent">📋 Status pesanan kafe: <strong>{{ $transaction->cafeOrder->status_badge }}</strong></span>
                    </div>
                @endif
            </div>
            @endif

            {{-- PAYMENT SUMMARY --}}
            <div style="padding: 1.5rem 2rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span class="text-muted text-sm">Subtotal</span>
                    <span class="text-sm">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                </div>
                @if($transaction->discount > 0)
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span class="text-success text-sm">Diskon ({{ $transaction->voucher->code }})</span>
                    <span class="text-success text-sm">-Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div style="border-top: 1px solid var(--clr-border); padding-top: 0.75rem; margin-top: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                    <span class="font-heading font-bold">Total Bayar</span>
                    <span class="font-heading" style="font-size: 1.4rem; font-weight: 800; color: var(--clr-accent);">
                        Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                    </span>
                </div>
                <div style="margin-top: 0.5rem;">
                    <span class="badge badge-success">✓ LUNAS</span>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('home') }}" class="btn btn-primary">← Kembali ke Home</a>
            <a href="{{ route('profile.index') }}" class="btn btn-outline" style="margin-left: 0.5rem;">Riwayat Transaksi</a>
        </div>
    </div>
</section>
@endsection
