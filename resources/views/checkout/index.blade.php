@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<section class="section">
    <div class="container" style="max-width: 1000px;">
        <h1 class="font-heading" style="font-size: 1.8rem; font-weight: 800; margin-bottom: 2rem;">Checkout</h1>

        <div style="display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: start;">
            {{-- LEFT: ORDER DETAILS --}}
            <div>
                {{-- TICKETS --}}
                @if($transaction->tickets->count() > 0)
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div style="padding: 1.25rem; border-bottom: 1px solid var(--clr-border);">
                        <h3 class="font-heading" style="font-weight: 700;">🎟️ Tiket Bioskop</h3>
                    </div>
                    @php $firstTicket = $transaction->tickets->first(); @endphp
                    <div style="padding: 1.25rem;">
                        <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
                            <div style="width: 50px; height: 75px; background: linear-gradient(135deg, var(--clr-surface-2), var(--clr-surface-3)); border-radius: var(--radius); display: flex; align-items: center; justify-content: center;">🎬</div>
                            <div>
                                <h4 style="font-weight: 700;">{{ $firstTicket->schedule->movie->title }}</h4>
                                <p class="text-muted text-xs">{{ $firstTicket->schedule->studio->cinema->name }} · {{ $firstTicket->schedule->studio->name }}</p>
                                <p class="text-muted text-xs">{{ $firstTicket->schedule->show_date->format('l, d M Y') }} · {{ $firstTicket->schedule->show_time_formatted }}</p>
                            </div>
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                            @foreach($transaction->tickets as $ticket)
                                <span class="badge badge-primary">{{ $ticket->seat->code }} · Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- F&B ITEMS --}}
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div style="padding: 1.25rem; border-bottom: 1px solid var(--clr-border); display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="font-heading" style="font-weight: 700;">🍿 Pesanan F&B</h3>
                    </div>

                    @if($transaction->orderItems->count() > 0)
                        @foreach($transaction->orderItems as $item)
                            <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--clr-border); display: flex; align-items: center; justify-content: space-between;">
                                <div>
                                    <span style="font-weight: 600;">{{ $item->product->name }}</span>
                                    <span class="text-muted text-xs" style="margin-left: 0.5rem;">x{{ $item->quantity }}</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <span class="text-accent font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    <form method="POST" action="{{ route('checkout.removeFnb', [$transaction, $item]) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm" style="color: var(--clr-error); padding: 0.2rem 0.4rem;">✕</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div style="padding: 2rem; text-align: center;">
                            <p class="text-muted">Belum ada pesanan F&B</p>
                        </div>
                    @endif

                    {{-- ADD F&B --}}
                    <div style="padding: 1.25rem;">
                        <button onclick="document.getElementById('fnbModal').style.display='flex'" class="btn btn-outline btn-sm btn-block">
                            + Tambah Makanan/Minuman
                        </button>
                    </div>
                </div>

                {{-- VOUCHER --}}
                <div class="card">
                    <div style="padding: 1.25rem;">
                        <h3 class="font-heading" style="font-weight: 700; margin-bottom: 1rem;">🎫 Voucher</h3>

                        @if($transaction->voucher)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: var(--radius);">
                                <div>
                                    <span class="badge badge-success">{{ $transaction->voucher->code }}</span>
                                    <span class="text-success text-sm" style="margin-left: 0.5rem;">-Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                                </div>
                                <form method="POST" action="{{ route('checkout.removeVoucher', $transaction) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-sm" style="color: var(--clr-error);">Hapus</button>
                                </form>
                            </div>
                        @else
                            <form method="POST" action="{{ route('checkout.applyVoucher', $transaction) }}" style="display: flex; gap: 0.5rem;">
                                @csrf
                                <input type="text" name="voucher_code" class="form-input" placeholder="Masukkan kode voucher" style="flex: 1; text-transform: uppercase;">
                                <button type="submit" class="btn btn-outline">Gunakan</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT: SUMMARY --}}
            <div class="card" style="position: sticky; top: 90px;">
                <div style="padding: 1.5rem; border-bottom: 1px solid var(--clr-border);">
                    <h3 class="font-heading" style="font-weight: 700;">Ringkasan</h3>
                </div>
                <div style="padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                        <span class="text-muted text-sm">Tiket ({{ $transaction->tickets->count() }}x)</span>
                        <span class="text-sm">Rp {{ number_format($transaction->ticket_total, 0, ',', '.') }}</span>
                    </div>
                    @if($transaction->orderItems->count() > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                        <span class="text-muted text-sm">F&B ({{ $transaction->orderItems->sum('quantity') }} item)</span>
                        <span class="text-sm">Rp {{ number_format($transaction->fnb_total, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <div style="border-top: 1px solid var(--clr-border); margin: 1rem 0; padding-top: 0.75rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span class="text-muted text-sm">Subtotal</span>
                            <span class="text-sm">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                        </div>
                        @if($transaction->discount > 0)
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span class="text-success text-sm">Diskon</span>
                            <span class="text-success text-sm">-Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>

                    <div style="border-top: 1px solid var(--clr-border); padding-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="font-heading" style="font-weight: 700;">Total</span>
                            <span class="font-heading" style="font-size: 1.5rem; font-weight: 800; color: var(--clr-accent);">
                                Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('checkout.pay', $transaction) }}">
                        @csrf
                        <button type="submit" class="btn btn-accent btn-block btn-lg mt-3" onclick="return confirm('Konfirmasi pembayaran?')">
                            💳 Bayar Sekarang
                        </button>
                    </form>

                    <form method="POST" action="{{ route('checkout.cancel', $transaction) }}" style="margin-top: 0.75rem;">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-block btn-sm" style="color: var(--clr-error);" onclick="return confirm('Batalkan transaksi ini?')">
                            Batalkan Transaksi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- F&B MODAL --}}
