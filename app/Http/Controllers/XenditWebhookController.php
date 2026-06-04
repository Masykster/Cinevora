<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Voucher;
use App\Models\CafeOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    /**
     * Handle Xendit invoice webhook callback.
     */
    public function handle(Request $request)
    {
        // Verify webhook token
        $callbackToken = $request->header('x-callback-token');
        $expectedToken = config('services.xendit.webhook_token');

        if ($expectedToken && $callbackToken !== $expectedToken) {
            Log::warning('Xendit webhook: invalid callback token');
            return response()->json(['error' => 'Invalid token'], 403);
        }

        $externalId = $request->input('external_id');
        $status = $request->input('status');
        $paymentMethod = $request->input('payment_method');

        Log::info('Xendit webhook received', [
            'external_id' => $externalId,
            'status' => $status,
            'payment_method' => $paymentMethod,
        ]);

        if ($status !== 'PAID') {
            return response()->json(['message' => 'Noted'], 200);
        }

        $transaction = Transaction::where('invoice_number', $externalId)->first();

        if (!$transaction) {
            Log::error('Xendit webhook: transaction not found', ['external_id' => $externalId]);
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        if ($transaction->status === 'paid') {
            return response()->json(['message' => 'Already processed'], 200);
        }

        DB::transaction(function () use ($transaction, $paymentMethod) {
            $transaction->update([
                'status' => 'paid',
                'paid_at' => now(),
                'expires_at' => null,
                'payment_method' => $paymentMethod,
                'booking_code' => Transaction::generateBookingCode(),
            ]);

            // Decrement voucher quota atomically
            if ($transaction->voucher_id) {
                Voucher::where('id', $transaction->voucher_id)
                    ->where('used_count', '<', DB::raw('quota'))
                    ->increment('used_count');
            }

            // Create cafe order if there are F&B items
            if ($transaction->orderItems()->count() > 0) {
                CafeOrder::create([
                    'transaction_id' => $transaction->id,
                    'status' => 'pending',
                ]);
            }
        });

        Log::info('Xendit webhook: payment processed', [
            'transaction_id' => $transaction->id,
            'booking_code' => $transaction->booking_code,
        ]);

        return response()->json(['message' => 'Success'], 200);
    }
}
