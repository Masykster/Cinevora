<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\CafeOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function selectSeats(Schedule $schedule)
    {
        if (!$schedule->is_bookable) {
            return redirect()->route('movies.show', $schedule->movie_id)
                ->with('error', 'Pemesanan tiket untuk jadwal ini sudah ditutup.');
        }

        $schedule->load(['movie', 'studio.cinema', 'studio.seats' => function ($q) {
            $q->where('is_active', true)->orderBy('row_label')->orderBy('seat_number');
        }]);

        $bookedSeatIds = $schedule->getBookedSeatIds();

        // Group seats by row
        $seatsByRow = $schedule->studio->seats->groupBy('row_label');

        return view('booking.seats', compact('schedule', 'seatsByRow', 'bookedSeatIds'));
    }

    /**
     * Process seat selection and create pending transaction with 10-minute expiry.
     */
    public function processBooking(Request $request, Schedule $schedule)
    {
        if (!$schedule->is_bookable) {
            return back()->with('error', 'Pemesanan tiket untuk jadwal ini sudah ditutup.');
        }

        $validated = $request->validate([
            'seat_ids' => 'required|array|min:1|max:8',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        $seatIds = $validated['seat_ids'];
        $price = $schedule->price;

        try {
            $transaction = DB::transaction(function () use ($schedule, $seatIds, $price) {
                // Lock and verify seats are still available
                // Check against both pending (not expired) and paid transactions
                $bookedSeats = Ticket::where('schedule_id', $schedule->id)
                    ->whereIn('seat_id', $seatIds)
                    ->whereHas('transaction', fn ($q) => $q
                        ->where(function ($sub) {
                            $sub->where('status', 'paid')
                                ->orWhere(function ($pending) {
                                    $pending->where('status', 'pending')
                                        ->where(function ($notExpired) {
                                            $notExpired->whereNull('expires_at')
                                                ->orWhere('expires_at', '>', now());
                                        });
                                });
                        })
                    )
                    ->lockForUpdate()
                    ->count();

                if ($bookedSeats > 0) {
                    throw new \Exception('Beberapa kursi yang dipilih sudah dipesan. Silakan pilih kursi lain.');
                }

                // Create transaction with 10-minute expiration
                $total = count($seatIds) * $price;
                $transaction = Transaction::create([
                    'user_id' => Auth::id(),
                    'invoice_number' => Transaction::generateInvoiceNumber(),
                    'total' => $total,
                    'discount' => 0,
                    'grand_total' => $total,
                    'status' => 'pending',
                    'expires_at' => now()->addMinutes(10),
                ]);

                // Create tickets
                foreach ($seatIds as $seatId) {
                    Ticket::create([
                        'transaction_id' => $transaction->id,
                        'schedule_id' => $schedule->id,
                        'seat_id' => $seatId,
                        'price' => $price,
                        'barcode' => Ticket::generateBarcode(),
                    ]);
                }

                return $transaction;
            });

            return redirect()->route('checkout.index', $transaction);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
