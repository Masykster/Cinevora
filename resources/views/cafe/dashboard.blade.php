@extends('layouts.cafe')
@section('title', 'Cafe Order Queue')

@section('content')
{{-- STATUS TABS --}}
<div style="display: flex; gap: 0.5rem; margin-bottom: 2rem; flex-wrap: wrap;">
    @foreach(['all' => 'Semua', 'pending' => '⏳ Pending', 'preparing' => '🍳 Preparing', 'ready' => '✅ Ready', 'completed' => '📦 Completed'] as $key => $label)
        <a href="?status={{ $key }}" class="btn {{ $status === $key ? 'btn-primary' : 'btn-outline' }} btn-sm">
            {{ $label }}
            @if(isset($counts[$key]) && $counts[$key] > 0)
                <span class="badge badge-accent" style="margin-left: 0.25rem;">{{ $counts[$key] }}</span>
            @endif
        </a>
    @endforeach
</div>

{{-- ORDER CARDS --}}
@if($orders->count() > 0)
    <div class="grid grid-3 gap-3">
        @foreach($orders as $order)
            <div class="card" style="border-left: 4px solid {{ match($order->status) { 'pending' => '#f59e0b', 'preparing' => '#3b82f6', 'ready' => '#10b981', default => '#64748b' } }};">
                <div style="padding: 1.25rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <span style="font-family: monospace; font-size: 0.8rem; font-weight: 600;">
                            {{ $order->transaction->invoice_number }}
                        </span>
                        <span class="badge badge-{{ match($order->status) { 'pending' => 'yellow', 'preparing' => 'blue', 'ready' => 'green', default => 'gray' } }}">
                            {{ $order->status_badge }}
                        </span>
                    </div>

                    <p style="font-weight: 600; margin-bottom: 0.5rem;">
                        👤 {{ $order->transaction->user->name }}
                    </p>

                    {{-- ORDER ITEMS --}}
                    <div style="background: var(--clr-surface-2); border-radius: var(--radius); padding: 0.75rem; margin-bottom: 0.75rem;">
                        @foreach($order->transaction->orderItems as $item)
                            <div style="display: flex; justify-content: space-between; font-size: 0.85rem; {{ !$loop->last ? 'margin-bottom: 0.3rem;' : '' }}">
                                <span>{{ $item->product->name }} <span class="text-muted">×{{ $item->quantity }}</span></span>
                            </div>
                        @endforeach
                    </div>

                    @if($order->notes)
                        <p class="text-muted text-xs" style="margin-bottom: 0.5rem;">📝 {{ $order->notes }}</p>
                    @endif

                    <p class="text-muted text-xs">{{ $order->created_at->diffForHumans() }}</p>

                    {{-- ACTION --}}
                    @if($order->status !== 'completed')
                        <form method="POST" action="{{ route('cafe.orders.advance', $order) }}" style="margin-top: 0.75rem;">
                            @csrf
                            <button type="submit" class="btn btn-{{ match($order->status) { 'pending' => 'accent', 'preparing' => 'primary', 'ready' => '' } }} btn-sm btn-block"
                                style="{{ $order->status === 'ready' ? 'background: var(--clr-success); color: #fff;' : '' }}">
                                {{ match($order->status) { 'pending' => '🍳 Mulai Proses', 'preparing' => '✅ Tandai Siap', 'ready' => '📦 Selesai' } }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="pagination">{{ $orders->withQueryString()->links() }}</div>
@else
    <div class="text-center" style="padding: 4rem 0;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">☕</div>
        <h3 class="font-heading" style="font-weight: 700;">Tidak ada pesanan</h3>
        <p class="text-muted mt-1">Pesanan baru akan muncul di sini setelah customer membayar</p>
    </div>
@endif

{{-- AUTO REFRESH --}}
<script>
    // Poll every 15 seconds for new orders
    setInterval(() => {
        if (document.hidden) return;
        window.location.reload();
    }, 15000);
</script>
@endsection
