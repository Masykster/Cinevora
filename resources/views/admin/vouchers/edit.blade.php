@extends('layouts.admin')
@section('title', 'Edit Voucher')

@section('content')
<div class="card" style="max-width: 700px; padding: 2rem;">
    <form method="POST" action="{{ route('admin.vouchers.update', $voucher) }}">
        @csrf @method('PUT')
        @include('admin.vouchers._form', ['voucher' => $voucher])
        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;"><button type="submit" class="btn btn-primary">💾 Update</button><a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline">Batal</a></div>
    </form>
</div>
@endsection
