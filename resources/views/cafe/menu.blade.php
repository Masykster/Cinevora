@extends('layouts.app')
@section('title', 'Menu Kafe - Cinevora')
@section('meta_description', 'Menu makanan dan minuman kafe Cinevora - Popcorn, snack, dan minuman segar untuk menemani film favorit Anda')

@section('content')
<section class="section" style="max-width: 1024px; margin: 0 auto; position: relative;">
    <div class="container">
        <div class="text-center mb-5" style="border-bottom: 1px solid var(--clr-border); padding-bottom: 2rem;">
            <h1 class="font-heading" style="font-size: 2.2rem; font-weight: 800; color: #fff; text-transform: uppercase; letter-spacing: -0.5px;">
                <span style="color: var(--clr-primary);">Cinevora</span> Café
            </h1>
            <p class="text-muted mt-2" style="max-width: 500px; margin: 0.5rem auto 0; font-size: 0.85rem;">Lengkapi pengalaman menontonmu dengan popcorn hangat, camilan renyah, dan minuman segar favoritmu</p>
        </div>

        {{-- CATEGORY FILTER PILLS --}}
        <div class="category-filter-wrapper" style="margin-bottom: 2.5rem; overflow-x: auto; display: flex; gap: 0.75rem; padding-bottom: 0.75rem; scrollbar-width: none; -ms-overflow-style: none;">
            <button class="filter-pill active" onclick="filterCategory('all')">
                🍽️ Semua
            </button>
            @foreach($categories as $category)
                @if($category->products->count() > 0)
                    <button class="filter-pill" onclick="filterCategory('{{ $category->slug }}')" data-slug="{{ $category->slug }}">
                        <span>{{ $category->icon }}</span> {{ $category->name }}
                    </button>
                @endif
            @endforeach
        </div>

        @foreach($categories as $category)
            @if($category->products->count() > 0)
            <div class="category-section" data-category-slug="{{ $category->slug }}" style="margin-bottom: 3.5rem;">
                <h2 class="font-heading" style="font-size: 1.3rem; font-weight: 800; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; color: #fff; text-transform: uppercase; letter-spacing: 0.5px; border-left: 3px solid var(--clr-primary); padding-left: 0.75rem;">
                    <span style="font-size: 1.5rem;">{{ $category->icon }}</span> {{ $category->name }}
                </h2>

                <div class="grid grid-3 gap-3">
                    @foreach($category->products as $product)
                        <div class="card cafe-card" style="display: flex; flex-direction: row; gap: 1rem; padding: 1.25rem; overflow: hidden; border: 1px solid var(--clr-border); background: var(--clr-surface); box-shadow: 0 4px 15px rgba(0,0,0,0.5); min-height: 160px; border-radius: 12px; transition: var(--transition);">
                            {{-- Left side: Text Details --}}
                            <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between; min-width: 0;">
                                <div>
                                    <h3 style="font-family: var(--font-heading); font-weight: 800; font-size: 1rem; color: #fff; margin-bottom: 0.35rem; letter-spacing: -0.2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $product->name }}</h3>
                                    <p class="text-muted text-xs" style="line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0;">{{ $product->description }}</p>
                                </div>
                                <div style="margin-top: auto; padding-top: 0.5rem;">
                                    <span style="font-family: var(--font-heading); font-weight: 800; color: var(--clr-primary); font-size: 1.1rem; display: block;">
                                        {{ $product->formatted_price }}
                                    </span>
                                </div>
                            </div>

                            {{-- Right side: Image and Button --}}
                            <div style="width: 100px; display: flex; flex-direction: column; align-items: center; justify-content: space-between; flex-shrink: 0;">
                                {{-- Square Image Container --}}
                                <div style="width: 100px; height: 100px; background: var(--clr-surface-2); display: flex; align-items: center; justify-content: center; border-radius: 12px; overflow: hidden; border: 1px solid var(--clr-border); position: relative; flex-shrink: 0;">
                                    @if($product->image)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="skeleton-img product-image" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                                    @else
                                        <div style="position: absolute; width: 50px; height: 50px; border-radius: 50%; background: var(--clr-primary-dim); filter: blur(10px); z-index: 1;"></div>
                                        <span style="font-size: 2.2rem; position: relative; z-index: 2;">{{ $category->icon }}</span>
                                    @endif
                                </div>

                                {{-- Tambah Button --}}
                                <div style="width: 100%; margin-top: 0.75rem; flex-shrink: 0;">
                                    @if(!$product->is_available)
                                        <span class="badge" style="background: var(--clr-error); color: #fff; font-size: 0.6rem; text-transform: uppercase; width: 100%; text-align: center; display: block; padding: 0.35rem 0; border-radius: 50px;">Habis</span>
                                    @else
                                        <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $category->icon }}')" class="btn btn-outline btn-sm add-to-cart-btn" style="border-radius: 50px; font-weight: 700; width: 100%; padding: 0.35rem 0; font-size: 0.75rem; text-transform: uppercase; border-width: 1.5px; border-color: var(--clr-border-dark); color: #fff; text-align: center; cursor: pointer; transition: var(--transition);">
                                            Tambah
                                        </button>
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

