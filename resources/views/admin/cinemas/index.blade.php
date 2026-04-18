@extends('layouts.admin')
@section('title', 'Kelola Bioskop')
@section('header-actions')
    <a href="{{ route('admin.cinemas.create') }}" class="btn btn-primary btn-sm">+ Tambah Bioskop</a>
@endsection

@section('content')
<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr><th>Bioskop</th><th>Kota</th><th>Studios</th><th>Status</th><th style="text-align:right;">Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($cinemas as $cinema)
                <tr>
                    <td><span style="font-weight: 600;">{{ $cinema->name }}</span><p class="text-muted text-xs">{{ Str::limit($cinema->address, 50) }}</p></td>
                    <td>{{ $cinema->city }}</td>
                    <td><span class="badge badge-primary">{{ $cinema->studios_count }} studio</span></td>
                    <td><span class="badge badge-{{ $cinema->is_active ? 'success' : 'gray' }}">{{ $cinema->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td style="text-align:right;">
                        <a href="{{ route('admin.cinemas.edit', $cinema) }}" class="btn btn-outline btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.cinemas.destroy', $cinema) }}" style="display:inline;" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">Hapus</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted" style="padding:2rem;">Belum ada bioskop</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $cinemas->links() }}</div>
@endsection
