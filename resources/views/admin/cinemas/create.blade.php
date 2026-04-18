@extends('layouts.admin')
@section('title', 'Tambah Bioskop')

@section('content')
<div class="card" style="max-width: 700px; padding: 2rem;">
    <form method="POST" action="{{ route('admin.cinemas.store') }}">
        @csrf
        <div class="form-group"><label class="form-label">Nama *</label><input type="text" name="name" class="form-input" value="{{ old('name') }}" required>@error('name')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div class="form-group"><label class="form-label">Kota *</label><input type="text" name="city" class="form-input" value="{{ old('city') }}" required>@error('city')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div class="form-group"><label class="form-label">Alamat *</label><textarea name="address" class="form-textarea" required>{{ old('address') }}</textarea>@error('address')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="description" class="form-textarea">{{ old('description') }}</textarea></div>
        <div class="form-group"><label class="form-label">Telepon</label><input type="text" name="phone" class="form-input" value="{{ old('phone') }}"></div>
        <div class="form-group"><label class="form-checkbox"><input type="checkbox" name="is_active" value="1" checked> Aktif</label></div>
        <div style="display: flex; gap: 1rem;"><button type="submit" class="btn btn-primary">💾 Simpan</button><a href="{{ route('admin.cinemas.index') }}" class="btn btn-outline">Batal</a></div>
    </form>
</div>
@endsection
