@extends('layouts.admin')
@section('title', 'Edit Jadwal')

@section('content')
<div class="card" style="max-width: 700px; padding: 2rem;">
    <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}">
        @csrf @method('PUT')
        <div class="form-group"><label class="form-label">Film *</label>
            <select name="movie_id" class="form-select" required>
                @foreach($movies as $m)<option value="{{ $m->id }}" {{ $schedule->movie_id == $m->id ? 'selected' : '' }}>{{ $m->title }}</option>@endforeach
            </select>
        </div>
        <div class="form-group"><label class="form-label">Studio *</label>
            <select name="studio_id" class="form-select" required>
                @foreach($cinemas as $cinema)
                    <optgroup label="{{ $cinema->name }}">
                        @foreach($cinema->studios as $studio)
                            <option value="{{ $studio->id }}" {{ $schedule->studio_id == $studio->id ? 'selected' : '' }}>{{ $studio->name }} ({{ strtoupper($studio->type) }})</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
        <div class="grid grid-2 gap-3">
            <div class="form-group"><label class="form-label">Tanggal *</label><input type="date" name="show_date" class="form-input" value="{{ $schedule->show_date->format('Y-m-d') }}" required></div>
            <div class="form-group"><label class="form-label">Jam *</label><input type="time" name="show_time" class="form-input" value="{{ \Carbon\Carbon::parse($schedule->show_time)->format('H:i') }}" required></div>
        </div>
        <div class="grid grid-2 gap-3">
            <div class="form-group"><label class="form-label">Harga Weekday *</label><input type="number" name="price_weekday" class="form-input" value="{{ $schedule->price_weekday }}" required></div>
            <div class="form-group"><label class="form-label">Harga Weekend *</label><input type="number" name="price_weekend" class="form-input" value="{{ $schedule->price_weekend }}" required></div>
        </div>
        <div class="form-group"><label class="form-checkbox"><input type="checkbox" name="is_active" value="1" {{ $schedule->is_active ? 'checked' : '' }}> Aktif</label></div>
        <div style="display: flex; gap: 1rem;"><button type="submit" class="btn btn-primary">💾 Update</button><a href="{{ route('admin.schedules.index') }}" class="btn btn-outline">Batal</a></div>
    </form>
</div>
@endsection
