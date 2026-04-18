@extends('layouts.app')
@section('title', 'Pilih Kursi')
@section('meta_description', 'Pilih kursi untuk menonton ' . $schedule->movie->title)

@section('content')
<section class="section">
    <div class="container" style="max-width: 900px;">
        {{-- HEADER INFO --}}
        <div class="card" style="padding: 1.5rem; margin-bottom: 2rem; display: flex; gap: 1.5rem; align-items: center; flex-wrap: wrap;">
            <div style="width: 60px; height: 90px; background: linear-gradient(135deg, var(--clr-surface-2), var(--clr-surface-3)); border-radius: var(--radius); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">🎬</div>
            <div style="flex: 1;">
                <h1 class="font-heading" style="font-size: 1.3rem; font-weight: 700;">{{ $schedule->movie->title }}</h1>
                <p class="text-muted text-sm">{{ $schedule->studio->cinema->name }} · {{ $schedule->studio->name }} ({{ $schedule->studio->type_label }})</p>
                <div style="display: flex; gap: 1rem; margin-top: 0.5rem; flex-wrap: wrap;">
                    <span class="text-sm">📅 {{ $schedule->show_date->format('l, d M Y') }}</span>
                    <span class="text-sm">🕐 {{ $schedule->show_time_formatted }}</span>
                    <span class="text-sm text-accent font-bold">Rp {{ number_format($schedule->price, 0, ',', '.') }}/kursi</span>
                </div>
            </div>
        </div>

        {{-- SCREEN --}}
        <div class="text-center mb-3">
            <div style="max-width: 500px; margin: 0 auto;">
                <div style="height: 4px; background: linear-gradient(90deg, transparent, var(--clr-primary-light), var(--clr-accent), var(--clr-primary-light), transparent); border-radius: 4px; margin-bottom: 0.5rem;"></div>
                <p class="text-muted text-xs">LAYAR</p>
            </div>
        </div>

        {{-- SEAT MAP --}}
        <form method="POST" action="{{ route('booking.process', $schedule) }}" id="seatForm">
            @csrf

            <div style="overflow-x: auto; padding: 1rem 0;">
                <div style="display: flex; flex-direction: column; gap: 0.4rem; align-items: center; min-width: fit-content;">
                    @foreach($seatsByRow as $rowLabel => $seats)
                        <div style="display: flex; align-items: center; gap: 0.4rem;">
                            <span style="width: 24px; text-align: center; font-size: 0.75rem; font-weight: 600; color: var(--clr-text-muted);">{{ $rowLabel }}</span>

                            @foreach($seats as $seat)
                                @php
                                    $isBooked = in_array($seat->id, $bookedSeatIds);
                                @endphp
                                <label style="cursor: {{ $isBooked ? 'not-allowed' : 'pointer' }};">
                                    <input type="checkbox" name="seat_ids[]" value="{{ $seat->id }}"
                                        {{ $isBooked ? 'disabled' : '' }}
                                        style="display: none;"
                                        onchange="updateSeatUI(this)">
                                    <div class="seat {{ $isBooked ? 'seat-booked' : 'seat-available' }}"
                                         id="seat-{{ $seat->id }}"
                                         title="{{ $seat->code }}"
                                         data-code="{{ $seat->code }}"
                                         data-price="{{ $schedule->price }}">
                                    </div>
                                </label>
                            @endforeach

                            <span style="width: 24px; text-align: center; font-size: 0.75rem; font-weight: 600; color: var(--clr-text-muted);">{{ $rowLabel }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- LEGEND --}}
            <div style="display: flex; gap: 2rem; justify-content: center; margin: 1.5rem 0; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div class="seat seat-available" style="cursor: default;"></div>
                    <span class="text-muted text-xs">Tersedia</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div class="seat seat-selected" style="cursor: default;"></div>
                    <span class="text-muted text-xs">Dipilih</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div class="seat seat-booked" style="cursor: default;"></div>
                    <span class="text-muted text-xs">Terisi</span>
                </div>
            </div>

            {{-- SUMMARY --}}
            <div class="card" style="padding: 1.5rem; margin-top: 1rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <p class="text-muted text-sm">Kursi dipilih: <span id="selectedCount" style="color: var(--clr-text); font-weight: 600;">0</span></p>
                        <p class="text-muted text-xs" id="selectedSeats">Belum ada kursi dipilih</p>
                    </div>
                    <div style="text-align: right;">
                        <p class="text-muted text-sm">Total</p>
                        <p class="font-heading" style="font-size: 1.5rem; font-weight: 800; color: var(--clr-accent);" id="totalPrice">Rp 0</p>
                    </div>
                </div>
                <button type="submit" class="btn btn-accent btn-block btn-lg mt-3" id="submitBtn" disabled>
                    Lanjut ke Checkout
                </button>
            </div>
        </form>

        @if($errors->any())
            <div class="alert alert-error mt-2">
                <span>✕</span> {{ $errors->first() }}
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .seat {
        width: 32px; height: 32px; border-radius: 6px 6px 8px 8px;
        transition: all 0.2s ease; display: flex; align-items: center; justify-content: center;
        font-size: 0.55rem; font-weight: 600;
    }
    .seat-available {
        background: var(--clr-surface-3); border: 1.5px solid var(--clr-border);
    }
    .seat-available:hover {
        background: rgba(124, 58, 237, 0.2); border-color: var(--clr-primary);
        transform: scale(1.1);
    }
    .seat-selected {
        background: var(--clr-primary) !important; border-color: var(--clr-primary-light) !important;
        color: #fff; transform: scale(1.05);
        box-shadow: 0 0 10px rgba(124, 58, 237, 0.4);
    }
    .seat-booked {
        background: var(--clr-surface-2); border: 1.5px solid var(--clr-border);
        opacity: 0.3;
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
