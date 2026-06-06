@extends('layouts.app')
@section('title', 'My m.tix - Cinevora')

@section('content')
<section style="background: linear-gradient(180deg, var(--clr-surface-2) 0%, var(--clr-bg) 100%); padding: 3rem 0; border-bottom: 1px solid var(--clr-border);">
    <div class="container" style="max-width: 1024px; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: center; justify-content: center;">
        
        {{-- USER PROFILE INFO --}}
        <div style="display: flex; gap: 1.5rem; align-items: center;">
            <div style="width: 80px; height: 80px; background: var(--clr-surface-3); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; font-weight: 800; border: 2px solid var(--clr-primary); color: var(--clr-primary); box-shadow: 0 0 20px rgba(188, 163, 116, 0.25); flex-shrink: 0;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="font-heading" style="font-size: 1.8rem; font-weight: 800; color: #fff; margin-bottom: 0.35rem; text-transform: uppercase;">{{ $user->name }}</h2>
                <p style="font-size: 0.85rem; color: var(--clr-text-muted); display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.25rem;">
                    <i class='bx bx-envelope'></i> {{ $user->email }}
                </p>
                <p style="font-size: 0.85rem; color: var(--clr-text-muted); display: flex; align-items: center; gap: 0.35rem;">
                    <i class='bx bx-phone'></i> {{ $user->phone ?? 'Belum mengisi nomor telepon' }}
                </p>
            </div>
        </div>

        {{-- WALLET BALANCE CARD (m.tix style) --}}
        <div class="wallet-card" style="background: linear-gradient(135deg, #1f170f 0%, #120e09 100%); border: 1px solid var(--clr-primary); border-radius: var(--radius-lg); padding: 1.5rem 1.75rem; box-shadow: 0 8px 24px rgba(188,163,116,0.1); position: relative; overflow: hidden; display: flex; justify-content: space-between; align-items: center; min-height: 120px;">
            <div style="position: absolute; right: -20px; top: -20px; font-size: 6rem; opacity: 0.05; pointer-events: none;">💳</div>
            <div>
                <span style="font-family: var(--font-heading); font-size: 0.75rem; font-weight: 800; color: var(--clr-primary); text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 0.25rem;">SALDO CINEVORA</span>
                <h3 class="font-heading" style="font-size: 2.2rem; font-weight: 800; color: #fff; line-height: 1;">Rp {{ number_format($user->balance, 0, ',', '.') }}</h3>
                <span style="font-size: 0.75rem; color: var(--clr-text-muted); font-weight: 600; display: block; margin-top: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">⭐ Poin: {{ number_format($user->balance / 1000, 0, ',', '.') }} Poin</span>
            </div>
            <div>
                <button onclick="openTopUpModal()" class="btn btn-primary btn-sm" style="border-radius: 4px; font-weight: 800; padding: 0.5rem 1.25rem; font-size: 0.75rem; color: #000;">
                    ➕ TOP UP
                </button>
            </div>
        </div>
        
    </div>
</section>

