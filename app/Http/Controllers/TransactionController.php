<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\CafeOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Show checkout page for a transaction.
     */
    public function index(Transaction $transaction)
    {
        $this->authorize($transaction);

        $transaction->load(['tickets.schedule.movie', 'tickets.seat', 'tickets.schedule.studio.cinema', 'orderItems.product', 'voucher']);

        $products = Product::available()->with('category')->get();

        return view('checkout.index', compact('transaction', 'products'));
    }

    /**
     * Add F&B items to the transaction.
     */
    public function addFnb(Request $request, Transaction $transaction)
    {
        $this->authorize($transaction);

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:10',
        ]);

        DB::transaction(function () use ($validated, $transaction) {
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];

                // Update or create order item
                $existingItem = $transaction->orderItems()
                    ->where('product_id', $item['product_id'])
                    ->first();

                if ($existingItem) {
                    $existingItem->update([
                        'quantity' => $existingItem->quantity + $item['quantity'],
                        'subtotal' => $existingItem->subtotal + $subtotal,
                    ]);
                } else {
                    OrderItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ]);
                }
            }

            $this->recalculateTotal($transaction);
        });

        return back()->with('success', 'Item F&B berhasil ditambahkan.');
    }

    /**
     * Remove an F&B item from the transaction.
     */
    public function removeFnb(Transaction $transaction, OrderItem $orderItem)
    {
        $this->authorize($transaction);

        if ($orderItem->transaction_id !== $transaction->id) {
            abort(403);
        }

        $orderItem->delete();
        $this->recalculateTotal($transaction);

        return back()->with('success', 'Item berhasil dihapus.');
    }

    /**
     * Apply a voucher to the transaction.
     */
    public function applyVoucher(Request $request, Transaction $transaction)
    {
        $this->authorize($transaction);

        $validated = $request->validate([
            'voucher_code' => 'required|string',
        ]);

        $voucher = Voucher::where('code', strtoupper($validated['voucher_code']))->first();

        if (!$voucher) {
            return back()->with('error', 'Kode voucher tidak ditemukan.');
        }

        if (!$voucher->isValid()) {
            return back()->with('error', 'Voucher sudah tidak berlaku atau kuota habis.');
        }

        // Determine applicable amount based on voucher target
        $applicableAmount = match ($voucher->target) {
            'ticket' => $transaction->tickets->sum('price'),
            'fnb' => $transaction->orderItems->sum('subtotal'),
            'all' => $transaction->total,
        };

        if (!$voucher->isApplicable($voucher->target, $applicableAmount)) {
            return back()->with('error', 'Voucher tidak memenuhi syarat minimum pembelian.');
        }

        $discount = $voucher->calculateDiscount($applicableAmount);

        $transaction->update([
            'voucher_id' => $voucher->id,
            'discount' => $discount,
            'grand_total' => $transaction->total - $discount,
        ]);

        return back()->with('success', 'Voucher berhasil diaplikasikan! Diskon: Rp ' . number_format($discount, 0, ',', '.'));
    }

    /**
     * Remove voucher from transaction.
     */
    public function removeVoucher(Transaction $transaction)
    {
        $this->authorize($transaction);

        $transaction->update([
            'voucher_id' => null,
            'discount' => 0,
            'grand_total' => $transaction->total,
        ]);

        return back()->with('success', 'Voucher berhasil dihapus.');
    }

    /**
     * Process payment (mock).
     */
    public function pay(Transaction $transaction)
    {
        $this->authorize($transaction);

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses.');
        }

        DB::transaction(function () use ($transaction) {
            $transaction->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Increment voucher used count
            if ($transaction->voucher_id) {
                $transaction->voucher->increment('used_count');
            }

            // Create cafe order if there are F&B items
            if ($transaction->orderItems()->count() > 0) {
                CafeOrder::create([
                    'transaction_id' => $transaction->id,
                    'status' => 'pending',
                ]);
            }
        });

        return redirect()->route('checkout.invoice', $transaction)->with('success', 'Pembayaran berhasil!');
    }

    /**
     * Show invoice/e-ticket.
     */
    public function invoice(Transaction $transaction)
    {
        $this->authorize($transaction);

        $transaction->load([
            'tickets.schedule.movie',
            'tickets.schedule.studio.cinema',
            'tickets.seat',
            'orderItems.product',
            'voucher',
            'cafeOrder',
            'user',
        ]);

        return view('checkout.invoice', compact('transaction'));
    }

    /**
     * Cancel a pending transaction.
     */
    public function cancel(Transaction $transaction)
    {
        $this->authorize($transaction);

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Hanya transaksi pending yang bisa dibatalkan.');
        }

        $transaction->update(['status' => 'cancelled']);

        return redirect()->route('home')->with('success', 'Transaksi berhasil dibatalkan.');
    }

    // === Private Helpers ===

    private function authorize(Transaction $transaction): void
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }
    }

    private function recalculateTotal(Transaction $transaction): void
    {
        $transaction->refresh();

        $ticketTotal = $transaction->tickets()->sum('price');
        $fnbTotal = $transaction->orderItems()->sum('subtotal');
        $total = $ticketTotal + $fnbTotal;

        $discount = 0;
        if ($transaction->voucher_id) {
            $voucher = $transaction->voucher;
            $applicableAmount = match ($voucher->target) {
                'ticket' => $ticketTotal,
                'fnb' => $fnbTotal,
                'all' => $total,
            };
            $discount = $voucher->calculateDiscount($applicableAmount);
        }

        $transaction->update([
            'total' => $total,
            'discount' => $discount,
            'grand_total' => max(0, $total - $discount),
        ]);
    }
}
