@extends('layouts.app')
@section('title', 'Checkout - Cinevora')

@section('content')
<section class="section" style="max-width: 1000px; margin: 0 auto;">
    <div class="container">
        <h1 class="font-heading" style="font-size: 1.8rem; font-weight: 800; margin-bottom: 2rem; text-transform: uppercase; color: #fff; letter-spacing: -0.5px;">Checkout</h1>

        @if($transaction->remaining_seconds > 0)
        <div class="alert badge-yellow" style="margin-bottom: 2rem; border-radius: var(--radius); padding: 1.25rem; border: 1px solid var(--clr-primary); background: rgba(247, 148, 30, 0.15); box-shadow: 0 0 15px rgba(247, 148, 30, 0.1);">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; width: 100%;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <span style="font-size: 1.5rem;">⏳</span>
                    <div>
                        <strong style="display: block; color: #fff; font-size: 0.95rem;">Selesaikan pembayaran Anda segera!</strong>
                        <span class="text-sm" style="color: var(--clr-text-muted);">Kursi pesanan Anda akan dilepas dalam waktu:</span>
                    </div>
                </div>
                <div id="countdown" class="font-heading" style="font-size: 1.8rem; font-weight: 800; color: var(--clr-primary);">
                    --:--
                </div>
            </div>
        </div>
        @endif

        <div class="checkout-layout">
            {{-- LEFT: ORDER DETAILS --}}
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                {{-- TICKETS --}}
                @if($transaction->tickets->count() > 0)
                <div class="card" style="background: var(--clr-surface); border: 1px solid var(--clr-border);">
                    <div style="padding: 1.25rem; border-bottom: 1px solid var(--clr-border); background: var(--clr-surface-2);">
                        <h3 class="font-heading" style="font-weight: 800; font-size: 1.05rem; color: #fff; display: flex; align-items: center; gap: 0.5rem;">
                            <span>🎟️</span> TIKET BIOSKOP
                        </h3>
                    </div>
                    @php $firstTicket = $transaction->tickets->first(); @endphp
                    <div style="padding: 1.5rem;">
                        <div style="display: flex; gap: 1.25rem; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap;">
                            <div style="width: 55px; height: 80px; background: var(--clr-primary-dim); border-radius: var(--radius-sm); border: 1px solid var(--clr-primary); display: flex; align-items: center; justify-content: center; font-size: 2rem; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">🎬</div>
                            <div>
                                <h4 class="font-heading" style="font-weight: 800; font-size: 1.2rem; color: #fff; margin-bottom: 0.25rem; letter-spacing: -0.2px;">{{ $firstTicket->schedule->movie->title }}</h4>
                                <p class="text-muted text-xs" style="font-weight: 500;">{{ $firstTicket->schedule->studio->cinema->name }} · {{ $firstTicket->schedule->studio->name }}</p>
                                <p style="font-size: 0.8rem; color: #fff; font-weight: 600; margin-top: 0.25rem;">
                                    📅 {{ $firstTicket->schedule->show_date->format('l, d M Y') }} · 🕐 {{ $firstTicket->schedule->show_time_formatted }}
                                </p>
                            </div>
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; border-top: 1px solid var(--clr-border); padding-top: 1rem;">
                            @foreach($transaction->tickets as $ticket)
                                <span class="badge" style="background: var(--clr-surface-3); border: 1px solid var(--clr-border-dark); color: #fff; padding: 0.4rem 0.8rem; font-weight: 700; font-size: 0.75rem;">
                                    {{ $ticket->seat->code }} · Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- F&B ITEMS --}}
                <div class="card" style="background: var(--clr-surface); border: 1px solid var(--clr-border);">
                    <div style="padding: 1.25rem; border-bottom: 1px solid var(--clr-border); background: var(--clr-surface-2);">
                        <h3 class="font-heading" style="font-weight: 800; font-size: 1.05rem; color: #fff; display: flex; align-items: center; gap: 0.5rem;">
                            <span>🍿</span> SNACK & MINUMAN (F&B)
                        </h3>
                    </div>

                    @if($transaction->orderItems->count() > 0)
                        <div style="display: flex; flex-direction: column;">
                            @foreach($transaction->orderItems as $item)
                                <div style="padding: 1.25rem; border-bottom: 1px solid var(--clr-border); display: flex; align-items: center; justify-content: space-between;">
                                    <div>
                                        <span style="font-weight: 700; color: #fff;">{{ $item->product->name }}</span>
                                        <span class="badge" style="background: var(--clr-primary-dim); color: var(--clr-primary); font-size: 0.65rem; font-weight: 800; margin-left: 0.5rem;">x{{ $item->quantity }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <span style="font-weight: 800; color: var(--clr-primary);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                        <form method="POST" action="{{ route('checkout.removeFnb', [$transaction, $item]) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-sm" style="color: var(--clr-error); padding: 0.25rem 0.5rem; font-size: 0.9rem;" title="Hapus item">✕</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="padding: 2.5rem; text-align: center; color: var(--clr-text-muted);">
                            <span style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;">🍿</span>
                            <p class="text-sm">Belum ada camilan yang dipesan. Nonton jadi sepi tanpa popcorn!</p>
                        </div>
                    @endif

                    {{-- ADD F&B --}}
                    <div style="padding: 1.25rem; background: var(--clr-surface-2); border-top: 1px solid var(--clr-border);">
                        <button onclick="document.getElementById('fnbModal').style.display='flex'" class="btn btn-outline btn-sm btn-block" style="border-radius: 4px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                            + Tambah Makanan/Minuman
                        </button>
                    </div>
                </div>

                {{-- VOUCHER --}}
                <div class="card" style="background: var(--clr-surface); border: 1px solid var(--clr-border);">
                    <div style="padding: 1.25rem;">
                        <h3 class="font-heading" style="font-weight: 800; font-size: 1.05rem; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                            <span>🎫</span> KODE VOUCHER
                        </h3>

                        @if($transaction->voucher)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.85rem; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 4px;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span class="badge" style="background: #10B981; color: #fff; font-weight: 800; padding: 0.3rem 0.6rem;">{{ $transaction->voucher->code }}</span>
                                    <span style="color: #34d399; font-weight: 700; font-size: 0.9rem;">Hemat Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                                </div>
                                <form method="POST" action="{{ route('checkout.removeVoucher', $transaction) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-sm" style="color: var(--clr-error); font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">Hapus</button>
                                </form>
                            </div>
                        @else
                            <form method="POST" action="{{ route('checkout.applyVoucher', $transaction) }}" style="display: flex; gap: 0.5rem;">
                                @csrf
                                <input type="text" name="voucher_code" class="form-input" placeholder="Masukkan kode voucher..." style="flex: 1; text-transform: uppercase; font-weight: 700; border-radius: 4px; height: 42px;">
                                <button type="submit" class="btn btn-outline" style="border-radius: 4px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; height: 42px;">Gunakan</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT: SUMMARY --}}
            <div class="card summary-card">
                <div style="padding: 1.25rem; border-bottom: 1px solid var(--clr-border); background: var(--clr-surface-2);">
                    <h3 class="font-heading" style="font-weight: 800; font-size: 1.05rem; color: #fff;">RINGKASAN BIAYA</h3>
                </div>
                <div style="padding: 1.5rem;">
                    <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                        @if($transaction->tickets->count() > 0)
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="text-muted text-sm" style="font-weight: 500;">Tiket Bioskop ({{ $transaction->tickets->count() }}x)</span>
                            <span style="color: #fff; font-weight: 700;">Rp {{ number_format($transaction->ticket_total, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($transaction->orderItems->count() > 0)
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="text-muted text-sm" style="font-weight: 500;">Camilan ({{ $transaction->orderItems->sum('quantity') }} item)</span>
                            <span style="color: #fff; font-weight: 700;">Rp {{ number_format($transaction->fnb_total, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        
                        <div style="border-top: 1px solid var(--clr-border); margin: 0.5rem 0; padding-top: 0.85rem; display: flex; flex-direction: column; gap: 0.75rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="text-muted text-sm" style="font-weight: 500;">Subtotal</span>
                                <span style="color: #fff; font-weight: 700;">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                            </div>
                            @if($transaction->discount > 0)
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #34d399; font-weight: 600; font-size: 0.9rem;">Diskon Voucher</span>
                                <span style="color: #34d399; font-weight: 700; font-size: 0.9rem;">-Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div style="border-top: 1px solid var(--clr-border); padding-top: 1.25rem; margin-top: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="font-heading" style="font-weight: 800; color: #fff; font-size: 1.1rem;">TOTAL BAYAR</span>
                            <span class="font-heading" style="font-size: 1.6rem; font-weight: 800; color: var(--clr-primary);">
                                Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('checkout.pay', $transaction) }}" style="margin-top: 1.5rem;">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-block" style="padding: 0.9rem; border-radius: 4px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; font-size: 0.9rem;" onclick="return confirm('Konfirmasi pembayaran?')">
                            💳 BAYAR SEKARANG
                        </button>
                    </form>

                    <form method="POST" action="{{ route('checkout.cancel', $transaction) }}" style="margin-top: 0.75rem;">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-block btn-sm" style="color: var(--clr-error); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;" onclick="return confirm('Batalkan transaksi booking ini?')">
                            Batalkan Transaksi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- F&B MODAL --}}
<div id="fnbModal" style="display: none; position: fixed; inset: 0; z-index: 200; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="card" style="width: 100%; max-width: 800px; max-height: 90vh; overflow: hidden; background: var(--clr-surface); border: 1px solid var(--clr-border); box-shadow: 0 10px 40px rgba(0,0,0,0.8); display: flex; flex-direction: column; border-radius: 12px;">
        <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--clr-border); display: flex; justify-content: space-between; align-items: center; background: var(--clr-surface-2); z-index: 10;">
            <h3 class="font-heading" style="font-weight: 800; font-size: 1.3rem; color: #fff; text-transform: uppercase; letter-spacing: 0.5px;">🍿 Tambah Snack & Minuman</h3>
            <button onclick="document.getElementById('fnbModal').style.display='none'" class="btn btn-ghost btn-sm" style="color: var(--clr-text-muted); font-size: 1.5rem; padding: 0.25rem 0.5rem; line-height: 1;">✕</button>
        </div>

        <form method="POST" action="{{ route('checkout.addFnb', $transaction) }}" id="fnbForm" style="flex: 1; display: flex; flex-direction: column; overflow: hidden;">
            @csrf
            <div style="padding: 1.5rem 2rem; overflow-y: auto; flex: 1; background: var(--clr-bg);">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
                    @foreach($products as $product)
                        <div class="card cafe-card" style="display: flex; flex-direction: column; overflow: hidden; border: 1px solid var(--clr-border); background: var(--clr-surface); box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: transform 0.2s, border-color 0.2s;">
                            <div style="height: 120px; background: var(--clr-surface-2); display: flex; align-items: center; justify-content: center; font-size: 3rem; border-bottom: 1px solid var(--clr-border); position: relative;">
                                <div style="position: absolute; width: 60px; height: 60px; border-radius: 50%; background: var(--clr-primary-dim); filter: blur(15px); z-index: 1;"></div>
                                <span style="position: relative; z-index: 2;">{{ $product->category->icon ?? '🍿' }}</span>
                            </div>
                            <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <h3 style="font-family: var(--font-heading); font-weight: 800; font-size: 1rem; color: #fff; margin-bottom: 0.35rem;">{{ $product->name }}</h3>
                                    <p class="text-muted text-xs" style="line-height: 1.4; margin-bottom: 1rem;">{{ $product->description }}</p>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--clr-border); padding-top: 0.75rem;">
                                    <span style="font-family: var(--font-heading); font-weight: 800; color: var(--clr-primary); font-size: 1.05rem;">
                                        {{ $product->formatted_price }}
                                    </span>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <button type="button" class="qty-btn" onclick="updateModalQty({{ $loop->index }}, -1)" style="width: 24px; height: 24px; border-radius: 4px; background: var(--clr-surface-3); border: 1px solid var(--clr-border); color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; font-weight: 800; transition: 0.2s;">-</button>
                                        <span id="qty-display-{{ $loop->index }}" style="font-weight: 800; width: 20px; text-align: center; color: #fff; font-size: 0.9rem;">0</span>
                                        <button type="button" class="qty-btn" onclick="updateModalQty({{ $loop->index }}, 1)" style="width: 24px; height: 24px; border-radius: 4px; background: var(--clr-surface-3); border: 1px solid var(--clr-border); color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; font-weight: 800; transition: 0.2s;">+</button>
                                        
                                        <input type="hidden" name="items[{{ $loop->index }}][quantity]" id="qty-input-{{ $loop->index }}" value="0" data-price="{{ $product->price }}" disabled>
                                        <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $product->id }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div style="padding: 1.5rem 2rem; border-top: 1px solid var(--clr-border); background: var(--clr-surface-2); z-index: 10;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div>
                        <span class="text-muted text-xs" style="display: block; font-weight: 600; text-transform: uppercase;">Total Transaksi Saat Ini</span>
                        <span style="color: #fff; font-weight: 700; font-size: 0.9rem;" id="modalCurrentTotal" data-value="{{ $transaction->grand_total }}">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-muted text-xs" style="display: block; font-weight: 600; text-transform: uppercase; text-align: right;">Tambahan F&B</span>
                        <span style="color: var(--clr-primary); font-weight: 800; font-size: 1.1rem; text-align: right;" id="modalFnbTotal">Rp 0</span>
                    </div>
                </div>
                <button type="submit" id="fnbSubmitBtn" class="btn btn-primary btn-block" style="border-radius: 6px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; font-size: 0.95rem; padding: 1rem;" disabled onclick="enableSelectedItems()">Tambahkan ke Pesanan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateModalQty(index, delta) {
    const input = document.getElementById('qty-input-' + index);
    const display = document.getElementById('qty-display-' + index);
    
    let current = parseInt(input.value) || 0;
    current += delta;
    
    if (current < 0) current = 0;
    if (current > 10) current = 10;
    
    input.value = current;
    display.textContent = current;
    
    calculateModalTotal();
}

