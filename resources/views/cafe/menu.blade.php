@extends('layouts.app')
@section('title', 'Menu Kafe')
@section('meta_description', 'Menu makanan dan minuman kafe Cinevora - Popcorn, snack, dan minuman segar untuk menemani film favorit Anda')

@section('content')
<section class="section">
    <div class="container">
        <div class="text-center mb-4">
            <h1 class="font-heading" style="font-size: 2.5rem; font-weight: 800;">
                <span style="background: linear-gradient(135deg, var(--clr-primary-light), var(--clr-accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Cinevora Café</span>
            </h1>
            <p class="text-muted mt-1" style="max-width: 500px; margin: 0.5rem auto 0;">Lengkapi pengalaman menontonmu dengan snack dan minuman favorit</p>
        </div>

        @foreach($categories as $category)
            @if($category->products->count() > 0)
            <div style="margin-bottom: 3rem;">
                <h2 class="font-heading" style="font-size: 1.4rem; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span>{{ $category->icon }}</span> {{ $category->name }}
                </h2>

                <div class="grid grid-3 gap-3">
                    @foreach($category->products as $product)
                        <div class="card" style="display: flex; flex-direction: column;">
                            <div style="height: 160px; background: linear-gradient(135deg, var(--clr-surface-2), var(--clr-surface-3)); display: flex; align-items: center; justify-content: center; font-size: 3rem;">
                                {{ $category->icon }}
                            </div>
                            <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column;">
                                <h3 style="font-family: var(--font-heading); font-weight: 700; margin-bottom: 0.3rem;">{{ $product->name }}</h3>
                                <p class="text-muted text-xs" style="flex: 1;">{{ $product->description }}</p>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                                    <span style="font-family: var(--font-heading); font-weight: 800; color: var(--clr-accent); font-size: 1.1rem;">
                                        {{ $product->formatted_price }}
                                    </span>
                                    @if(!$product->is_available)
                                        <span class="badge badge-error">Habis</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach

        <div class="text-center" style="padding: 2rem 0;">
            <div class="card card-glass" style="display: inline-block; padding: 2rem 3rem;">
                <p class="text-muted mb-2">Pesan F&B bersamaan dengan tiket film Anda!</p>
                <a href="{{ route('movies.index') }}" class="btn btn-primary">🎬 Lihat Film & Booking</a>
            </div>
        </div>
    </div>
</section>
@endsection
