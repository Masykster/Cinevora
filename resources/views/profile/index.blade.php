@extends('layouts.app')
@section('title', 'Profil Saya - Cinevora')

@section('content')
<section style="background: var(--clr-surface-2); padding: 3rem 0; border-bottom: 1px solid var(--clr-border);">
    <div class="container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; max-width: 1024px;">
        <div style="width: 80px; height: 80px; background: var(--clr-surface-3); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; margin-bottom: 1rem; border: 2px solid var(--clr-primary); color: var(--clr-primary); box-shadow: 0 0 15px rgba(247, 148, 30, 0.2);">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <h2 class="font-heading" style="font-size: 1.6rem; font-weight: 800; color: #fff; margin-bottom: 0.25rem;">{{ Auth::user()->name }}</h2>
        <p style="font-size: 0.85rem; color: var(--clr-text-muted);">{{ Auth::user()->email }}</p>
        <p style="font-size: 0.85rem; color: var(--clr-text-muted); margin-top: 0.25rem;">📱 {{ Auth::user()->phone ?? 'Belum ada nomor telepon' }}</p>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width: 700px; margin: 0 auto;">
        
        <h3 class="font-heading" style="font-size: 1.1rem; font-weight: 800; margin-bottom: 1.25rem; text-transform: uppercase; color: #fff; letter-spacing: 0.5px; border-left: 3px solid var(--clr-primary); padding-left: 0.5rem;">Pengaturan Akun</h3>
        <div class="card" style="margin-bottom: 3rem; border: 1px solid var(--clr-border); background: var(--clr-surface); box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
            <div style="padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--clr-border); display: flex; align-items: center; justify-content: space-between; cursor: pointer;" onclick="document.getElementById('editProfileForm').toggleAttribute('hidden')">
                <div style="display: flex; align-items: center; gap: 0.8rem;">
                    <i class='bx bx-user' style="font-size: 1.3rem; color: var(--clr-primary);"></i>
                    <span style="font-weight: 700; font-size: 0.95rem; color: #fff;">Ubah Profil</span>
                </div>
                <i class='bx bx-chevron-down text-muted' style="font-size: 1.2rem;"></i>
            </div>
            <div id="editProfileForm" hidden style="padding: 1.5rem; background: var(--clr-surface-2); border-bottom: 1px solid var(--clr-border);">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Email (Tidak dapat diubah)</label>
                        <input type="email" class="form-input" value="{{ $user->email }}" disabled style="opacity: 0.5; background: #000; cursor: not-allowed; border-color: var(--clr-border);">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="phone">Nomor Telepon</label>
                        <input type="text" id="phone" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mt-2" style="border-radius:4px; font-weight:700; text-transform:uppercase;">Simpan Profil</button>
                </form>
            </div>

            <div style="padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--clr-border); display: flex; align-items: center; justify-content: space-between; cursor: pointer;" onclick="document.getElementById('editPasswordForm').toggleAttribute('hidden')">
                <div style="display: flex; align-items: center; gap: 0.8rem;">
                    <i class='bx bx-lock-alt' style="font-size: 1.3rem; color: var(--clr-primary);"></i>
                    <span style="font-weight: 700; font-size: 0.95rem; color: #fff;">Ubah Password</span>
                </div>
                <i class='bx bx-chevron-down text-muted' style="font-size: 1.2rem;"></i>
            </div>
            <div id="editPasswordForm" hidden style="padding: 1.5rem; background: var(--clr-surface-2); border-bottom: 1px solid var(--clr-border);">
                <form method="POST" action="{{ route('profile.updatePassword') }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label class="form-label" for="current_password">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password">Password Baru</label>
                        <input type="password" id="password" name="password" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mt-2" style="border-radius:4px; font-weight:700; text-transform:uppercase;">Simpan Password</button>
                </form>
            </div>
            
            <div style="padding: 1.1rem 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                    @csrf
                    <button type="submit" style="background: none; border: none; width: 100%; display: flex; align-items: center; justify-content: space-between; color: var(--clr-error); cursor: pointer; padding: 0;">
                        <div style="display: flex; align-items: center; gap: 0.8rem;">
                            <i class='bx bx-log-out' style="font-size: 1.3rem;"></i>
                            <span style="font-weight: 700; font-size: 0.95rem;">Keluar Dari Akun</span>
                        </div>
                    </button>
                </form>
            </div>
        </div>

        <h3 class="font-heading" style="font-size: 1.1rem; font-weight: 800; margin-bottom: 1.25rem; text-transform: uppercase; color: #fff; letter-spacing: 0.5px; border-left: 3px solid var(--clr-primary); padding-left: 0.5rem;">Riwayat Transaksi & Tiket</h3>
        
        @if($transactions->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($transactions as $tx)
                    <div class="card tx-card" style="border: 1px solid var(--clr-border); background: var(--clr-surface); box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
                        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--clr-border); background: var(--clr-surface-2); display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.75rem; color: var(--clr-text-muted); font-weight: 700; font-family: monospace;">INV: {{ $tx->invoice_number }}</span>
                            <span class="badge" style="font-size: 0.65rem; font-weight: 800; letter-spacing: 0.5px; background: {{ $tx->status === 'paid' ? 'rgba(16,185,129,0.15)' : ($tx->status === 'pending' ? 'rgba(247,148,30,0.15)' : 'rgba(239,68,68,0.15)') }}; color: {{ $tx->status === 'paid' ? '#10B981' : ($tx->status === 'pending' ? 'var(--clr-primary)' : '#EF4444') }}; border: 1px solid {{ $tx->status === 'paid' ? '#10B981' : ($tx->status === 'pending' ? 'var(--clr-primary)' : '#EF4444') }}; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">
                                {{ $tx->status }}
                            </span>
                        </div>
                        <div style="padding: 1.25rem; display: flex; gap: 1.25rem; align-items: center;">
                            <div style="width: 50px; height: 68px; background: var(--clr-primary-dim); border: 1px solid var(--clr-border); border-radius: 4px; display: flex; justify-content: center; align-items: center; font-size: 1.8rem; box-shadow: 0 4px 10px rgba(0,0,0,0.3); flex-shrink:0;">
                                🎬
                            </div>
                            <div style="flex: 1;">
                                @if($tx->tickets->count() > 0)
                                    <h4 style="font-size: 1rem; font-weight: 800; color: #fff; line-height: 1.3;">{{ $tx->tickets->first()->schedule->movie->title }}</h4>
                                    <p style="font-size: 0.75rem; color: var(--clr-text-muted); margin-top: 0.25rem; font-weight: 500;">
                                        {{ $tx->tickets->first()->schedule->show_date->format('d M Y') }} • {{ $tx->tickets->first()->schedule->show_time_formatted }} • {{ $tx->tickets->first()->schedule->studio->cinema->name }}
                                    </p>
                                @else
                                    <h4 style="font-size: 1rem; font-weight: 800; color: #fff;">Pesanan F&B Kafe</h4>
                                @endif
                                <p style="font-size: 0.9rem; font-weight: 800; color: var(--clr-primary); margin-top: 0.4rem;">
                                    {{ $tx->formatted_grand_total }}
                                </p>
                            </div>
                        </div>
                        @if($tx->status === 'paid' || $tx->status === 'pending')
                        <div style="padding: 0.75rem 1.25rem; border-top: 1px solid var(--clr-border); display: flex; justify-content: flex-end; background: var(--clr-surface-2);">
                            @if($tx->status === 'paid')
                                <a href="{{ route('checkout.invoice', $tx) }}" style="font-size: 0.75rem; font-weight: 800; color: var(--clr-primary); text-decoration: none; text-transform: uppercase; letter-spacing: 0.5px;">Lihat E-Ticket <i class='bx bx-right-arrow-alt' style="font-size: 1rem; vertical-align: middle;"></i></a>
                            @elseif($tx->status === 'pending')
                                <a href="{{ route('checkout.index', $tx) }}" style="font-size: 0.75rem; font-weight: 800; color: var(--clr-accent); text-decoration: none; text-transform: uppercase; letter-spacing: 0.5px;">Lanjut Bayar <i class='bx bx-right-arrow-alt' style="font-size: 1rem; vertical-align: middle;"></i></a>
                            @endif
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
            @if($transactions->hasPages())
                <div style="margin-top: 2rem; display: flex; justify-content: center;">
                    {{ $transactions->links('pagination::bootstrap-4') }}
                </div>
            @endif
        @else
            <div style="padding: 4rem 1rem; text-align: center; border: 1px dashed var(--clr-border); border-radius: var(--radius); background: var(--clr-surface);">
                <i class='bx bx-receipt' style="font-size: 3.5rem; color: var(--clr-text-muted); margin-bottom: 1rem; display: block;"></i>
                <p style="font-weight: 800; color: #fff; font-size: 1.1rem; text-transform: uppercase;">Belum ada transaksi</p>
                <p style="font-size: 0.85rem; color: var(--clr-text-muted); margin-top: 0.25rem;">Mulai pesan tiket bioskop pertamamu sekarang!</p>
                <a href="{{ route('movies.index') }}" class="btn btn-outline btn-sm mt-3" style="border-radius: 4px; font-weight: 700; text-transform: uppercase;">Cari Film</a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .tx-card {
        transition: var(--transition);
    }
    .tx-card:hover {
        border-color: var(--clr-primary);
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
