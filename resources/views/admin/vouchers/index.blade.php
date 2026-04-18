@extends('layouts.admin')
@section('title', 'Kelola Voucher')
@section('header-actions')
    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary btn-sm">+ Tambah Voucher</a>
@endsection

@section('content')
<div class="table-wrapper">
    <table class="table">
        <thead><tr><th>Kode</th><th>Deskripsi</th><th>Tipe</th><th>Target</th><th>Kuota</th><th>Berlaku</th><th>Status</th><th style="text-align:right;">Aksi</th></tr></thead>
        <tbody>
            @forelse($vouchers as $v)
                <tr>
                    <td><span style="font-family: monospace; font-weight: 700;">{{ $v->code }}</span></td>
                    <td class="text-sm">{{ $v->description }}</td>
                    <td><span class="badge badge-accent">{{ $v->type === 'percentage' ? $v->value.'%' : 'Rp '.number_format($v->value,0,',','.') }}</span></td>
                    <td><span class="badge badge-gray">{{ ucfirst($v->target) }}</span></td>
                    <td class="text-sm">{{ $v->used_count }}/{{ $v->quota }}</td>
                    <td class="text-xs text-muted">{{ $v->valid_from->format('d/m/Y') }} - {{ $v->valid_until->format('d/m/Y') }}</td>
                    <td><span class="badge badge-{{ $v->status_label === 'Active' ? 'success' : ($v->status_label === 'Expired' ? 'error' : 'gray') }}">{{ $v->status_label }}</span></td>
                    <td style="text-align:right;">
                        <a href="{{ route('admin.vouchers.edit', $v) }}" class="btn btn-outline btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.vouchers.destroy', $v) }}" style="display:inline;" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">×</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted" style="padding:2rem;">Belum ada voucher</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $vouchers->links() }}</div>
@endsection
