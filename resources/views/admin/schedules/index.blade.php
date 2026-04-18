@extends('layouts.admin')
@section('title', 'Kelola Jadwal')
@section('header-actions')
    <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary btn-sm">+ Tambah Jadwal</a>
@endsection

@section('content')
<form method="GET" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <select name="movie_id" class="form-select" style="max-width: 250px;">
        <option value="">Semua Film</option>
        @foreach($movies as $m)<option value="{{ $m->id }}" {{ request('movie_id') == $m->id ? 'selected' : '' }}>{{ $m->title }}</option>@endforeach
    </select>
    <select name="cinema_id" class="form-select" style="max-width: 200px;">
        <option value="">Semua Bioskop</option>
        @foreach($cinemas as $c)<option value="{{ $c->id }}" {{ request('cinema_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
    </select>
    <input type="date" name="date" class="form-input" value="{{ request('date') }}" style="max-width: 180px;">
    <button type="submit" class="btn btn-outline btn-sm">Filter</button>
</form>

<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr><th>Film</th><th>Bioskop / Studio</th><th>Tanggal</th><th>Jam</th><th>Harga (WD/WE)</th><th>Status</th><th style="text-align:right;">Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($schedules as $s)
                <tr>
                    <td style="font-weight:600;">{{ $s->movie->title }}</td>
                    <td><span class="text-sm">{{ $s->studio->cinema->name }}</span><p class="text-muted text-xs">{{ $s->studio->name }} ({{ $s->studio->type_label }})</p></td>
                    <td class="text-sm">{{ $s->show_date->format('d M Y') }}</td>
                    <td style="font-weight:600;">{{ $s->show_time_formatted }}</td>
                    <td class="text-sm">Rp {{ number_format($s->price_weekday, 0, ',', '.') }} / {{ number_format($s->price_weekend, 0, ',', '.') }}</td>
                    <td><span class="badge badge-{{ $s->is_active ? 'success' : 'gray' }}">{{ $s->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td style="text-align:right;">
                        <a href="{{ route('admin.schedules.edit', $s) }}" class="btn btn-outline btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.schedules.destroy', $s) }}" style="display:inline;" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">×</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted" style="padding:2rem;">Belum ada jadwal</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $schedules->withQueryString()->links() }}</div>
@endsection
