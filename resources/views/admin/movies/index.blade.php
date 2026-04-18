@extends('layouts.admin')
@section('title', 'Kelola Film')
@section('header-actions')
    <a href="{{ route('admin.movies.create') }}" class="btn btn-primary btn-sm">+ Tambah Film</a>
@endsection

@section('content')
<form method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <input type="text" name="search" class="form-input" placeholder="Cari film..." value="{{ request('search') }}" style="max-width: 300px;">
    <select name="status" class="form-select" style="max-width: 180px;" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="now_playing" {{ request('status') === 'now_playing' ? 'selected' : '' }}>Now Playing</option>
        <option value="coming_soon" {{ request('status') === 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
        <option value="ended" {{ request('status') === 'ended' ? 'selected' : '' }}>Ended</option>
    </select>
    <button type="submit" class="btn btn-outline btn-sm">Filter</button>
</form>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>Film</th>
                <th>Genre</th>
                <th>Durasi</th>
                <th>Rating</th>
                <th>Status</th>
                <th>Rilis</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movies as $movie)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 40px; height: 56px; background: var(--clr-surface-3); border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">🎬</div>
                            <div>
                                <span style="font-weight: 600;">{{ $movie->title }}</span>
                                <p class="text-muted text-xs">{{ $movie->director }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-sm">{{ $movie->genre }}</td>
                    <td class="text-sm">{{ $movie->duration_formatted }}</td>
                    <td><span class="badge badge-accent">⭐ {{ number_format($movie->rating, 1) }}</span></td>
                    <td>
                        <span class="badge badge-{{ $movie->status === 'now_playing' ? 'success' : ($movie->status === 'coming_soon' ? 'primary' : 'gray') }}">
                            {{ str_replace('_', ' ', ucfirst($movie->status)) }}
                        </span>
                    </td>
                    <td class="text-sm">{{ $movie->release_date->format('d M Y') }}</td>
                    <td style="text-align: right;">
                        <div style="display: flex; gap: 0.25rem; justify-content: flex-end;">
                            <a href="{{ route('admin.movies.edit', $movie) }}" class="btn btn-outline btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.movies.destroy', $movie) }}" onsubmit="return confirm('Hapus film ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted" style="padding: 2rem;">Belum ada film</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination">{{ $movies->withQueryString()->links() }}</div>
@endsection