<section class="section" style="padding-top: 2rem;">
    <div class="container" style="max-width: 800px; margin: 0 auto;">
        
        {{-- TAB NAVIGATION --}}
        <div class="profile-tabs" style="display: flex; border-bottom: 1px solid var(--clr-border); margin-bottom: 2.5rem; gap: 0.5rem;">
            <button class="profile-tab-btn active" id="tabBtnActive" onclick="switchTab('active')">
                🎟️ Tiket Aktif
            </button>
            <button class="profile-tab-btn" id="tabBtnHistory" onclick="switchTab('history')">
                📜 Riwayat Transaksi
            </button>
            <button class="profile-tab-btn" id="tabBtnSettings" onclick="switchTab('settings')">
                ⚙️ Pengaturan
            </button>
        </div>

        {{-- TAB CONTENT 1: ACTIVE TICKETS --}}
        <div id="tabContentActive" class="tab-pane active">
            @php 
                $activeTxs = $transactions->filter(fn($tx) => $tx->status === 'paid' || $tx->status === 'pending');
            @endphp

            @if($activeTxs->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    @foreach($activeTxs as $tx)
                        @php $firstTicket = $tx->tickets->first(); @endphp
                        <div class="card profile-tx-card" style="border: 1px solid var(--clr-border); background: var(--clr-surface); box-shadow: 0 4px 15px rgba(0,0,0,0.5); border-radius: var(--radius-lg); overflow: hidden;">
                            <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--clr-border); background: var(--clr-surface-2); display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.75rem; color: var(--clr-text-muted); font-weight: 700; font-family: monospace;">INV: {{ $tx->invoice_number }}</span>
                                <span class="badge" style="font-size: 0.65rem; font-weight: 800; letter-spacing: 0.5px; background: {{ $tx->status === 'paid' ? 'rgba(16,185,129,0.15)' : 'rgba(188,163,116,0.15)' }}; color: {{ $tx->status === 'paid' ? '#10B981' : 'var(--clr-primary)' }}; border: 1px solid {{ $tx->status === 'paid' ? '#10B981' : 'var(--clr-primary)' }}; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">
                                    {{ $tx->status }}
                                </span>
                            </div>
                            <div style="padding: 1.25rem; display: flex; gap: 1.25rem; align-items: center;">
                                <div style="width: 50px; height: 72px; background: var(--clr-primary-dim); border: 1px solid var(--clr-border); border-radius: 4px; display: flex; justify-content: center; align-items: center; font-size: 2rem; flex-shrink: 0;">
                                    🎬
                                </div>
                                <div style="flex: 1;">
                                    @if($firstTicket)
                                        <h4 style="font-size: 1.1rem; font-weight: 800; color: #fff; line-height: 1.3;">{{ $firstTicket->schedule->movie->title }}</h4>
                                        <p style="font-size: 0.8rem; color: var(--clr-text-muted); margin-top: 0.25rem; font-weight: 500;">
                                            📍 {{ $firstTicket->schedule->studio->cinema->name }} • {{ $firstTicket->schedule->studio->name }}
                                        </p>
                                        <p style="font-size: 0.8rem; color: #fff; font-weight: 700; margin-top: 0.25rem;">
                                            📅 {{ $firstTicket->schedule->show_date->format('d M Y') }} • 🕐 {{ $firstTicket->schedule->show_time_formatted }}
                                        </p>
                                    @else
                                        <h4 style="font-size: 1.1rem; font-weight: 800; color: #fff;">Pemesanan F&B Kafe</h4>
                                        <p style="font-size: 0.8rem; color: var(--clr-text-muted); margin-top: 0.25rem;">📍 {{ $tx->cafeOrder->cinema->name ?? 'Bioskop Cinevora' }}</p>
                                    @endif
                                </div>
                                <div style="text-align: right;">
                                    <span style="font-size: 0.75rem; color: var(--clr-text-muted); font-weight: 600; display: block;">Total Bayar</span>
                                    <span style="font-size: 1.1rem; font-weight: 800; color: var(--clr-primary);">{{ $tx->formatted_grand_total }}</span>
                                </div>
                            </div>
                            <div style="padding: 0.75rem 1.25rem; border-top: 1px solid var(--clr-border); display: flex; justify-content: flex-end; background: var(--clr-surface-2);">
                                @if($tx->status === 'paid')
                                    <a href="{{ route('checkout.invoice', $tx) }}" class="btn btn-primary btn-sm" style="font-size: 0.75rem; font-weight: 800; padding: 0.4rem 1rem; border-radius: 4px; color: #000; text-transform: uppercase;">Lihat E-Ticket <i class='bx bx-qr-scan' style="vertical-align: middle; margin-left: 2px;"></i></a>
                                @elseif($tx->status === 'pending')
                                    <a href="{{ route('checkout.index', $tx) }}" class="btn btn-outline btn-sm" style="font-size: 0.75rem; font-weight: 800; padding: 0.4rem 1rem; border-radius: 4px; text-transform: uppercase;">Lanjut Bayar <i class='bx bx-credit-card' style="vertical-align: middle; margin-left: 2px;"></i></a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="padding: 4rem 1rem; text-align: center; border: 1px dashed var(--clr-border); border-radius: var(--radius-lg); background: var(--clr-surface);">
                    <i class='bx bx-ticket' style="font-size: 3.5rem; color: var(--clr-text-muted); margin-bottom: 1rem; display: block;"></i>
                    <p style="font-weight: 800; color: #fff; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 0.5px;">Belum Ada Tiket Aktif</p>
                    <p style="font-size: 0.85rem; color: var(--clr-text-muted); margin-top: 0.25rem;">Tiket bioskop yang baru Anda beli akan muncul di sini.</p>
                    <a href="{{ route('movies.index') }}" class="btn btn-outline btn-sm mt-3" style="border-radius: 4px; font-weight: 700; text-transform: uppercase;">Beli Tiket Sekarang</a>
                </div>
            @endif
        </div>

        {{-- TAB CONTENT 2: TRANSACTION HISTORY --}}
        <div id="tabContentHistory" class="tab-pane">
            @if($transactions->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @foreach($transactions as $tx)
                        @php $firstTicket = $tx->tickets->first(); @endphp
                        <div class="card profile-tx-card" style="border: 1px solid var(--clr-border); background: var(--clr-surface); border-radius: var(--radius-lg); overflow: hidden; opacity: {{ $tx->status === 'cancelled' || $tx->status === 'failed' ? '0.7' : '1' }}">
                            <div style="padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--clr-border); background: var(--clr-surface-2); display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.75rem; color: var(--clr-text-muted); font-weight: 700; font-family: monospace;">INV: {{ $tx->invoice_number }}</span>
                                <span class="badge" style="font-size: 0.65rem; font-weight: 800; letter-spacing: 0.5px; background: {{ $tx->status === 'paid' ? 'rgba(16,185,129,0.15)' : ($tx->status === 'pending' ? 'rgba(188,163,116,0.15)' : 'rgba(239,68,68,0.15)') }}; color: {{ $tx->status === 'paid' ? '#10B981' : ($tx->status === 'pending' ? 'var(--clr-primary)' : '#EF4444') }}; border: 1px solid {{ $tx->status === 'paid' ? '#10B981' : ($tx->status === 'pending' ? 'var(--clr-primary)' : '#EF4444') }}; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">
                                    {{ $tx->status }}
                                </span>
                            </div>
                            <div style="padding: 1.25rem; display: flex; gap: 1.25rem; align-items: center;">
                                <div style="width: 45px; height: 60px; background: var(--clr-surface-2); border: 1px solid var(--clr-border); border-radius: 4px; display: flex; justify-content: center; align-items: center; font-size: 1.6rem; flex-shrink: 0;">
                                    🎬
                                </div>
                                <div style="flex: 1;">
                                    @if($firstTicket)
                                        <h4 style="font-size: 1rem; font-weight: 800; color: #fff; line-height: 1.3;">{{ $firstTicket->schedule->movie->title }}</h4>
                                        <p style="font-size: 0.75rem; color: var(--clr-text-muted); margin-top: 0.25rem;">
                                            {{ $firstTicket->schedule->show_date->format('d M Y') }} • {{ $firstTicket->schedule->studio->cinema->name }}
                                        </p>
                                    @else
                                        <h4 style="font-size: 1rem; font-weight: 800; color: #fff;">Pesanan F&B Kafe</h4>
                                        <p style="font-size: 0.75rem; color: var(--clr-text-muted); margin-top: 0.25rem;">{{ $tx->created_at->format('d M Y') }}</p>
                                    @endif
                                </div>
                                <div style="text-align: right;">
                                    <span style="font-size: 0.95rem; font-weight: 800; color: var(--clr-primary);">{{ $tx->formatted_grand_total }}</span>
                                    <span style="font-size: 0.65rem; color: var(--clr-text-muted); display: block; margin-top: 0.1rem;">via {{ $tx->payment_method ?? 'Gateway' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($transactions->hasPages())
                    <div style="margin-top: 2.5rem; display: flex; justify-content: center;">
                        {{ $transactions->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            @else
                <div style="padding: 4rem 1rem; text-align: center; border: 1px dashed var(--clr-border); border-radius: var(--radius-lg); background: var(--clr-surface);">
                    <i class='bx bx-receipt' style="font-size: 3.5rem; color: var(--clr-text-muted); margin-bottom: 1rem; display: block;"></i>
                    <p style="font-weight: 800; color: #fff; font-size: 1.1rem; text-transform: uppercase;">Belum Ada Transaksi</p>
                </div>
            @endif
        </div>

        {{-- TAB CONTENT 3: SETTINGS --}}
        <div id="tabContentSettings" class="tab-pane">
            <div class="card" style="border: 1px solid var(--clr-border); background: var(--clr-surface); box-shadow: 0 4px 15px rgba(0,0,0,0.5); border-radius: var(--radius-lg); overflow: hidden;">
                
                {{-- Edit Profile --}}
                <div class="accordion-item">
                    <div class="accordion-header" onclick="toggleAccordion('editProfile')">
                        <div style="display: flex; align-items: center; gap: 0.8rem;">
                            <i class='bx bx-user' style="font-size: 1.3rem; color: var(--clr-primary);"></i>
                            <span style="font-weight: 700; font-size: 0.95rem; color: #fff;">Ubah Data Profil</span>
                        </div>
                        <i class='bx bx-chevron-down text-muted accordion-arrow' id="arrow-editProfile" style="font-size: 1.2rem; transition: transform 0.2s;"></i>
                    </div>
                    <div id="accordion-body-editProfile" class="accordion-body">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf @method('PUT')
                            <div class="form-group">
                                <label class="form-label" for="name">Nama Lengkap</label>
                                <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required style="border-radius: 4px;">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Alamat Email (Tidak dapat diubah)</label>
                                <input type="email" class="form-input" value="{{ $user->email }}" disabled style="opacity: 0.5; background: #000; cursor: not-allowed; border-color: var(--clr-border); border-radius: 4px;">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="phone">Nomor Telepon</label>
                                <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" style="border-radius: 4px;">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm mt-2" style="border-radius:4px; font-weight:700; color:#000;">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>

                {{-- Edit Password --}}
                <div class="accordion-item" style="border-top: 1px solid var(--clr-border);">
                    <div class="accordion-header" onclick="toggleAccordion('editPassword')">
                        <div style="display: flex; align-items: center; gap: 0.8rem;">
                            <i class='bx bx-lock-alt' style="font-size: 1.3rem; color: var(--clr-primary);"></i>
                            <span style="font-weight: 700; font-size: 0.95rem; color: #fff;">Ubah Kata Sandi (Password)</span>
                        </div>
                        <i class='bx bx-chevron-down text-muted accordion-arrow' id="arrow-editPassword" style="font-size: 1.2rem; transition: transform 0.2s;"></i>
                    </div>
                    <div id="accordion-body-editPassword" class="accordion-body">
                        <form method="POST" action="{{ route('profile.updatePassword') }}">
                            @csrf @method('PUT')
                            <div class="form-group">
                                <label class="form-label" for="current_password">Password Saat Ini</label>
                                <input type="password" id="current_password" name="current_password" class="form-input" required style="border-radius: 4px;">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="password">Password Baru</label>
                                <input type="password" id="password" name="password" class="form-input" required style="border-radius: 4px;">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required style="border-radius: 4px;">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm mt-2" style="border-radius:4px; font-weight:700; color:#000;">Simpan Password</button>
                        </form>
                    </div>
                </div>
                
                {{-- Logout --}}
                <div style="padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--clr-border);">
                    <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                        @csrf
                        <button type="submit" style="background: none; border: none; width: 100%; display: flex; align-items: center; justify-content: space-between; color: var(--clr-error); cursor: pointer; padding: 0;">
                            <div style="display: flex; align-items: center; gap: 0.8rem;">
                                <i class='bx bx-log-out' style="font-size: 1.3rem;"></i>
                                <span style="font-weight: 700; font-size: 0.95rem;">Keluar Dari Akun</span>
                            </div>
                            <i class='bx bx-chevron-right' style="font-size: 1.2rem;"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- TOP UP MODAL --}}
<div id="topUpModal" style="display: none; position: fixed; inset: 0; z-index: 1000; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="card" style="width: 100%; max-width: 420px; background: var(--clr-surface); border: 1px solid var(--clr-border); box-shadow: 0 10px 40px rgba(0,0,0,0.8); border-radius: var(--radius-lg); overflow: hidden;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--clr-border); display: flex; justify-content: space-between; align-items: center; background: var(--clr-surface-2);">
            <h3 class="font-heading" style="font-weight: 800; font-size: 1.1rem; color: #fff; text-transform: uppercase; letter-spacing: 0.5px;">💰 Top Up Saldo Cinevora</h3>
            <button onclick="closeTopUpModal()" class="btn btn-ghost btn-sm" style="color: var(--clr-text-muted); font-size: 1.3rem; padding: 0;">✕</button>
        </div>
        <form method="POST" action="{{ route('profile.topup') }}" style="padding: 1.5rem;">
            @csrf
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label">Pilih Nominal Top Up</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; margin-bottom: 1rem;">
                    <button type="button" class="btn btn-outline btn-sm" style="border-radius: 4px;" onclick="setTopUpAmount(50000)">Rp 50.000</button>
                    <button type="button" class="btn btn-outline btn-sm" style="border-radius: 4px;" onclick="setTopUpAmount(100000)">Rp 100.000</button>
                    <button type="button" class="btn btn-outline btn-sm" style="border-radius: 4px;" onclick="setTopUpAmount(200000)">Rp 200.000</button>
                    <button type="button" class="btn btn-outline btn-sm" style="border-radius: 4px;" onclick="setTopUpAmount(500000)">Rp 500.000</button>
                </div>
                <label class="form-label" for="topup_amount_input">Atau Masukkan Nominal Kustom (Min Rp 10.000)</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); font-weight: 700; color: #fff; font-size: 0.9rem;">Rp</span>
                    <input type="number" id="topup_amount_input" name="amount" class="form-input" min="10000" max="10000000" required placeholder="0" style="padding-left: 2.5rem; font-weight: 700; font-size: 1.1rem; border-radius: 4px; height: 46px;">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" style="padding: 0.85rem; border-radius: 4px; font-weight: 800; text-transform: uppercase; color: #000;">PROSES TOP UP</button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-tab-btn {
        background: transparent;
        border: none;
        outline: none;
        color: var(--clr-text-muted);
        font-family: var(--font-heading);
        font-size: 1.05rem;
        font-weight: 700;
        padding: 0.75rem 1.25rem;
        cursor: pointer;
        transition: var(--transition);
        border-bottom: 2px solid transparent;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .profile-tab-btn:hover {
        color: #fff;
    }
    
    .profile-tab-btn.active {
        color: var(--clr-primary);
        border-bottom-color: var(--clr-primary);
    }
    
    .tab-pane {
        display: none;
    }
    
    .tab-pane.active {
        display: block;
        animation: fadeIn 0.3s ease-out;
    }
    
    .profile-tx-card {
        transition: var(--transition);
    }
    
    .profile-tx-card:hover {
        border-color: var(--clr-primary) !important;
    }
    
    .accordion-header {
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
        transition: var(--transition);
    }
    .accordion-header:hover {
        background: var(--clr-surface-2);
    }
    
    .accordion-body {
        padding: 0 1.5rem;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s cubic-bezier(0, 1, 0, 1), padding 0.3s ease;
        background: var(--clr-surface-2);
    }
    
    .accordion-body.open {
        max-height: 500px;
        padding: 1.5rem;
        transition: max-height 0.3s cubic-bezier(1, 0, 1, 0), padding 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @media (max-width: 768px) {
        section .container {
            grid-template-columns: 1fr !important;
            gap: 1.5rem !important;
        }
        .profile-tabs {
            flex-direction: row;
            overflow-x: auto;
            scrollbar-width: none;
        }
        .profile-tabs::-webkit-scrollbar {
            display: none;
        }
        .profile-tab-btn {
            font-size: 0.85rem;
            padding: 0.6rem 0.85rem;
            white-space: nowrap;
        }
    }
    
    /* Override bootstrap pagination to match dark theme */
    .pagination { display: flex; list-style: none; gap: 0.4rem; padding: 0; }
    .page-item .page-link {
        display: flex; align-items: center; justify-content: center;
        width: 36px; height: 36px; border-radius: 4px;
        background: var(--clr-surface-2); color: var(--clr-text);
        border: 1px solid var(--clr-border);
        text-decoration: none; font-size: 0.9rem; font-weight: 600;
        transition: var(--transition);
    }
    .page-item .page-link:hover {
        border-color: var(--clr-primary);
        color: var(--clr-primary);
    }
    .page-item.active .page-link {
        background: var(--clr-primary); color: #000000; border-color: var(--clr-primary);
    }
    .page-item.disabled .page-link {
        color: var(--clr-text-muted); background: var(--clr-surface); border-color: var(--clr-border); opacity: 0.5; cursor: not-allowed;
    }
</style>
@endpush

@push('scripts')
<script>
function switchTab(tabName) {
    // Hide all tab panes
    document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
    // Deactivate all tab buttons
    document.querySelectorAll('.profile-tab-btn').forEach(btn => btn.classList.remove('active'));
    
    // Show selected tab pane
    if (tabName === 'active') {
        document.getElementById('tabContentActive').classList.add('active');
        document.getElementById('tabBtnActive').classList.add('active');
    } else if (tabName === 'history') {
        document.getElementById('tabContentHistory').classList.add('active');
        document.getElementById('tabBtnHistory').classList.add('active');
    } else if (tabName === 'settings') {
        document.getElementById('tabContentSettings').classList.add('active');
        document.getElementById('tabBtnSettings').classList.add('active');
    }
}

function toggleAccordion(accordionId) {
    const body = document.getElementById('accordion-body-' + accordionId);
    const arrow = document.getElementById('arrow-' + accordionId);
    const isOpen = body.classList.contains('open');
    
    // Close other accordions
    document.querySelectorAll('.accordion-body').forEach(b => b.classList.remove('open'));
    document.querySelectorAll('.accordion-arrow').forEach(a => a.style.transform = 'rotate(0deg)');
    
    if (!isOpen) {
        body.classList.add('open');
        arrow.style.transform = 'rotate(180deg)';
    }
}

// Modal handling
function openTopUpModal() {
    document.getElementById('topUpModal').style.display = 'flex';
}
function closeTopUpModal() {
    document.getElementById('topUpModal').style.display = 'none';
}
function setTopUpAmount(amount) {
    document.getElementById('topup_amount_input').value = amount;
}

// Close modal on background click
document.getElementById('topUpModal').addEventListener('click', function(e) {
    if (e.target === this) closeTopUpModal();
});

// Check URL param tab on load
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    if (tab === 'balance') {
        openTopUpModal();
    } else if (tab === 'history') {
        switchTab('history');
    } else if (tab === 'settings') {
        switchTab('settings');
    }
});
</script>
@endpush
