@extends('layouts.app')
@section('title', 'E-Ticket - Cinevora')

@section('content')
<section class="section" style="max-width: 480px; margin: 0 auto; padding-top: 1.5rem;">
    <div class="container">
        {{-- SUCCESS HEADER --}}
        <div class="text-center" style="margin-bottom: 2rem;">
            <div class="success-checkmark">
                <svg viewBox="0 0 52 52" style="width: 56px; height: 56px;">
                    <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
            </div>
            <h1 class="font-heading" style="font-size: 1.5rem; font-weight: 800; text-transform: uppercase; color: #fff; letter-spacing: 1px; margin-top: 1rem;">Pembayaran Berhasil</h1>
            <p class="text-muted" style="font-size: 0.78rem; margin-top: 0.35rem; font-weight: 500;">Simpan e-ticket ini untuk masuk bioskop</p>
        </div>

        {{-- ═══════════════════════════════════════ --}}
        {{-- E-TICKET CARD (captured as image)      --}}
        {{-- ═══════════════════════════════════════ --}}
        @php $firstTicket = $transaction->tickets->first(); @endphp
        <div id="eTicketCard" class="eticket-card">

            {{-- TICKET TOP: BRAND + MOVIE INFO --}}
            @if($firstTicket)
                <div class="eticket-header">
                    {{-- Brand Row --}}
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                        <span style="font-family: var(--font-heading); font-size: 1.15rem; font-weight: 700; color: #fff; text-transform: lowercase; letter-spacing: 0.5px;">cine<span style="color: var(--clr-primary);">vora</span></span>
                        <span style="font-size: 0.6rem; font-weight: 700; color: rgba(255,255,255,0.35); text-transform: uppercase; letter-spacing: 2px;">E-TICKET</span>
                    </div>

                    {{-- Movie Info: Poster + Details --}}
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        {{-- Poster --}}
                        <div class="eticket-poster">
                            <img src="{{ route('img.proxy', ['url' => $firstTicket->schedule->movie->poster_url]) }}" alt="{{ $firstTicket->schedule->movie->title }}" style="width: 100%; height: 100%; object-fit: cover; display: block;" crossorigin="anonymous">
                        </div>

                        {{-- Details --}}
                        <div style="flex: 1; min-width: 0;">
                            <h2 class="font-heading" style="font-size: 1.2rem; font-weight: 800; color: var(--clr-primary); text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.2; margin-bottom: 0.85rem;">
                                {{ $firstTicket->schedule->movie->title }}
                            </h2>

                            <div class="eticket-detail-grid">
                                <div class="eticket-detail-item">
                                    <i class='bx bx-calendar'></i>
                                    <div>
                                        <span class="eticket-label">Tanggal</span>
                                        <span class="eticket-value">{{ $firstTicket->schedule->show_date->translatedFormat('l, d M Y') }}</span>
                                    </div>
                                </div>
                                <div class="eticket-detail-item">
                                    <i class='bx bx-time-five'></i>
                                    <div>
                                        <span class="eticket-label">Jam</span>
                                        <span class="eticket-value">{{ $firstTicket->schedule->show_time_formatted }}</span>
                                    </div>
                                </div>
                                <div class="eticket-detail-item">
                                    <i class='bx bx-buildings'></i>
                                    <div>
                                        <span class="eticket-label">Bioskop</span>
                                        <span class="eticket-value">{{ $firstTicket->schedule->studio->cinema->name }}</span>
                                    </div>
                                </div>
                                <div class="eticket-detail-item">
                                    <i class='bx bx-tv'></i>
                                    <div>
                                        <span class="eticket-label">Studio</span>
                                        <span class="eticket-value">{{ $firstTicket->schedule->studio->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Seat Badges --}}
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.06);">
                        <span class="eticket-label" style="display: block; margin-bottom: 0.5rem;">Kursi</span>
                        <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                            @foreach($transaction->tickets as $ticket)
                                <span class="seat-badge">{{ $ticket->seat->code }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

            @else
                {{-- CAFE ONLY HEADER --}}
                <div class="eticket-header" style="background: linear-gradient(160deg, #1c1308 0%, #0d0a04 100%);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="font-family: var(--font-heading); font-size: 1.15rem; font-weight: 700; color: #fff; text-transform: lowercase; letter-spacing: 0.5px;">cine<span style="color: var(--clr-primary);">vora</span></span>
                        <span style="font-size: 0.6rem; font-weight: 700; color: rgba(255,255,255,0.35); text-transform: uppercase; letter-spacing: 2px;">CAFE ORDER</span>
                    </div>
                    <h2 class="font-heading" style="font-size: 1.5rem; font-weight: 800; color: var(--clr-primary); text-transform: uppercase; text-align: center; padding: 1rem 0;">
                        PESANAN CAFE
                    </h2>
                    <p class="text-center" style="color: rgba(255,255,255,0.5); font-size: 0.8rem; font-weight: 600;">
                        {{ $transaction->cafeOrder->cinema->name ?? 'Bioskop Cinevora' }}
                    </p>
                </div>
            @endif

            {{-- TEAR LINE (perforation effect) --}}
            <div class="eticket-tear">
                <div class="tear-circle tear-left"></div>
                <div class="tear-line"></div>
                <div class="tear-circle tear-right"></div>
            </div>

            {{-- QR CODE SECTION --}}
            <div class="eticket-qr-section">
                <div id="qrcode" style="display: inline-block; margin: 0 auto;"></div>

                {{-- Booking Code --}}
                <div style="margin-top: 1rem;">
                    <span style="font-size: 0.7rem; color: #888; font-weight: 600; display: block; text-transform: uppercase; letter-spacing: 1px;">Kode Booking</span>
                    <span class="font-heading" style="font-size: 2.2rem; font-weight: 800; color: #111; letter-spacing: 4px; line-height: 1.3;">{{ $transaction->booking_code }}</span>
                </div>
            </div>

            {{-- INSTRUCTIONS --}}
            <div class="eticket-instructions">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.65rem;">
                    <i class='bx bx-info-circle' style="font-size: 1.1rem; color: var(--clr-primary);"></i>
                    <span style="font-weight: 800; color: #333; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Cara {{ $firstTicket ? 'mencetak tiket' : 'mengambil pesanan' }}</span>
                </div>
                <ol style="margin: 0; padding-left: 1.2rem; font-size: 0.72rem; color: #555; line-height: 1.9; font-weight: 500;">
                    <li>Kunjungi {{ $firstTicket ? 'kios' : 'area cafe' }} CINEVORA di bioskop</li>
                    <li>Tunjukkan kode QR atau masukkan kode booking</li>
                    <li>{{ $firstTicket ? 'Tiket tercetak otomatis & pesanan F&B siap diambil' : 'Pesanan akan segera disiapkan' }}</li>
                </ol>
            </div>
        </div>

        {{-- ═══════════════════════════════════════ --}}
        {{-- PAYMENT SUMMARY                        --}}
        {{-- ═══════════════════════════════════════ --}}
        <div class="invoice-summary-card">
            <div class="invoice-summary-header">
                <h3 class="font-heading" style="font-weight: 800; font-size: 0.85rem; color: #fff; text-transform: uppercase; letter-spacing: 1px;">Ringkasan Pembayaran</h3>
            </div>
            <div style="padding: 1.25rem 1.5rem;">
                @if($transaction->tickets->count() > 0)
                <div class="invoice-row">
                    <span class="text-muted text-sm">Tiket ({{ $transaction->tickets->count() }}x)</span>
                    <span class="text-sm" style="color: #fff; font-weight: 700;">Rp {{ number_format($transaction->ticket_total, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($transaction->orderItems->count() > 0)
                <div class="invoice-row">
                    <span class="text-muted text-sm">Camilan ({{ $transaction->orderItems->sum('quantity') }} item)</span>
                    <span class="text-sm" style="color: #fff; font-weight: 700;">Rp {{ number_format($transaction->fnb_total, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($transaction->discount > 0)
                <div class="invoice-row">
                    <span style="color: #34d399; font-weight: 600; font-size: 0.85rem;">Diskon ({{ $transaction->voucher->code }})</span>
                    <span style="color: #34d399; font-weight: 700; font-size: 0.85rem;">-Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="invoice-total-row">
                    <span class="font-heading" style="font-weight: 800; color: #fff; text-transform: uppercase; font-size: 0.9rem;">Total</span>
                    <span class="font-heading" style="font-size: 1.3rem; font-weight: 800; color: var(--clr-primary);">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                </div>
                <div style="margin-top: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span class="paid-badge">✓ LUNAS</span>
                    @if($transaction->payment_method)
                    <span class="text-xs text-muted">via {{ $transaction->payment_method }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- F&B STATUS --}}
        @if($transaction->orderItems->count() > 0 && $transaction->cafeOrder)
        <div class="invoice-summary-card" style="margin-top: 0.75rem;">
            <div style="padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <span class="text-sm" style="font-weight: 700; color: #fff; display: flex; align-items: center; gap: 0.4rem;">
                    <i class='bx bx-coffee-togo' style="color: var(--clr-primary); font-size: 1.1rem;"></i> Status Pesanan F&B
                </span>
                <span class="badge" style="background: rgba(188, 163, 116, 0.12); color: var(--clr-primary); font-weight: 800; font-size: 0.65rem; padding: 0.25rem 0.6rem; border: 1px solid rgba(188, 163, 116, 0.25);">{{ $transaction->cafeOrder->status_badge }}</span>
            </div>
        </div>
        @endif

        {{-- ACTION BUTTONS --}}
        <div style="margin-top: 1.5rem; display: flex; flex-direction: column; gap: 0.65rem; padding-bottom: 1rem;">
            <button onclick="downloadTicket()" class="btn btn-primary btn-block" id="downloadBtn" style="padding: 0.85rem; border-radius: var(--radius); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem;">
                <i class='bx bx-download' style="font-size: 1.1rem;"></i> Simpan E-Ticket
            </button>
            <div style="display: flex; gap: 0.65rem;">
                <a href="{{ route('home') }}" class="btn btn-outline" style="flex: 1; border-radius: var(--radius); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.72rem; padding: 0.7rem;">
                    <i class='bx bx-home'></i> Beranda
                </a>
                <a href="{{ route('profile.index') }}" class="btn btn-outline" style="flex: 1; border-radius: var(--radius); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.72rem; padding: 0.7rem;">
                    <i class='bx bx-receipt'></i> Riwayat
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* ══════════════════════════════════════
       E-TICKET CARD STYLES
       ══════════════════════════════════════ */
    .eticket-card {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,255,255,0.05);
    }

    .eticket-header {
        background: linear-gradient(160deg, #0f1923 0%, #0a1018 50%, #080d14 100%);
        padding: 1.5rem;
    }

    .eticket-poster {
        width: 85px;
        flex-shrink: 0;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid rgba(188, 163, 116, 0.2);
        box-shadow: 0 6px 20px rgba(0,0,0,0.6);
        aspect-ratio: 2/3;
    }

    .eticket-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.65rem;
    }

    .eticket-detail-item {
        display: flex;
        align-items: flex-start;
        gap: 0.4rem;
    }

    .eticket-detail-item i {
        color: var(--clr-primary);
        font-size: 0.9rem;
        margin-top: 0.1rem;
        flex-shrink: 0;
        opacity: 0.7;
    }

    .eticket-label {
        display: block;
        font-size: 0.6rem;
        font-weight: 700;
        color: rgba(255,255,255,0.35);
        text-transform: uppercase;
        letter-spacing: 1px;
        line-height: 1;
        margin-bottom: 0.15rem;
    }

    .eticket-value {
        font-size: 0.75rem;
        font-weight: 700;
        color: #fff;
        line-height: 1.3;
    }

    .seat-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.3rem 0.75rem;
        background: rgba(188, 163, 116, 0.1);
        border: 1px solid rgba(188, 163, 116, 0.3);
        border-radius: 6px;
        font-family: var(--font-heading);
        font-size: 0.85rem;
        font-weight: 800;
        color: var(--clr-primary);
        letter-spacing: 1px;
    }

    /* Tear/Perforation Effect */
    .eticket-tear {
        position: relative;
        height: 24px;
        background: #f7f8fa;
        display: flex;
        align-items: center;
    }

    .tear-circle {
        position: absolute;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--clr-bg);
    }
    .tear-left { left: -12px; }
    .tear-right { right: -12px; }

    .tear-line {
        flex: 1;
        margin: 0 16px;
        border-top: 2px dashed #d0d5dd;
    }

    /* QR Section */
    .eticket-qr-section {
        background: #ffffff;
        padding: 1.75rem 1.5rem;
        text-align: center;
    }

    /* Instructions */
    .eticket-instructions {
        background: #f0f2f5;
        padding: 1rem 1.5rem;
        border-top: 1px solid #e4e7ec;
    }

    /* ══════════════════════════════════════
       PAYMENT SUMMARY CARD
       ══════════════════════════════════════ */
    .invoice-summary-card {
        margin-top: 1.5rem;
        overflow: hidden;
        border-radius: var(--radius-lg);
        border: 1px solid var(--clr-border);
        background: var(--clr-surface);
        box-shadow: 0 8px 30px rgba(0,0,0,0.5);
    }

    .invoice-summary-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--clr-border);
        background: var(--clr-surface-2);
    }

    .invoice-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .invoice-total-row {
        border-top: 1px solid var(--clr-border);
        padding-top: 0.75rem;
        margin-top: 0.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .paid-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(16, 185, 129, 0.12);
        color: #10B981;
        font-weight: 800;
        font-size: 0.65rem;
        padding: 0.25rem 0.6rem;
        border-radius: var(--radius-sm);
        border: 1px solid rgba(16, 185, 129, 0.2);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ══════════════════════════════════════
       SUCCESS CHECKMARK ANIMATION
       ══════════════════════════════════════ */
    .success-checkmark svg {
        display: block;
        margin: 0 auto;
    }
    .checkmark-circle {
        stroke: var(--clr-primary);
        stroke-width: 2;
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        animation: stroke-draw 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    .checkmark-check {
        stroke: var(--clr-primary);
        stroke-width: 3;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke-draw 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.5s forwards;
    }
    @keyframes stroke-draw {
        100% { stroke-dashoffset: 0; }
    }

    /* Mobile fine-tuning */
    @media (max-width: 400px) {
        .eticket-detail-grid {
            grid-template-columns: 1fr;
        }
        .eticket-poster {
            width: 70px;
        }
        .eticket-header {
            padding: 1.25rem;
        }
    }
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
        width: 160,
        height: 160,
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
    const btn = document.getElementById('downloadBtn');
    btn.innerHTML = '<i class="bx bx-loader-alt bx-spin" style="font-size:1.1rem;"></i> Menyimpan...';
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
        btn.innerHTML = '<i class="bx bx-download" style="font-size:1.1rem;"></i> Simpan E-Ticket';
        btn.disabled = false;
    }).catch(() => {
        btn.innerHTML = '<i class="bx bx-download" style="font-size:1.1rem;"></i> Simpan E-Ticket';
        btn.disabled = false;
        alert('Gagal menyimpan tiket. Coba screenshot manual.');
    });
}
</script>
@endpush
