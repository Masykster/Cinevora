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
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class TransactionController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
    }

    /**
     * Show checkout page for a transaction.
     */
    public function index(Transaction $transaction)
    {
        $this->authorize($transaction);
        $this->checkExpiry($transaction);

        $transaction->load(['tickets.schedule.movie', 'tickets.seat', 'tickets.schedule.studio.cinema', 'orderItems.product', 'voucher']);

        $products = Product::available()
            ->whereHas('category', function ($q) {
                $q->where('is_active', true);
            })
            ->with('category')
            ->get();

        return view('checkout.index', compact('transaction', 'products'));
    }

    /**
     * Add F&B items to the transaction (with stock validation & decrement).
     */
    public function addFnb(Request $request, Transaction $transaction)
    {
        $this->authorize($transaction);
        $this->checkExpiry($transaction);

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:10',
        ]);

        try {
            DB::transaction(function () use ($validated, $transaction) {
                foreach ($validated['items'] as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                    $quantity = $item['quantity'];

                    // Validate stock availability
                    if (!$product->hasStock($quantity)) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi.");
                    }

                    $subtotal = $product->price * $quantity;

                    // Update or create order item
                    $existingItem = $transaction->orderItems()
                        ->where('product_id', $item['product_id'])
                        ->first();

                    if ($existingItem) {
                        // Check if total quantity after addition is still available
                        $additionalQty = $quantity;
                        if ($product->stock > 0 && ($product->stock < $additionalQty)) {
                            throw new \Exception("Stok {$product->name} tidak mencukupi untuk ditambah.");
                        }

                        $existingItem->update([
                            'quantity' => $existingItem->quantity + $quantity,
                            'subtotal' => $existingItem->subtotal + $subtotal,
                        ]);
                    } else {
                        OrderItem::create([
                            'transaction_id' => $transaction->id,
                            'product_id' => $item['product_id'],
                            'quantity' => $quantity,
                            'price' => $product->price,
                            'subtotal' => $subtotal,
                        ]);
                    }

                    // Decrement stock atomically
                    $product->decrementStock($quantity);
                }

                $this->recalculateTotal($transaction);
            });

            return back()->with('success', 'Item F&B berhasil ditambahkan.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove an F&B item from the transaction (restore stock).
     */
    public function removeFnb(Transaction $transaction, OrderItem $orderItem)
    {
        $this->authorize($transaction);

        if ($orderItem->transaction_id !== $transaction->id) {
            abort(403);
        }

        DB::transaction(function () use ($orderItem, $transaction) {
            // Restore stock
            $product = $orderItem->product;
            $product->incrementStock($orderItem->quantity);

            $orderItem->delete();
            $this->recalculateTotal($transaction);
        });

        return back()->with('success', 'Item berhasil dihapus.');
    }

    /**
     * Apply a voucher to the transaction (with per-user check).
     */
    public function applyVoucher(Request $request, Transaction $transaction)
    {
        $this->authorize($transaction);
        $this->checkExpiry($transaction);

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

        // Check per-user usage limit
        if ($voucher->hasBeenUsedBy(Auth::id())) {
            return back()->with('error', 'Anda sudah pernah menggunakan voucher ini.');
        }

        // Determine applicable amount based on voucher target
        $transaction->load(['tickets', 'orderItems']);
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
            'grand_total' => max(0, $transaction->total - $discount),
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
     * Process payment via Xendit Invoice API or Cinevora Balance.
     */
    public function pay(Request $request, Transaction $transaction)
    {
        $this->authorize($transaction);

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses.');
        }

        if ($transaction->isExpired()) {
            $transaction->update(['status' => 'cancelled']);
            return redirect()->route('home')->with('error', 'Transaksi telah kedaluwarsa. Kursi telah dilepas.');
        }

        $paymentMethod = $request->input('payment_method', 'xendit');

        if ($paymentMethod === 'balance') {
            $user = Auth::user();
            if ($user->balance < $transaction->grand_total) {
                return back()->with('error', 'Saldo Cinevora tidak mencukupi. Silakan top up saldo Anda.');
            }

            try {
                DB::transaction(function () use ($transaction, $user) {
                    // Deduct balance
                    $user->decrement('balance', $transaction->grand_total);

                    // Update transaction
                    $transaction->status = 'paid';
                    $transaction->paid_at = now();
                    $transaction->expires_at = null;
                    $transaction->payment_method = 'Cinevora Balance';
                    $transaction->booking_code = Transaction::generateBookingCode();
                    $transaction->save();

                    // Decrement voucher quota
                    if ($transaction->voucher_id) {
                        Voucher::where('id', $transaction->voucher_id)
                            ->where('used_count', '<', DB::raw('quota'))
                            ->increment('used_count');
                    }

                    // Create cafe order if F&B items exist
                    if ($transaction->orderItems()->count() > 0) {
                        CafeOrder::firstOrCreate(
                            ['transaction_id' => $transaction->id],
                            ['status' => 'pending']
                        );
                    }
                });

                return redirect()->route('checkout.invoice', $transaction)->with('success', 'Pembayaran menggunakan Saldo Cinevora berhasil!');
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal memproses pembayaran saldo: ' . $e->getMessage());
            }
        }

        // If already has Xendit invoice, redirect to it
        if ($transaction->xendit_invoice_url) {
            return redirect()->route('checkout.paymentPending', $transaction);
        }

        try {
            $apiInstance = new InvoiceApi();

            $transaction->load(['tickets.schedule.movie', 'user']);
            $movieTitle = $transaction->tickets->first()?->schedule?->movie?->title ?? 'Tiket Bioskop';

            $baseUrl = request()->getSchemeAndHttpHost();

            $createInvoiceRequest = new CreateInvoiceRequest([
                'external_id' => $transaction->invoice_number,
                'amount' => $transaction->grand_total,
                'description' => "Pembayaran Cinevora - {$movieTitle}",
                'invoice_duration' => 600, // 10 minutes
                'currency' => 'IDR',
                'customer' => [
                    'given_names' => $transaction->user->name,
                    'email' => $transaction->user->email,
                ],
                'success_redirect_url' => $baseUrl . '/checkout/' . $transaction->id . '/invoice',
                'failure_redirect_url' => $baseUrl . '/checkout/' . $transaction->id,
            ]);

            $result = $apiInstance->createInvoice($createInvoiceRequest);

            $transaction->update([
                'xendit_invoice_id' => $result->getId(),
                'xendit_invoice_url' => $result->getInvoiceUrl(),
            ]);

            return redirect($result->getInvoiceUrl());

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat invoice pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Show payment pending page.
     */
    public function paymentPending(Transaction $transaction)
    {
        $this->authorize($transaction);

        if ($transaction->status === 'paid') {
            return redirect()->route('checkout.invoice', $transaction);
        }

        $transaction->load(['tickets.schedule.movie', 'tickets.seat', 'tickets.schedule.studio.cinema']);

        return view('checkout.payment-pending', compact('transaction'));
    }

    /**
     * AJAX endpoint to check payment status.
     * Falls back to checking Xendit API directly if webhook was missed.
     */
    public function checkPaymentStatus(Transaction $transaction)
    {
        $this->authorize($transaction);

        $transaction = $transaction->fresh();

        // If still pending and has Xendit invoice, check Xendit API directly
        if ($transaction->status === 'pending' && $transaction->xendit_invoice_id) {
            try {
                $apiInstance = new InvoiceApi();
                $invoice = $apiInstance->getInvoiceById($transaction->xendit_invoice_id);

                if ($invoice->getStatus() === 'PAID') {
                    // Process payment locally (webhook was missed)
                    $transaction->status = 'paid';
                    $transaction->paid_at = now();
                    $transaction->expires_at = null;
                    $transaction->payment_method = $invoice->getPaymentMethod() ?? 'Xendit';
                    $transaction->booking_code = Transaction::generateBookingCode();
                    $transaction->save();

                    // Decrement voucher quota
                    if ($transaction->voucher_id) {
                        Voucher::where('id', $transaction->voucher_id)
                            ->where('used_count', '<', DB::raw('quota'))
                            ->increment('used_count');
                    }

                    // Create cafe order if F&B items exist
                    if ($transaction->orderItems()->count() > 0) {
                        CafeOrder::firstOrCreate(
                            ['transaction_id' => $transaction->id],
                            ['status' => 'pending']
                        );
                    }
                }
            } catch (\Exception $e) {
                // Silently fail — will retry on next poll
            }
        }

        return response()->json([
            'status' => $transaction->status,
        ]);
    }

    /**
     * Show invoice/e-ticket.
     */
    public function invoice(Transaction $transaction)
    {
        $this->authorize($transaction);

        // If not paid yet, redirect to pending page
        if ($transaction->status !== 'paid') {
            if ($transaction->xendit_invoice_url) {
                return redirect()->route('checkout.paymentPending', $transaction);
            }
            return redirect()->route('checkout.index', $transaction);
        }

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
     * Cancel a pending transaction (release seats and stock).
     */
    public function cancel(Transaction $transaction)
    {
        $this->authorize($transaction);

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Hanya transaksi pending yang bisa dibatalkan.');
        }

        DB::transaction(function () use ($transaction) {
            // Restore F&B stock
            foreach ($transaction->orderItems()->with('product')->get() as $item) {
                $item->product->incrementStock($item->quantity);
            }

            $transaction->update([
                'status' => 'cancelled',
                'expires_at' => null,
            ]);
        });

        return redirect()->route('home')->with('success', 'Transaksi berhasil dibatalkan.');
    }

    // === Private Helpers ===

    private function authorize(Transaction $transaction): void
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }
    }

    /**
     * Check if a pending transaction has expired and redirect if so.
     */
    private function checkExpiry(Transaction $transaction): void
    {
        if ($transaction->isExpired()) {
            // Auto-cancel and release stock
            DB::transaction(function () use ($transaction) {
                foreach ($transaction->orderItems()->with('product')->get() as $item) {
                    $item->product->incrementStock($item->quantity);
                }
                $transaction->update(['status' => 'cancelled', 'expires_at' => null]);
            });

            abort(redirect()->route('home')->with('error', 'Transaksi telah kedaluwarsa (10 menit). Silakan booking ulang.'));
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
