<?php

namespace App\Filament\Widgets;

use App\Models\Movie;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class NowPlayingWidget extends BaseWidget
{
    protected static ?string $heading = 'Kelola Status & Section Lagi Tayang';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Movie::query()->orderByRaw("CASE WHEN status = 'now_playing' THEN 1 WHEN status = 'coming_soon' THEN 2 ELSE 3 END")
            )
            ->columns([
                ImageColumn::make('poster')
                    ->disk('supabase')
                    ->label('Poster')
                    ->circular(),
                TextColumn::make('title')
                    ->label('Judul Film')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('genre')
                    ->label('Genre'),
                TextColumn::make('release_date')
                    ->label('Tanggal Rilis')
                    ->date()
                    ->sortable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'now_playing' => 'Now Playing',
                        'coming_soon' => 'Coming Soon',
                        'ended' => 'Ended',
                    ])
                    ->selectablePlaceholder(false)
                    ->sortable(),
            ]);
    }
}
