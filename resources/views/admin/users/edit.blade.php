@extends('layouts.admin')
@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="card" style="max-width: 600px; padding: 2rem;">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')
        <div class="form-group"><label class="form-label">Nama *</label><input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>@error('name')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div class="form-group"><label class="form-label">Email *</label><input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>@error('email')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div class="form-group"><label class="form-label">Role *</label><select name="role" class="form-select" required>
            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
            <option value="cinema_admin" {{ $user->role === 'cinema_admin' ? 'selected' : '' }}>Cinema Admin</option>
            <option value="cafe_admin" {{ $user->role === 'cafe_admin' ? 'selected' : '' }}>Cafe Admin</option>
        </select></div>
        <div class="form-group"><label class="form-label">Telepon</label><input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}"></div>
        <div style="display: flex; gap: 1rem;"><button type="submit" class="btn btn-primary">💾 Update</button><a href="{{ route('admin.users.index') }}" class="btn btn-outline">Batal</a></div>
    </form>
</div>
@endsection