{{-- FLOATING CART BUTTON --}}
<div id="floatingCartBtn" onclick="toggleCart()" style="position: fixed; bottom: calc(var(--bottom-nav-height, 64px) + 1rem); right: 1.5rem; background: var(--clr-primary); color: #000; width: 60px; height: 60px; border-radius: 50%; display: none; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 4px 20px rgba(188, 163, 116, 0.35); cursor: pointer; z-index: 99; transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);">
    <i class='bx bxs-shopping-bag'></i>
    <span id="cartCountBadge" style="position: absolute; top: -5px; right: -5px; background: #fff; color: #000; font-size: 0.75rem; font-weight: 800; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">0</span>
</div>

{{-- OFFCANVAS CART --}}
<div id="cartOverlay" onclick="toggleCart()" style="position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); z-index: 100; opacity: 0; visibility: hidden; transition: all 0.3s ease;"></div>
<div id="cartOffcanvas" style="position: fixed; top: 0; right: -400px; width: 400px; max-width: 100%; height: 100vh; background: var(--clr-surface); border-left: 1px solid var(--clr-border); z-index: 101; display: flex; flex-direction: column; transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: -10px 0 30px rgba(0,0,0,0.8);">
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--clr-border); display: flex; justify-content: space-between; align-items: center; background: var(--clr-surface-2);">
        <h3 class="font-heading" style="font-weight: 800; font-size: 1.2rem; color: #fff; display: flex; align-items: center; gap: 0.5rem;">
            <i class='bx bxs-shopping-bag text-primary'></i> Pesanan Anda
        </h3>
        <button onclick="toggleCart()" class="btn btn-ghost btn-sm" style="font-size: 1.2rem; padding: 0.2rem 0.5rem; color: var(--clr-text-muted);">✕</button>
    </div>

    <div id="cartItemsContainer" style="flex: 1; overflow-y: auto; padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
        <!-- Cart items injected via JS -->
    </div>

    <div style="padding: 1.5rem; background: var(--clr-surface-2); border-top: 1px solid var(--clr-border);">
        <div class="form-group mb-3">
            <label class="form-label" style="color: #fff;">📍 Pilih Lokasi Pengambilan</label>
            <select id="cinemaSelect" class="form-select" style="font-weight: 600;">
                <option value="">-- Pilih Bioskop --</option>
                @foreach($cinemas as $cinema)
                    <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <span class="font-heading" style="font-weight: 700; color: var(--clr-text-muted); text-transform: uppercase; font-size: 0.9rem;">Total Bayar</span>
            <span id="cartTotal" class="font-heading" style="font-size: 1.4rem; font-weight: 800; color: var(--clr-primary);">Rp 0</span>
        </div>

        <button onclick="checkoutCart()" id="checkoutBtn" class="btn btn-primary btn-block" style="padding: 0.9rem; font-weight: 800; font-size: 0.9rem; letter-spacing: 1px;">
            LANJUTKAN PEMBAYARAN
        </button>
    </div>
