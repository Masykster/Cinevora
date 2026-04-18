@extends('layouts.admin')
@section('title', 'Tambah Voucher')

@section('content')
<div class="card" style="max-width: 700px; padding: 2rem;">
    <form method="POST" action="{{ route('admin.vouchers.store') }}">
        @csrf
        @include('admin.vouchers._form')
        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;"><button type="submit" class="btn btn-primary">💾 Buat Voucher</button><a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline">Batal</a></div>
    </form>
</div>
@endsection
