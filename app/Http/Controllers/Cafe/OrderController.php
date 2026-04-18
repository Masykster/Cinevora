<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\CafeOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = CafeOrder::with([
            'transaction.user',
            'transaction.orderItems.product',
        ])->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(20);

        // Counts for tabs
        $counts = [
            'all' => CafeOrder::count(),
            'pending' => CafeOrder::where('status', 'pending')->count(),
            'preparing' => CafeOrder::where('status', 'preparing')->count(),
            'ready' => CafeOrder::where('status', 'ready')->count(),
            'completed' => CafeOrder::where('status', 'completed')->count(),
        ];

        return view('cafe.dashboard', compact('orders', 'status', 'counts'));
    }

    /**
     * Advance order to next status.
     */
    public function advanceStatus(CafeOrder $cafeOrder)
    {
        $cafeOrder->advanceStatus();

        // TODO: Broadcast event for real-time notification via Laravel Reverb
        // event(new CafeOrderStatusChanged($cafeOrder));

        return back()->with('success', "Pesanan #{$cafeOrder->transaction->invoice_number} status diubah ke: {$cafeOrder->status_badge}");
    }

    /**
     * Get orders data for real-time polling (JSON).
     */
    public function apiOrders(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = CafeOrder::with([
            'transaction.user',
            'transaction.orderItems.product',
        ])->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->take(50)->get();

        $counts = [
            'pending' => CafeOrder::where('status', 'pending')->count(),
            'preparing' => CafeOrder::where('status', 'preparing')->count(),
            'ready' => CafeOrder::where('status', 'ready')->count(),
        ];

        return response()->json([
            'orders' => $orders,
            'counts' => $counts,
        ]);
    }
}
