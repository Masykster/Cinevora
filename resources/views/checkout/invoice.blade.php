@extends('layouts.app')
@section('title', 'E-Ticket - Cinevora')

@section('content')
<section class="section" style="max-width: 500px; margin: 0 auto;">
    <div class="container">
        <div class="text-center mb-3">
            <div style="font-size: 3.5rem; margin-bottom: 0.5rem; filter: drop-shadow(0 0 10px rgba(16, 185, 129, 0.4));">✅</div>
            <h1 class="font-heading" style="font-size: 1.8rem; font-weight: 800; text-transform: uppercase; color: #fff; letter-spacing: -0.5px;">Pembayaran Berhasil!</h1>
            <p class="text-muted text-xs">Simpan e-ticket di bawah ini untuk masuk bioskop</p>
        </div>

        {{-- E-TICKET CARD (this whole div will be captured as image) --}}
        @php $firstTicket = $transaction->tickets->first(); @endphp
        <div id="eTicketCard" style="overflow: hidden; border-radius: 12px; background: var(--clr-surface);">
            @if($firstTicket)
                {{-- TICKET HEADER - Movie Title --}}
                <div style="background: linear-gradient(135deg, #0a1628 0%, #1a2744 100%); padding: 1.5rem 1.5rem 1rem 1.5rem; text-align: center;">
                    <span style="font-family: var(--font-heading); font-size: 1rem; font-weight: 700; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.3rem;">cinevora</span>
                    <h2 class="font-heading" style="font-size: 1.5rem; font-weight: 800; color: var(--clr-primary); text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.2;">
                        {{ $firstTicket->schedule->movie->title }}
                    </h2>
                </div>

                {{-- TICKET BODY --}}
                <div style="background: linear-gradient(180deg, #1a2744 0%, #152036 100%); padding: 1.25rem 1.5rem;">
                    {{-- Movie Info Row: Poster + Details --}}
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        {{-- Poster --}}
                        <div style="width: 80px; flex-shrink: 0; border-radius: 6px; overflow: hidden; border: 2px solid rgba(255,255,255,0.1); box-shadow: 0 4px 12px rgba(0,0,0,0.4);">
                            <img src="{{ route('img.proxy', ['url' => $firstTicket->schedule->movie->poster_url]) }}" alt="{{ $firstTicket->schedule->movie->title }}" style="width: 100%; display: block;" crossorigin="anonymous">
                        </div>

                        {{-- Details --}}
                        <div style="flex: 1; display: grid; grid-template-columns: auto 1fr; gap: 0.4rem 0.75rem; font-size: 0.8rem;">
                            <span style="color: rgba(255,255,255,0.5); font-weight: 600;">Tanggal</span>
                            <span style="color: #fff; font-weight: 700;">{{ $firstTicket->schedule->show_date->translatedFormat('l, d M Y') }}</span>

                            <span style="color: rgba(255,255,255,0.5); font-weight: 600;">Bioskop</span>
                            <span style="color: #fff; font-weight: 700;">{{ $firstTicket->schedule->studio->cinema->name }} {{ $firstTicket->schedule->studio->name }}</span>

                            <span style="color: rgba(255,255,255,0.5); font-weight: 600;">Jam</span>
                            <span style="color: #fff; font-weight: 700;">{{ $firstTicket->schedule->show_time_formatted }}</span>

                            <span style="color: rgba(255,255,255,0.5); font-weight: 600;">Tiket</span>
                            <span style="color: var(--clr-primary); font-weight: 800;">{{ $transaction->tickets->pluck('seat.code')->implode(', ') }}</span>
                        </div>
                    </div>
                </div>
            @else
                {{-- CAFE ONLY HEADER --}}
                <div style="background: linear-gradient(135deg, #1c1308 0%, #2e1e0a 100%); padding: 1.5rem 1.5rem 1rem 1.5rem; text-align: center;">
                    <span style="font-family: var(--font-heading); font-size: 1rem; font-weight: 700; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.3rem;">cinevora</span>
                    <h2 class="font-heading" style="font-size: 1.5rem; font-weight: 800; color: var(--clr-primary); text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.2;">
                        PESANAN CAFE
                    </h2>
                </div>
                <div style="background: linear-gradient(180deg, #2e1e0a 0%, #150f05 100%); padding: 1.25rem 1.5rem; text-align: center;">
                    <span style="color: rgba(255,255,255,0.5); font-weight: 600; display: block; margin-bottom: 0.25rem; font-size: 0.8rem;">Lokasi Pengambilan</span>
                    <span style="color: #fff; font-weight: 700; font-size: 1.1rem;">{{ $transaction->cafeOrder->cinema->name ?? 'Bioskop Cinevora' }}</span>
                </div>
            @endif

            {{-- QR CODE SECTION --}}
            <div style="background: #ffffff; padding: 1.5rem; text-align: center;">
                <div id="qrcode" style="display: inline-block; margin: 0 auto;"></div>

                {{-- Booking Code --}}
                <div style="margin-top: 1rem;">
                    <span style="font-size: 0.75rem; color: #666; font-weight: 600; display: block;">Kode Booking</span>
                    <span style="font-family: var(--font-heading); font-size: 2.5rem; font-weight: 800; color: var(--clr-primary); letter-spacing: 3px; line-height: 1.2;">{{ $transaction->booking_code }}</span>
                </div>
            </div>

            {{-- INSTRUCTIONS --}}
            <div style="background: #f0f2f5; padding: 1.25rem 1.5rem; border-top: 2px dashed #d0d5dd;">
                <div style="display: flex; align-items: flex-start; gap: 0.6rem; margin-bottom: 0.75rem;">
                    <span style="font-size: 1.1rem;">❓</span>
                    <span style="font-weight: 800; color: #1a1a1a; font-size: 0.9rem;">Cara {{ $firstTicket ? 'mencetak tiket' : 'mengambil pesanan' }} Anda</span>
                </div>
                <ol style="margin: 0; padding-left: 1.25rem; font-size: 0.75rem; color: #444; line-height: 1.8; font-weight: 500;">
                    <li>Kunjungi {{ $firstTicket ? 'kios' : 'area cafe' }} CINEVORA yang ada di lokasi bioskop pilihan Anda</li>
                    <li>Tunjukkan kode QR atau masukkan kode booking</li>
                    <li>{{ $firstTicket ? 'Tiket akan tercetak otomatis dan pesanan makanan dapat diambil di area cafe' : 'Pesanan makanan/minuman Anda akan segera disiapkan' }}</li>
                </ol>
            </div>
        </div>

        {{-- PAYMENT SUMMARY (outside the screenshot card) --}}
        <div class="card" style="margin-top: 1.5rem; overflow: hidden; border: 1px solid var(--clr-border); background: var(--clr-surface);">
            <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--clr-border); background: var(--clr-surface-2);">
                <h3 class="font-heading" style="font-weight: 800; font-size: 0.9rem; color: #fff; text-transform: uppercase; letter-spacing: 0.5px;">Ringkasan Pembayaran</h3>
            </div>
            <div style="padding: 1.25rem 1.5rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span class="text-muted text-sm">Tiket ({{ $transaction->tickets->count() }}x)</span>
                    <span class="text-sm" style="color: #fff; font-weight: 700;">Rp {{ number_format($transaction->ticket_total, 0, ',', '.') }}</span>
                </div>
                @if($transaction->orderItems->count() > 0)
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span class="text-muted text-sm">Camilan ({{ $transaction->orderItems->sum('quantity') }} item)</span>
                    <span class="text-sm" style="color: #fff; font-weight: 700;">Rp {{ number_format($transaction->fnb_total, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($transaction->discount > 0)
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: #34d399; font-weight: 600; font-size: 0.85rem;">Diskon ({{ $transaction->voucher->code }})</span>
                    <span style="color: #34d399; font-weight: 700; font-size: 0.85rem;">-Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div style="border-top: 1px solid var(--clr-border); padding-top: 0.75rem; margin-top: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                    <span class="font-heading" style="font-weight: 800; color: #fff; text-transform: uppercase; font-size: 0.9rem;">Total</span>
                    <span class="font-heading" style="font-size: 1.3rem; font-weight: 800; color: var(--clr-primary);">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                </div>
                <div style="margin-top: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span class="badge" style="background: #10B981; color: #fff; font-weight: 800; font-size: 0.65rem; padding: 0.25rem 0.6rem;">✓ LUNAS</span>
                    @if($transaction->payment_method)
                    <span class="text-xs text-muted">via {{ $transaction->payment_method }}</span>
                    @endif
                </div>
            </div>
        </div>

        @if($transaction->orderItems->count() > 0 && $transaction->cafeOrder)
        <div class="card" style="margin-top: 1rem; overflow: hidden; border: 1px solid var(--clr-border); background: var(--clr-surface);">
            <div style="padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <span class="text-sm" style="font-weight: 700; color: #fff;">🍿 Status Pesanan F&B</span>
                <span class="badge" style="background: rgba(247, 148, 30, 0.15); color: var(--clr-primary); font-weight: 800; font-size: 0.65rem; padding: 0.25rem 0.6rem; border: 1px solid var(--clr-primary-dim);">{{ $transaction->cafeOrder->status_badge }}</span>
            </div>
        </div>
        @endif

        {{-- ACTION BUTTONS --}}
        <div style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem;">
            <button onclick="downloadTicket()" class="btn btn-primary btn-block" style="padding: 0.85rem; border-radius: 6px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; font-size: 0.85rem;">
                📥 Simpan Tiket sebagai Gambar
            </button>
            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('home') }}" class="btn btn-outline" style="flex: 1; border-radius: 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.75rem; padding: 0.7rem;">← Beranda</a>
                <a href="{{ route('profile.index') }}" class="btn btn-outline" style="flex: 1; border-radius: 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.75rem; padding: 0.7rem;">Riwayat →</a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .mb-3 { margin-bottom: 1.5rem; }
    .mb-5 { margin-bottom: 2.5rem; }
</style>
@endpush

@push('scripts')
{{-- QR Code library --}}
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
{{-- html2canvas for screenshot --}}
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
// Convert cross-origin images to base64 to avoid html2canvas CORS issues
function convertImagesToBase64() {
    const images = document.querySelectorAll('#eTicketCard img');
    const promises = Array.from(images).map(img => {
        return new Promise((resolve) => {
            if (img.src.startsWith('data:')) return resolve();
            const canvas = document.createElement('canvas');
            const newImg = new Image();
            newImg.crossOrigin = 'anonymous';
            newImg.onload = function() {
                canvas.width = newImg.naturalWidth;
                canvas.height = newImg.naturalHeight;
                canvas.getContext('2d').drawImage(newImg, 0, 0);
                try {
                    img.src = canvas.toDataURL('image/png');
                } catch(e) { /* CORS blocked, ignore */ }
                resolve();
            };
            newImg.onerror = () => resolve();
            // Use proxy to bypass CORS
            newImg.src = img.src;
        });
    });
    return Promise.all(promises);
}

// Generate QR Code
document.addEventListener('DOMContentLoaded', function() {
    const bookingCode = @json($transaction->booking_code);
    const qrData = JSON.stringify({
        code: bookingCode,
        invoice: @json($transaction->invoice_number),
        tickets: @json($transaction->tickets->pluck('seat.code')),
    });

    new QRCode(document.getElementById('qrcode'), {
        text: qrData,
        width: 180,
        height: 180,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M,
    });

    // Pre-convert images to base64 on load
    convertImagesToBase64();
});

// Download ticket as image
function downloadTicket() {
    const card = document.getElementById('eTicketCard');
    const btn = event.target;
    btn.textContent = '⏳ Menyimpan...';
    btn.disabled = true;

    convertImagesToBase64().then(() => {
        return html2canvas(card, {
            scale: 2,
            useCORS: true,
            allowTaint: true,
            backgroundColor: null,
            logging: false,
        });
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = 'Cinevora-Ticket-{{ $transaction->booking_code }}.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
        btn.textContent = '📥 Simpan Tiket sebagai Gambar';
        btn.disabled = false;
    }).catch(() => {
        btn.textContent = '📥 Simpan Tiket sebagai Gambar';
        btn.disabled = false;
        alert('Gagal menyimpan tiket. Coba screenshot manual.');
    });
}
</script>
@endpush
