<?php

namespace App\Filament\Resources\Schedules\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('movie_id')
                    ->relationship(
                        'movie', 
                        'title',
                        modifyQueryUsing: fn (\Illuminate\Database\Eloquent\Builder $query) => $query->nowPlaying()
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('cinema_id')
                    ->label('Bioskop')
                    ->options(\App\Models\Cinema::all()->pluck('name', 'id'))
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('studio_id', null))
                    ->afterStateHydrated(function (callable $set, callable $get) {
                        $studioId = $get('studio_id');
                        if ($studioId) {
                            $studio = \App\Models\Studio::find($studioId);
                            if ($studio) {
                                $set('cinema_id', $studio->cinema_id);
                            }
                        }
                    })
                    ->dehydrated(false)
                    ->required(),
                Select::make('studio_id')
                    ->label('Studio')
                    ->options(function (callable $get) {
                        $cinemaId = $get('cinema_id');
                        if (! $cinemaId) {
                            return [];
                        }
                        return \App\Models\Studio::where('cinema_id', $cinemaId)->pluck('name', 'id');
                    })
                    ->required(),
                DatePicker::make('show_date')
                    ->native(false)
                    ->timezone('Asia/Jakarta')
                    ->required(),
                TimePicker::make('show_time')
                    ->native(false)
                    ->timezone('Asia/Jakarta')
                    ->required(),
                TextInput::make('price_weekday')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(35000),
                TextInput::make('price_weekend')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(50000),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]);
    }
}
