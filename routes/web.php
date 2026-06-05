<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CafeMenuController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\CinemaController as AdminCinemaController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\VoucherController as AdminVoucherController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CafeCartController;
use App\Http\Controllers\Cafe\OrderController as CafeOrderController;

// ========================================
// Public Routes
// ========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/{movie}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/cinemas', [\App\Http\Controllers\PublicCinemaController::class, 'index'])->name('cinemas.index');
Route::get('/cafe-menu', [CafeMenuController::class, 'index'])->name('cafe.menu');
Route::post('/cafe-menu/checkout', [CafeCartController::class, 'checkout'])->name('cafe.checkout')->middleware('auth');

// Xendit Webhook (no auth, no CSRF)
Route::post('/xendit/webhook', [\App\Http\Controllers\XenditWebhookController::class, 'handle'])->name('xendit.webhook');

// Image proxy for html2canvas CORS bypass
Route::get('/img-proxy', function (\Illuminate\Http\Request $request) {
    $url = $request->query('url');
    if (!$url || !str_starts_with($url, 'https://image.tmdb.org/')) {
        abort(400);
    }
    try {
        $response = \Illuminate\Support\Facades\Http::timeout(5)->get($url);
        return response($response->body(), 200)
            ->header('Content-Type', $response->header('Content-Type'))
            ->header('Cache-Control', 'public, max-age=86400');
    } catch (\Exception $e) {
        abort(404);
    }
})->name('img.proxy');

// ========================================
// Authentication Routes
// ========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ========================================
// Authenticated User Routes
// ========================================
Route::middleware('auth')->group(function () {
    // Booking
    Route::get('/booking/{schedule}/seats', [BookingController::class, 'selectSeats'])->name('booking.seats');
    Route::post('/booking/{schedule}/process', [BookingController::class, 'processBooking'])->name('booking.process');

    // Checkout & Transaction
    Route::get('/checkout/{transaction}', [TransactionController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/{transaction}/fnb', [TransactionController::class, 'addFnb'])->name('checkout.addFnb');
    Route::delete('/checkout/{transaction}/fnb/{orderItem}', [TransactionController::class, 'removeFnb'])->name('checkout.removeFnb');
    Route::post('/checkout/{transaction}/voucher', [TransactionController::class, 'applyVoucher'])->name('checkout.applyVoucher');
    Route::delete('/checkout/{transaction}/voucher', [TransactionController::class, 'removeVoucher'])->name('checkout.removeVoucher');
    Route::post('/checkout/{transaction}/pay', [TransactionController::class, 'pay'])->name('checkout.pay');
    Route::get('/checkout/{transaction}/invoice', [TransactionController::class, 'invoice'])->name('checkout.invoice');
    Route::get('/checkout/{transaction}/payment-pending', [TransactionController::class, 'paymentPending'])->name('checkout.paymentPending');
    Route::get('/checkout/{transaction}/check-status', [TransactionController::class, 'checkPaymentStatus'])->name('checkout.checkStatus');
    Route::post('/checkout/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('checkout.cancel');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});

// ========================================
// Cinema Admin Routes (Replaced by Filament Admin Panel)
// ========================================
/*
Route::middleware(['auth', 'role:cinema_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Movies CRUD
    Route::resource('movies', AdminMovieController::class);

    // Cinemas CRUD + Studios
    Route::resource('cinemas', AdminCinemaController::class);
    Route::post('cinemas/{cinema}/studios', [AdminCinemaController::class, 'storeStudio'])->name('cinemas.studios.store');
    Route::delete('cinemas/{cinema}/studios/{studio}', [AdminCinemaController::class, 'destroyStudio'])->name('cinemas.studios.destroy');

    // Schedules CRUD
    Route::resource('schedules', AdminScheduleController::class);

    // Vouchers CRUD
    Route::resource('vouchers', AdminVoucherController::class);

    // Users Management
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::post('users/{user}/toggle', [AdminUserController::class, 'toggleActive'])->name('users.toggle');
});
*/

// ========================================
// Cafe Admin Routes
// ========================================
Route::middleware(['auth', 'role:cafe_admin'])->prefix('cafe')->name('cafe.')->group(function () {
    Route::get('/', [CafeOrderController::class, 'index'])->name('dashboard');
    Route::post('/orders/{cafeOrder}/advance', [CafeOrderController::class, 'advanceStatus'])->name('orders.advance');
    Route::get('/api/orders', [CafeOrderController::class, 'apiOrders'])->name('api.orders');
});
