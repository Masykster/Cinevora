@extends('layouts.app')
@section('title', 'Menu Kafe - Cinevora')
@section('meta_description', 'Menu makanan dan minuman kafe Cinevora - Popcorn, snack, dan minuman segar untuk menemani film favorit Anda')

@section('content')
<section class="section" style="max-width: 1024px; margin: 0 auto;">
    <div class="container">
        <div class="text-center mb-5" style="border-bottom: 1px solid var(--clr-border); padding-bottom: 2rem;">
            <h1 class="font-heading" style="font-size: 2.2rem; font-weight: 800; color: #fff; text-transform: uppercase; letter-spacing: -0.5px;">
                <span style="color: var(--clr-primary);">Cinevora</span> Café
            </h1>
            <p class="text-muted mt-2" style="max-width: 500px; margin: 0.5rem auto 0; font-size: 0.85rem;">Lengkapi pengalaman menontonmu dengan popcorn hangat, camilan renyah, dan minuman segar favoritmu</p>
        </div>

        @foreach($categories as $category)
            @if($category->products->count() > 0)
            <div style="margin-bottom: 3.5rem;">
                <h2 class="font-heading" style="font-size: 1.3rem; font-weight: 800; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; color: #fff; text-transform: uppercase; letter-spacing: 0.5px; border-left: 3px solid var(--clr-primary); padding-left: 0.75rem;">
                    <span style="font-size: 1.5rem;">{{ $category->icon }}</span> {{ $category->name }}
                </h2>

                <div class="grid grid-3 gap-3">
                    @foreach($category->products as $product)
                        <div class="card cafe-card" style="display: flex; flex-direction: column; overflow: hidden; border: 1px solid var(--clr-border); background: var(--clr-surface); box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
                            <div style="height: 150px; background: var(--clr-surface-2); display: flex; align-items: center; justify-content: center; font-size: 3.5rem; border-bottom: 1px solid var(--clr-border); position: relative;">
                                <div style="position: absolute; width: 80px; height: 80px; border-radius: 50%; background: var(--clr-primary-dim); filter: blur(15px); z-index: 1;"></div>
                                <span style="position: relative; z-index: 2;">{{ $category->icon }}</span>
                            </div>
                            <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                <div>
                                    <h3 style="font-family: var(--font-heading); font-weight: 800; font-size: 1rem; color: #fff; margin-bottom: 0.35rem; letter-spacing: -0.2px;">{{ $product->name }}</h3>
                                    <p class="text-muted text-xs" style="line-height: 1.4; margin-bottom: 1rem;">{{ $product->description }}</p>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--clr-border); padding-top: 0.75rem;">
                                    <span style="font-family: var(--font-heading); font-weight: 800; color: var(--clr-primary); font-size: 1.1rem;">
                                        {{ $product->formatted_price }}
                                    </span>
                                    @if(!$product->is_available)
                                        <span class="badge" style="background: var(--clr-error); color: #fff; font-size: 0.6rem; text-transform: uppercase;">Habis</span>
                                    @else
                                        <span class="badge" style="background: var(--clr-success); color: #fff; font-size: 0.6rem; text-transform: uppercase;">Tersedia</span>
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
            <div class="card" style="display: inline-block; padding: 2rem 3rem; background: var(--clr-surface-2); border: 1px dashed var(--clr-primary); box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
                <p class="text-muted mb-3 text-sm" style="font-weight: 500;">Pesan F&B bersamaan dengan tiket film Anda untuk menghindari antrean!</p>
                <a href="{{ route('movies.index') }}" class="btn btn-primary" style="font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.8rem; border-radius: 4px;">
                    🎬 Lihat Film & Booking Tiket
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .cafe-card {
        transition: var(--transition);
    }
    .cafe-card:hover {
        transform: translateY(-4px);
        border-color: var(--clr-primary);
        box-shadow: 0 8px 25px rgba(247, 148, 30, 0.15);
    }
</style>
@endpush
