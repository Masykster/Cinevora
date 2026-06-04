<?php

namespace App\Filament\Widgets;

use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Ticket;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalMovies = Movie::count();
        $totalCinemas = Cinema::count();
        $totalTickets = Ticket::whereHas('transaction', fn ($q) => $q->where('status', 'paid'))->count();
        $totalRevenue = Transaction::where('status', 'paid')->sum('grand_total');

        return [
            Stat::make('Total Film', $totalMovies)
                ->description('Film terdaftar di sistem')
                ->descriptionIcon('heroicon-m-film')
                ->color('info'),
            Stat::make('Total Bioskop', $totalCinemas)
                ->description('Bioskop aktif')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),
            Stat::make('Tiket Terjual', $totalTickets)
                ->description('Tiket dari transaksi sukses')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('warning'),
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Pendapatan dari transaksi lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}
