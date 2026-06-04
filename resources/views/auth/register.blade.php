@extends('layouts.app')
@section('title', 'Daftar')
@section('meta_description', 'Buat akun Cinevora baru untuk booking tiket bioskop dan kafe')

@section('content')
<div class="section" style="display: flex; align-items: center; justify-content: center; min-height: calc(100vh - 200px);">
    <div class="card" style="width: 100%; max-width: 440px; padding: 2.5rem;">
        <div class="text-center mb-4">
            <h1 class="font-heading" style="font-size: 2rem; font-weight: 800; color: var(--clr-text);">
                Buat Akun
            </h1>
            <p class="text-muted mt-1">Daftar untuk mulai booking</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required autofocus placeholder="John Doe">
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required placeholder="nama@email.com">
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">No. Telepon <span class="text-muted">(opsional)</span></label>
                <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" required placeholder="Min. 8 karakter">
                @error('password')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required placeholder="Ulangi password">
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">Daftar</button>
        </form>

        <p class="text-center text-muted mt-3" style="font-size: 0.9rem;">
            Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--clr-primary); text-decoration: none; font-weight: 600;">Login</a>
        </p>
    </div>
</div>
@endsection
