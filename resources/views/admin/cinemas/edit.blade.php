@extends('layouts.admin')
@section('title', 'Edit Bioskop: ' . $cinema->name)

@section('content')
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start;">
    {{-- CINEMA FORM --}}
    <div class="card" style="padding: 2rem;">
        <h3 style="font-family: var(--font-heading); font-weight: 700; margin-bottom: 1.5rem;">Info Bioskop</h3>
        <form method="POST" action="{{ route('admin.cinemas.update', $cinema) }}">
            @csrf @method('PUT')
            <div class="form-group"><label class="form-label">Nama *</label><input type="text" name="name" class="form-input" value="{{ old('name', $cinema->name) }}" required>@error('name')<p class="form-error">{{ $message }}</p>@enderror</div>
            <div class="form-group"><label class="form-label">Kota *</label><input type="text" name="city" class="form-input" value="{{ old('city', $cinema->city) }}" required></div>
            <div class="form-group"><label class="form-label">Alamat *</label><textarea name="address" class="form-textarea" required>{{ old('address', $cinema->address) }}</textarea></div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="description" class="form-textarea">{{ old('description', $cinema->description) }}</textarea></div>
            <div class="form-group"><label class="form-label">Telepon</label><input type="text" name="phone" class="form-input" value="{{ old('phone', $cinema->phone) }}"></div>
            <div class="form-group"><label class="form-checkbox"><input type="checkbox" name="is_active" value="1" {{ $cinema->is_active ? 'checked' : '' }}> Aktif</label></div>
            <button type="submit" class="btn btn-primary">💾 Update</button>
        </form>
    </div>

    {{-- STUDIOS --}}
    <div>
        <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
            <h3 style="font-family: var(--font-heading); font-weight: 700; margin-bottom: 1rem;">🎬 Studios</h3>
            @foreach($cinema->studios as $studio)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; border-bottom: 1px solid var(--clr-border);">
                    <div>
                        <span style="font-weight: 600;">{{ $studio->name }}</span>
                        <span class="badge badge-{{ $studio->type === 'imax' ? 'accent' : ($studio->type === 'vip' ? 'primary' : 'gray') }}" style="margin-left: 0.25rem;">{{ strtoupper($studio->type) }}</span>
                        <p class="text-muted text-xs">{{ $studio->seats_count }} kursi · {{ $studio->rows }}×{{ $studio->cols }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.cinemas.studios.destroy', [$cinema, $studio]) }}" onsubmit="return confirm('Hapus studio ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">×</button>
                    </form>
                </div>
            @endforeach
        </div>

        {{-- ADD STUDIO --}}
        <div class="card" style="padding: 1.5rem;">
            <h3 style="font-family: var(--font-heading); font-weight: 700; margin-bottom: 1rem;">+ Tambah Studio</h3>
            <form method="POST" action="{{ route('admin.cinemas.studios.store', $cinema) }}">
                @csrf
                <div class="form-group"><label class="form-label">Nama Studio *</label><input type="text" name="name" class="form-input" required placeholder="Studio 1"></div>
                <div class="form-group"><label class="form-label">Tipe *</label><select name="type" class="form-select"><option value="regular">Regular</option><option value="imax">IMAX</option><option value="vip">VIP</option></select></div>
                <div class="grid grid-2 gap-2">
                    <div class="form-group"><label class="form-label">Baris *</label><input type="number" name="rows" class="form-input" value="8" min="3" max="20" required></div>
                    <div class="form-group"><label class="form-label">Kolom *</label><input type="number" name="cols" class="form-input" value="10" min="4" max="20" required></div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Tambah Studio</button>
            </form>
        </div>
    </div>
</div>
@endsection
