<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Pendapatan Tahun Ini';

    protected function getData(): array
    {
        $currentYear = now()->year;

        $data = collect(range(1, 12))->map(function ($month) use ($currentYear) {
            return \App\Models\Transaction::where('status', 'paid')
                ->whereYear('paid_at', $currentYear)
                ->whereMonth('paid_at', $month)
                ->sum('grand_total');
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan Bulanan (' . $currentYear . ')',
                    'data' => $data,
                    'borderColor' => '#3b82f6', // Tailwind blue color
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