function calculateModalTotal() {
    let fnbTotal = 0;
    document.querySelectorAll('#fnbForm input[name$="[quantity]"]').forEach(input => {
        let qty = parseInt(input.value) || 0;
        let price = parseInt(input.getAttribute('data-price')) || 0;
        fnbTotal += qty * price;
    });

    const modalFnbTotal = document.getElementById('modalFnbTotal');
    const submitBtn = document.getElementById('fnbSubmitBtn');
    const currentTotal = parseInt(document.getElementById('modalCurrentTotal').getAttribute('data-value')) || 0;

    modalFnbTotal.textContent = 'Rp ' + fnbTotal.toLocaleString('id-ID');

    if (fnbTotal > 0) {
        submitBtn.disabled = false;
        submitBtn.textContent = `Tambahkan ke Pesanan (Total: Rp ${(currentTotal + fnbTotal).toLocaleString('id-ID')})`;
    } else {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Tambahkan ke Pesanan';
    }
}

function enableSelectedItems() {
    // Enable hidden inputs before submit so they get included in POST
    document.querySelectorAll('#fnbForm input[name$="[quantity]"]').forEach(input => {
        if (parseInt(input.value) > 0) {
            input.disabled = false;
            // Also enable the product_id input next to it
            input.parentElement.querySelector('input[name$="[product_id]"]').disabled = false;
        }
    });
}

