@extends('layouts.app')
@section('title', 'Login')
@section('meta_description', 'Login ke akun Cinevora Anda')

@section('content')
<div class="section" style="display: flex; align-items: center; justify-content: center; min-height: calc(100vh - 200px);">
    <div class="card" style="width: 100%; max-width: 440px; padding: 2.5rem;">
        <div class="text-center mb-4">
            <h1 class="font-heading" style="font-size: 2rem; font-weight: 800; background: linear-gradient(135deg, var(--clr-primary-light), var(--clr-accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Selamat Datang
            </h1>
            <p class="text-muted mt-1">Login ke akun Cinevora Anda</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" required placeholder="••••••••">
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">Login</button>
        </form>

        <p class="text-center text-muted mt-3" style="font-size: 0.9rem;">
            Belum punya akun? <a href="{{ route('register') }}" style="color: var(--clr-primary-light); text-decoration: none; font-weight: 600;">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection
