@extends('layouts.admin')
@section('title', 'Kelola Users')

@section('content')
<form method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <input type="text" name="search" class="form-input" placeholder="Cari nama/email..." value="{{ request('search') }}" style="max-width: 300px;">
    <select name="role" class="form-select" style="max-width: 180px;" onchange="this.form.submit()">
        <option value="">Semua Role</option>
        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
        <option value="cinema_admin" {{ request('role') === 'cinema_admin' ? 'selected' : '' }}>Cinema Admin</option>
        <option value="cafe_admin" {{ request('role') === 'cafe_admin' ? 'selected' : '' }}>Cafe Admin</option>
    </select>
    <button type="submit" class="btn btn-outline btn-sm">Filter</button>
</form>

<div class="table-wrapper">
    <table class="table">
        <thead><tr><th>User</th><th>Role</th><th>Telepon</th><th>Bergabung</th><th style="text-align:right;">Aksi</th></tr></thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--clr-primary), var(--clr-accent)); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; color: #fff;">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <div><span style="font-weight: 600;">{{ $user->name }}</span><p class="text-muted text-xs">{{ $user->email }}</p></div>
                        </div>
                    </td>
                    <td><span class="badge badge-{{ $user->role === 'cinema_admin' ? 'accent' : ($user->role === 'cafe_admin' ? 'primary' : 'gray') }}">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span></td>
                    <td class="text-sm">{{ $user->phone ?? '-' }}</td>
                    <td class="text-sm text-muted">{{ $user->created_at->format('d M Y') }}</td>
                    <td style="text-align:right;">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline btn-sm">Edit</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted" style="padding:2rem;">Tidak ada user</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $users->withQueryString()->links() }}</div>
@endsection
