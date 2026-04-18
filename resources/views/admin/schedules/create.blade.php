@extends('layouts.admin')
@section('title', 'Tambah Jadwal')

@section('content')
<div class="card" style="max-width: 700px; padding: 2rem;">
    <form method="POST" action="{{ route('admin.schedules.store') }}">
        @csrf
        <div class="form-group"><label class="form-label">Film *</label>
            <select name="movie_id" class="form-select" required>
                <option value="">Pilih Film</option>
                @foreach($movies as $m)<option value="{{ $m->id }}" {{ old('movie_id') == $m->id ? 'selected' : '' }}>{{ $m->title }}</option>@endforeach
            </select>@error('movie_id')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-group"><label class="form-label">Bioskop & Studio *</label>
            <select name="studio_id" class="form-select" required>
                <option value="">Pilih Studio</option>
                @foreach($cinemas as $cinema)
                    <optgroup label="{{ $cinema->name }}">
                        @foreach($cinema->studios as $studio)
                            <option value="{{ $studio->id }}" {{ old('studio_id') == $studio->id ? 'selected' : '' }}>{{ $studio->name }} ({{ strtoupper($studio->type) }})</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>@error('studio_id')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Tayang * (bisa pilih beberapa)</label>
            <div id="dateFields">
                <input type="date" name="show_dates[]" class="form-input mb-1" required min="{{ date('Y-m-d') }}">
            </div>
            <button type="button" onclick="addDateField()" class="btn btn-ghost btn-sm mt-1">+ Tambah Tanggal</button>
            @error('show_dates')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Jam Tayang * (bisa pilih beberapa)</label>
            <div id="timeFields">
                <input type="time" name="show_times[]" class="form-input mb-1" required>
            </div>
            <button type="button" onclick="addTimeField()" class="btn btn-ghost btn-sm mt-1">+ Tambah Jam</button>
            @error('show_times')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-2 gap-3">
            <div class="form-group"><label class="form-label">Harga Weekday (Rp) *</label><input type="number" name="price_weekday" class="form-input" value="{{ old('price_weekday', 40000) }}" required min="0">@error('price_weekday')<p class="form-error">{{ $message }}</p>@enderror</div>
            <div class="form-group"><label class="form-label">Harga Weekend (Rp) *</label><input type="number" name="price_weekend" class="form-input" value="{{ old('price_weekend', 55000) }}" required min="0">@error('price_weekend')<p class="form-error">{{ $message }}</p>@enderror</div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" class="btn btn-primary">💾 Buat Jadwal</button>
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function addDateField() { const div = document.getElementById('dateFields'); div.insertAdjacentHTML('beforeend', '<input type="date" name="show_dates[]" class="form-input mb-1" required min="{{ date("Y-m-d") }}">'); }
function addTimeField() { const div = document.getElementById('timeFields'); div.insertAdjacentHTML('beforeend', '<input type="time" name="show_times[]" class="form-input mb-1" required>'); }
</script>
@endpush