<div id="fnbModal" style="display: none; position: fixed; inset: 0; z-index: 200; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 2rem;">
    <div class="card" style="width: 100%; max-width: 600px; max-height: 80vh; overflow-y: auto;">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--clr-border); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: var(--clr-surface); z-index: 1;">
            <h3 class="font-heading" style="font-weight: 700;">Tambah F&B</h3>
            <button onclick="document.getElementById('fnbModal').style.display='none'" class="btn btn-ghost btn-sm">✕</button>
        </div>

        <form method="POST" action="{{ route('checkout.addFnb', $transaction) }}" id="fnbForm">
            @csrf
            <div style="padding: 1rem;">
                @foreach($products as $product)
                    <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem; border-bottom: 1px solid var(--clr-border);">
                        <div style="flex: 1;">
                            <span style="font-weight: 600; font-size: 0.9rem;">{{ $product->name }}</span>
                            <span class="badge badge-gray" style="margin-left: 0.25rem;">{{ $product->category->name }}</span>
                            <p class="text-muted text-xs">{{ $product->description }}</p>
                            <p class="text-accent text-sm font-semibold">{{ $product->formatted_price }}</p>
                        </div>
                        <div style="width: 80px;">
                            <select name="items[{{ $loop->index }}][quantity]" class="form-select" style="padding: 0.4rem; font-size: 0.8rem;" onchange="this.closest('div').parentElement.querySelector('input[type=hidden]').disabled = (this.value == 0)">
                                <option value="0">0</option>
                                @for($i = 1; $i <= 5; $i++)<option value="{{ $i }}">{{ $i }}</option>@endfor
                            </select>
                            <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $product->id }}" disabled>
                        </div>
                    </div>
                @endforeach
            </div>
            <div style="padding: 1rem; border-top: 1px solid var(--clr-border); position: sticky; bottom: 0; background: var(--clr-surface);">
                <button type="submit" class="btn btn-primary btn-block" onclick="enableSelectedItems()">Tambahkan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function enableSelectedItems() {
    document.querySelectorAll('#fnbForm select').forEach(select => {
        const hidden = select.closest('div').parentElement.querySelector('input[type=hidden]');
        if (parseInt(select.value) > 0) {
            hidden.disabled = false;
        }
    });
}

// Close modal on backdrop click
document.getElementById('fnbModal').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});
</script>
@endpush

@push('styles')
<style>
    @media (max-width: 768px) {
        .section .container > div { grid-template-columns: 1fr !important; }
    }
</style>
@endpush
