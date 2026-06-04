@extends('layouts.app')
@section('title', 'Pilih Kursi - Cinevora')
@section('meta_description', 'Pilih kursi untuk menonton ' . $schedule->movie->title)

@section('content')
<section class="section" style="max-width: 900px; margin: 0 auto;">
    <div class="container">
        {{-- HEADER INFO --}}
        <div class="card" style="padding: 1.5rem; margin-bottom: 2.5rem; display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap; background: var(--clr-surface); border: 1px solid var(--clr-border); box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
            <div style="width: 60px; height: 90px; background: var(--clr-surface-2); border: 1px solid var(--clr-border); border-radius: var(--radius); display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">🎬</div>
            <div style="flex: 1; min-width: 250px;">
                <h1 class="font-heading" style="font-size: 1.3rem; font-weight: 800; color: #fff; letter-spacing: -0.2px; margin-bottom: 0.25rem;">{{ $schedule->movie->title }}</h1>
                <p class="text-muted text-sm" style="font-weight: 500;">{{ $schedule->studio->cinema->name }} · {{ $schedule->studio->name }} ({{ $schedule->studio->type_label }})</p>
                <div style="display: flex; gap: 1rem; margin-top: 0.75rem; flex-wrap: wrap; align-items: center;">
                    <span class="text-sm" style="color: #fff; font-weight: 600;">📅 {{ $schedule->show_date->format('l, d M Y') }}</span>
                    <span style="width: 4px; height: 4px; background: var(--clr-border-dark); border-radius: 50%;"></span>
                    <span class="text-sm" style="color: #fff; font-weight: 600;">🕐 {{ $schedule->show_time_formatted }}</span>
                    <span style="width: 4px; height: 4px; background: var(--clr-border-dark); border-radius: 50%;"></span>
                    <span class="text-sm font-bold" style="color: var(--clr-primary);">Rp {{ number_format($schedule->price, 0, ',', '.') }} / Kursi</span>
                </div>
            </div>
        </div>

        {{-- SCREEN --}}
        <div class="text-center mb-5">
            <div style="max-width: 600px; margin: 0 auto; position: relative;">
                <div class="cinema-screen"></div>
                <p class="text-muted text-xs" style="letter-spacing: 2px; font-weight: 800; text-transform: uppercase;">Layar Utama Bioskop</p>
            </div>
        </div>

        {{-- SEAT MAP --}}
        <form method="POST" action="{{ route('booking.process', $schedule) }}" id="seatForm">
            @csrf

            <div style="overflow-x: auto; padding: 1.5rem 0; background: #050507; border-radius: var(--radius); border: 1px solid var(--clr-border); margin-bottom: 2rem; box-shadow: inset 0 0 20px rgba(0,0,0,0.8);">
                <div style="display: flex; flex-direction: column; gap: 0.5rem; align-items: center; min-width: fit-content; padding: 0 1.5rem;">
                    @foreach($seatsByRow as $rowLabel => $seats)
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span class="row-label">{{ $rowLabel }}</span>

                            @foreach($seats as $seat)
                                @php
                                    $isBooked = in_array($seat->id, $bookedSeatIds);
                                @endphp
                                <label style="cursor: {{ $isBooked ? 'not-allowed' : 'pointer' }}; margin: 0;">
                                    <input type="checkbox" name="seat_ids[]" value="{{ $seat->id }}"
                                        {{ $isBooked ? 'disabled' : '' }}
                                        style="display: none;"
                                        onchange="updateSeatUI(this)">
                                    <div class="seat {{ $isBooked ? 'seat-booked' : 'seat-available' }}"
                                         id="seat-{{ $seat->id }}"
                                         title="{{ $seat->code }}"
                                         data-code="{{ $seat->code }}"
                                         data-price="{{ $schedule->price }}">
                                         <span style="font-size: 0.6rem;">{{ substr($seat->code, 1) }}</span>
                                    </div>
                                </label>
                            @endforeach

                            <span class="row-label">{{ $rowLabel }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- LEGEND --}}
            <div style="display: flex; gap: 2rem; justify-content: center; margin: 1.5rem 0 2.5rem; flex-wrap: wrap; background: var(--clr-surface); padding: 1rem; border-radius: var(--radius); border: 1px solid var(--clr-border);">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div class="seat seat-available" style="cursor: default;"></div>
                    <span class="text-muted text-xs font-semibold">Tersedia</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div class="seat seat-selected" style="cursor: default;"></div>
                    <span class="text-muted text-xs font-semibold">Dipilih</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div class="seat seat-booked" style="cursor: default;"></div>
                    <span class="text-muted text-xs font-semibold">Terisi</span>
                </div>
            </div>

            {{-- SUMMARY --}}
            <div class="card" style="padding: 1.5rem; background: var(--clr-surface); border: 1px solid var(--clr-border); box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <p class="text-muted text-sm" style="font-weight: 500;">Kursi dipilih: <span id="selectedCount" style="color: #fff; font-weight: 800; font-size: 1.1rem; margin-left: 0.25rem;">0</span></p>
                        <p class="text-xs" id="selectedSeats" style="color: var(--clr-primary); font-weight: 700; margin-top: 0.15rem; min-height: 16px;">Belum ada kursi dipilih</p>
                    </div>
                    <div style="text-align: right;">
                        <p class="text-muted text-sm" style="font-weight: 500;">Total Harga</p>
                        <p class="font-heading" style="font-size: 1.6rem; font-weight: 800; color: var(--clr-primary);" id="totalPrice">Rp 0</p>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg mt-4" id="submitBtn" style="border-radius: 4px; padding: 0.85rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; font-size: 0.9rem;" disabled>
                    Lanjut ke Checkout <i class='bx bx-right-arrow-alt' style="font-size: 1.3rem; vertical-align: middle;"></i>
                </button>
            </div>
        </form>

        @if($errors->any())
            <div class="alert alert-error mt-3">
                <span>✕</span> <span>{{ $errors->first() }}</span>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Curved glowing cinema screen */
    .cinema-screen {
        height: 6px; 
        background: linear-gradient(90deg, transparent, var(--clr-primary), transparent); 
        border-radius: 50%; 
        margin-bottom: 0.75rem;
        box-shadow: 0 4px 15px rgba(247, 148, 30, 0.4);
    }

    .row-label {
        width: 24px; 
        text-align: center; 
        font-size: 0.75rem; 
        font-weight: 800; 
        color: var(--clr-primary);
    }

    .seat {
        width: 32px; 
        height: 32px; 
        border-radius: 4px;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); 
        display: flex; 
        align-items: center; 
        justify-content: center;
        font-weight: 700;
        user-select: none;
    }
    .seat-available {
        background: var(--clr-surface-3); 
        border: 1px solid var(--clr-border-dark);
        color: var(--clr-text-muted);
    }
    .seat-available:hover {
        background: var(--clr-primary-dim); 
        border-color: var(--clr-primary);
        color: #fff;
        transform: scale(1.1);
    }
    .seat-selected {
        background: var(--clr-primary) !important; 
        border-color: var(--clr-primary-light) !important;
        color: #000000 !important; 
        transform: scale(1.1);
        box-shadow: 0 0 12px rgba(247, 148, 30, 0.5);
    }
    .seat-booked {
        background: #141416; 
        border: 1px solid var(--clr-border);
        color: #2e2e33;
        opacity: 0.45;
        cursor: not-allowed;
    }
    .seat-booked span {
        text-decoration: line-through;
    }
</style>
@endpush

@push('scripts')
<script>
const pricePerSeat = {{ $schedule->price }};

function updateSeatUI(input) {
    const seatDiv = input.nextElementSibling;
    if (input.checked) {
        seatDiv.classList.remove('seat-available');
        seatDiv.classList.add('seat-selected');
    } else {
        seatDiv.classList.remove('seat-selected');
        seatDiv.classList.add('seat-available');
    }
    updateSummary();
}

function updateSummary() {
    const checked = document.querySelectorAll('input[name="seat_ids[]"]:checked');
    const count = checked.length;
    const codes = Array.from(checked).map(el => el.nextElementSibling.dataset.code);

    document.getElementById('selectedCount').textContent = count;
    document.getElementById('selectedSeats').textContent = count > 0 ? codes.join(', ') : 'Belum ada kursi dipilih';
    document.getElementById('totalPrice').textContent = 'Rp ' + (count * pricePerSeat).toLocaleString('id-ID');
    document.getElementById('submitBtn').disabled = count === 0;
}
</script>
@endpush
