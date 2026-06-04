<?php

namespace App\Filament\Widgets;

use App\Models\Schedule;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class ClosedSchedulesWidget extends BaseWidget
{
    protected static ?string $heading = 'Riwayat Jadwal Tayang (Tutup)';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Schedule::query()
                    ->whereRaw("CONCAT(show_date, ' ', show_time) < ?", [now()->addMinutes(5)->toDateTimeString()])
                    ->with(['movie', 'studio.cinema'])
                    ->withCount(['tickets' => function ($q) {
                        $q->whereHas('transaction', fn ($t) => $t->where('status', 'paid'));
                    }])
                    ->orderBy('show_date', 'desc')
                    ->orderBy('show_time', 'desc')
            )
            ->columns([
                TextColumn::make('movie.title')
                    ->label('Nama Film')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('studio.cinema.name')
                    ->label('Bioskop')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('show_date')
                    ->label('Tanggal Tayang')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('show_time')
                    ->label('Jam Tayang')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('tickets_count')
                    ->label('Tiket Terjual')
                    ->alignCenter()
                    ->sortable()
                    ->badge()
                    ->color('success'),
            ]);
    }
}
