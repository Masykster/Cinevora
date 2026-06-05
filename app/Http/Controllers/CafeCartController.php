<?php

namespace App\Http\Controllers;

use App\Models\CafeOrder;
use App\Models\Cinema;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CafeCartController extends Controller
{
    /**
     * Process standalone cafe checkout.
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'cinema_id' => 'required|exists:cinemas,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $transaction = DB::transaction(function () use ($validated) {
                // Create the transaction
                $transaction = Transaction::create([
                    'user_id' => Auth::id(),
                    'invoice_number' => Transaction::generateInvoiceNumber(),
                    'total' => 0,
                    'discount' => 0,
                    'grand_total' => 0,
                    'status' => 'pending',
                    'expires_at' => now()->addMinutes(10),
                ]);

                $total = 0;

                // Process items
                foreach ($validated['items'] as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                    $quantity = $item['quantity'];

                    if (!$product->hasStock($quantity)) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi.");
                    }

                    $subtotal = $product->price * $quantity;
                    $total += $subtotal;

                    OrderItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ]);

                    // Decrement stock
                    $product->decrementStock($quantity);
                }

                $transaction->update([
                    'total' => $total,
                    'grand_total' => $total,
                ]);

                // Create the CafeOrder tied to the cinema
                CafeOrder::create([
                    'transaction_id' => $transaction->id,
                    'cinema_id' => $validated['cinema_id'],
                    'status' => 'pending',
                ]);

                return $transaction;
            });

            return response()->json([
                'success' => true,
                'redirect_url' => route('checkout.index', $transaction),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
