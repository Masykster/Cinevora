<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Transaction;
use App\Models\Ticket;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Revenue stats
        $ticketRevenue = Ticket::whereHas('transaction', fn ($q) => $q->where('status', 'paid'))
            ->sum('price');

        $fnbRevenue = OrderItem::whereHas('transaction', fn ($q) => $q->where('status', 'paid'))
            ->sum('subtotal');

        $totalRevenue = $ticketRevenue + $fnbRevenue;

        $totalTransactions = Transaction::where('status', 'paid')->count();

        // Best selling movies
        $bestMovies = Movie::select('movies.*')
            ->selectSub(
                Ticket::selectRaw('COUNT(*)')
                    ->join('schedules', 'tickets.schedule_id', '=', 'schedules.id')
                    ->whereColumn('schedules.movie_id', 'movies.id')
                    ->whereHas('transaction', fn ($q) => $q->where('status', 'paid')),
                'tickets_sold'
            )
            ->orderByDesc('tickets_sold')
            ->take(5)
            ->get();

        // Recent transactions
        $recentTransactions = Transaction::with(['user', 'tickets.schedule.movie'])
            ->where('status', 'paid')
            ->orderBy('paid_at', 'desc')
            ->take(10)
            ->get();

        // User count
        $userCount = User::where('role', 'user')->count();

        // Monthly revenue chart data (last 6 months)
        $monthlyRevenue = Transaction::where('status', 'paid')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as month, SUM(grand_total) as revenue")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'ticketRevenue',
            'fnbRevenue',
            'totalRevenue',
            'totalTransactions',
            'bestMovies',
            'recentTransactions',
            'userCount',
            'monthlyRevenue'
        ));
    }
}
