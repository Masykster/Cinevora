@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<section class="section">
    <div class="container" style="max-width: 900px;">
        <h1 class="font-heading" style="font-size: 1.8rem; font-weight: 800; margin-bottom: 2rem;">Profil Saya</h1>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            {{-- PROFILE FORM --}}
            <div class="card" style="padding: 1.5rem;">
                <h3 class="font-heading" style="font-weight: 700; margin-bottom: 1rem;">Informasi Profil</h3>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label class="form-label" for="name">Nama</label>
                        <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" value="{{ $user->email }}" disabled style="opacity: 0.6;">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="phone">Telepon</label>
                        <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>

            {{-- PASSWORD FORM --}}
            <div class="card" style="padding: 1.5rem;">
                <h3 class="font-heading" style="font-weight: 700; margin-bottom: 1rem;">Ubah Password</h3>
                <form method="POST" action="{{ route('profile.updatePassword') }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label class="form-label" for="current_password">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" class="form-input" required>
                        @error('current_password')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password">Password Baru</label>
                        <input type="password" id="password" name="password" class="form-input" required>
                        @error('password')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Ubah Password</button>
                </form>
            </div>
        </div>

        {{-- TRANSACTION HISTORY --}}
        <div style="margin-top: 2.5rem;">
            <h2 class="font-heading" style="font-size: 1.4rem; font-weight: 700; margin-bottom: 1.5rem;">📋 Riwayat Transaksi</h2>

            @if($transactions->count() > 0)
                @foreach($transactions as $tx)
                    <div class="card" style="margin-bottom: 1rem; padding: 1.25rem;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                    <span style="font-family: monospace; font-size: 0.8rem; font-weight: 600;">{{ $tx->invoice_number }}</span>
                                    <span class="badge badge-{{ $tx->status === 'paid' ? 'success' : ($tx->status === 'pending' ? 'yellow' : 'error') }}">
                                        {{ ucfirst($tx->status) }}
                                    </span>
                                </div>

                                @if($tx->tickets->count() > 0)
                                    <p style="font-weight: 600;">🎬 {{ $tx->tickets->first()->schedule->movie->title }}</p>
                                @endif

                                @if($tx->orderItems->count() > 0)
                                    <p class="text-muted text-xs">🍿 {{ $tx->orderItems->count() }} item F&B</p>
                                @endif

                                <p class="text-muted text-xs mt-1">{{ $tx->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div style="text-align: right;">
                                <p class="font-heading font-bold text-accent" style="font-size: 1.1rem;">{{ $tx->formatted_grand_total }}</p>
                                @if($tx->status === 'paid')
                                    <a href="{{ route('checkout.invoice', $tx) }}" class="btn btn-outline btn-sm mt-1">Lihat Invoice</a>
                                @elseif($tx->status === 'pending')
                                    <a href="{{ route('checkout.index', $tx) }}" class="btn btn-accent btn-sm mt-1">Lanjut Bayar</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="pagination">{{ $transactions->links() }}</div>
            @else
                <div class="text-center" style="padding: 3rem 0;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🎬</div>
                    <p class="text-muted">Belum ada transaksi. <a href="{{ route('movies.index') }}" style="color: var(--clr-primary-light);">Mulai booking sekarang!</a></p>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    @media (max-width: 768px) {
        .section .container > div:first-child { grid-template-columns: 1fr !important; }
    }
</style>
@endpush