// Close modal on backdrop click
document.getElementById('fnbModal').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});

@if($transaction->remaining_seconds > 0)
let timeLeft = {{ $transaction->remaining_seconds }};
const timerDisplay = document.getElementById('countdown');

const timer = setInterval(() => {
    timeLeft--;
    
    if (timeLeft <= 0) {
        clearInterval(timer);
        timerDisplay.innerHTML = "Kedaluwarsa";
        alert("Waktu pembayaran telah habis. Transaksi dibatalkan.");
        window.location.reload();
        return;
    }
    
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    
    timerDisplay.innerHTML = 
        String(minutes).padStart(2, '0') + ':' + 
        String(seconds).padStart(2, '0');
        
}, 1000);
@endif
</script>
@endpush

@push('styles')
<style>
    .checkout-layout {
        display: grid; 
        grid-template-columns: 1fr 350px; 
        gap: 2rem; 
        align-items: start;
    }

    .summary-card {
        position: sticky; 
        top: 90px;
        background: var(--clr-surface); 
        border: 1px solid var(--clr-border);
        box-shadow: 0 4px 20px rgba(0,0,0,0.5);
    }

    @media (max-width: 768px) {
        .checkout-layout {
            grid-template-columns: 1fr !important;
            gap: 1.5rem;
        }
        .summary-card {
            position: relative;
            top: 0;
        }
    }
</style>
@endpush