</div>

@endsection

@push('styles')
<style>
    .category-filter-wrapper::-webkit-scrollbar {
        display: none;
    }
    .filter-pill {
        background: var(--clr-surface-2);
        border: 1px solid var(--clr-border);
        color: var(--clr-text-muted);
        padding: 0.6rem 1.25rem;
        border-radius: var(--radius-full);
        font-family: var(--font-heading);
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
        transition: var(--transition);
        outline: none;
    }
    .filter-pill:hover {
        color: #fff;
        border-color: rgba(255,255,255,0.2);
    }
    .filter-pill.active {
        background: var(--clr-primary);
        color: #000;
        border-color: var(--clr-primary);
        box-shadow: 0 4px 15px rgba(188, 163, 116, 0.25);
    }

    .cafe-card {
        transition: var(--transition);
    }
    .cafe-card:hover {
        transform: translateY(-4px);
        border-color: var(--clr-primary);
        box-shadow: 0 8px 25px rgba(188, 163, 116, 0.15);
    }
    .cafe-card:hover .product-image {
        transform: scale(1.08);
    }
    .add-to-cart-btn:hover {
        background: var(--clr-primary) !important;
        color: #000 !important;
        border-color: var(--clr-primary) !important;
    }
    
    #floatingCartBtn:hover {
        transform: scale(1.05);
    }
    #floatingCartBtn:active {
        transform: scale(0.95);
    }
    @media (min-width: 769px) {
        #floatingCartBtn {
            bottom: 2rem;
            right: 2rem;
        }
    }

    .cart-item {
        display: flex; gap: 1rem; align-items: center; padding-bottom: 1rem; border-bottom: 1px solid var(--clr-border-dark);
    }
    .cart-item:last-child {
        border-bottom: none; padding-bottom: 0;
    }
    .qty-btn {
        background: var(--clr-surface-3); border: 1px solid var(--clr-border); color: #fff; width: 28px; height: 28px; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-weight: bold; transition: var(--transition);
    }
    .qty-btn:hover { background: var(--clr-primary); color: #000; border-color: var(--clr-primary); }
</style>
@endpush

@push('scripts')
<script>
    function filterCategory(slug) {
        document.querySelectorAll('.filter-pill').forEach(pill => {
            pill.classList.remove('active');
        });
        
        if (slug === 'all') {
            const allPill = document.querySelector('.category-filter-wrapper button');
            if (allPill) allPill.classList.add('active');
            
            document.querySelectorAll('.category-section').forEach(section => {
                section.style.display = 'block';
            });
        } else {
            const activePill = document.querySelector(`.filter-pill[data-slug="${slug}"]`);
            if (activePill) activePill.classList.add('active');
            
            document.querySelectorAll('.category-section').forEach(section => {
                if (section.getAttribute('data-category-slug') === slug) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        }
    }

    let cart = JSON.parse(localStorage.getItem('cinevora_cafe_cart')) || {};

    function saveCart() {
        localStorage.setItem('cinevora_cafe_cart', JSON.stringify(cart));
        renderCart();
    }

    function addToCart(id, name, price, icon) {
        if (!cart[id]) {
            cart[id] = { id, name, price, icon, quantity: 1 };
        } else {
            if (cart[id].quantity < 10) cart[id].quantity++;
        }
        saveCart();
        
        // Show cart floating button animation
        const btn = document.getElementById('floatingCartBtn');
        btn.style.transform = 'scale(1.2)';
        setTimeout(() => btn.style.transform = 'scale(1)', 200);
    }

    function updateQty(id, delta) {
        if (!cart[id]) return;
        cart[id].quantity += delta;
        if (cart[id].quantity <= 0) {
            delete cart[id];
        } else if (cart[id].quantity > 10) {
            cart[id].quantity = 10;
        }
        saveCart();
    }

    function toggleCart() {
        const offcanvas = document.getElementById('cartOffcanvas');
        const overlay = document.getElementById('cartOverlay');
        const isOpen = offcanvas.style.right === '0px';
        
        if (isOpen) {
            offcanvas.style.right = '-400px';
            overlay.style.opacity = '0';
            overlay.style.visibility = 'hidden';
        } else {
            offcanvas.style.right = '0px';
            overlay.style.visibility = 'visible';
            overlay.style.opacity = '1';
        }
    }

    function formatRupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    function renderCart() {
        const container = document.getElementById('cartItemsContainer');
        const totalEl = document.getElementById('cartTotal');
        const countBadge = document.getElementById('cartCountBadge');
        const floatingBtn = document.getElementById('floatingCartBtn');
        
        let total = 0;
        let count = 0;
        let html = '';

        for (const id in cart) {
            const item = cart[id];
            const subtotal = item.price * item.quantity;
            total += subtotal;
            count += item.quantity;

            html += `
                <div class="cart-item">
                    <div style="font-size: 2rem; background: var(--clr-surface-2); border-radius: 8px; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--clr-border);">
                        ${item.icon}
                    </div>
                    <div style="flex: 1;">
                        <h4 style="font-family: var(--font-heading); font-size: 0.95rem; margin-bottom: 0.2rem; color: #fff;">${item.name}</h4>
                        <div style="color: var(--clr-primary); font-weight: 700; font-size: 0.85rem;">${formatRupiah(item.price)}</div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <button onclick="updateQty(${id}, -1)" class="qty-btn">-</button>
                        <span style="font-weight: 800; width: 20px; text-align: center; font-size: 0.9rem;">${item.quantity}</span>
                        <button onclick="updateQty(${id}, 1)" class="qty-btn">+</button>
                    </div>
                </div>
            `;
        }

        if (count === 0) {
            html = `
                <div style="text-align: center; padding: 3rem 0; color: var(--clr-text-muted);">
                    <i class='bx bx-shopping-bag' style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                    <p style="font-weight: 600;">Keranjang masih kosong.</p>
                </div>
            `;
            floatingBtn.style.display = 'none';
        } else {
            floatingBtn.style.display = 'flex';
        }

        container.innerHTML = html;
        totalEl.innerHTML = formatRupiah(total);
        countBadge.innerHTML = count;
        
        document.getElementById('checkoutBtn').disabled = count === 0;
    }

    async function checkoutCart() {
        const cinemaId = document.getElementById('cinemaSelect').value;
        const btn = document.getElementById('checkoutBtn');
        
        if (Object.keys(cart).length === 0) return;
        
        if (!cinemaId) {
            alert('Silakan pilih lokasi pengambilan (Bioskop) terlebih dahulu.');
            return;
        }

        @guest
            alert('Anda harus login terlebih dahulu untuk memesan.');
            window.location.href = "{{ route('login') }}";
            return;
        @endguest

        const items = Object.values(cart).map(i => ({
            product_id: i.id,
            quantity: i.quantity
        }));

        btn.innerHTML = 'Memproses... <i class="bx bx-loader-alt bx-spin"></i>';
        btn.disabled = true;

        try {
            const res = await fetch("{{ route('cafe.checkout') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ cinema_id: cinemaId, items: items })
            });

            const data = await res.json();
            
            if (res.ok && data.success) {
                // Clear cart
                cart = {};
                localStorage.removeItem('cinevora_cafe_cart');
                window.location.href = data.redirect_url;
            } else {
                alert('Gagal: ' + (data.message || 'Terjadi kesalahan saat memproses pesanan.'));
                btn.innerHTML = 'LANJUTKAN PEMBAYARAN';
                btn.disabled = false;
            }
        } catch (error) {
            alert('Gagal terhubung ke server.');
            btn.innerHTML = 'LANJUTKAN PEMBAYARAN';
            btn.disabled = false;
        }
    }

    // Initialize
    renderCart();
</script>
@endpush
