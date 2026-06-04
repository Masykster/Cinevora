<?php

namespace App\Filament\Resources\Movies\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use App\Services\TmdbService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MovieForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Cari dari TMDB')
                    ->description('Cari detail film dari database TMDB untuk mengisi form otomatis.')
                    ->collapsible()
                    ->schema([
                        Select::make('tmdb_id')
                            ->label('Pilih Film')
                            ->key('tmdb_id_search')
                            ->dehydrated(false)
                            ->native(false)
                            ->placeholder('Ketik untuk mencari film...')
                            ->selectablePlaceholder(false)
                            ->searchable()
                            ->getOptionLabelUsing(fn ($value) => "TMDB #{$value}")
                            ->getSearchResultsUsing(function (string $search) {
                                if (strlen($search) < 2) {
                                    return [];
                                }
                                $service = new TmdbService();
                                $results = $service->searchMovies($search);

                                return collect($results)->mapWithKeys(function ($movie) {
                                    $year = isset($movie['release_date']) ? ' (' . substr($movie['release_date'], 0, 4) . ')' : '';
                                    return [$movie['id'] => $movie['title'] . $year];
                                })->toArray();
                            })
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!$state) {
                                    return;
                                }

                                $service = new TmdbService();
                                $movie = $service->getMovieDetails($state);

                                if (empty($movie)) {
                                    return;
                                }

                                $set('title', $movie['title'] ?? '');
                                $set('synopsis', $movie['overview'] ?? '');
                                $set('release_date', $movie['release_date'] ?? null);
                                $set('duration', $movie['runtime'] ?? null);
                                $set('rating', number_format($movie['vote_average'] ?? 0.0, 1));

                                if (!empty($movie['genres'])) {
                                    $genres = collect($movie['genres'])->pluck('name')->implode(', ');
                                    $set('genre', $genres);
                                }

                                if (!empty($movie['credits'])) {
                                    $crew = $movie['credits']['crew'] ?? [];
                                    $director = collect($crew)->firstWhere('job', 'Director');
                                    if ($director) {
                                        $set('director', $director['name']);
                                    }

                                    $cast = $movie['credits']['cast'] ?? [];
                                    $topCast = collect($cast)->take(5)->pluck('name')->implode(', ');
                                    $set('cast', $topCast);
                                }

                                if (!empty($movie['poster_path'])) {
                                    $set('poster', "https://image.tmdb.org/t/p/w500" . $movie['poster_path']);
                                }

                                if (!empty($movie['backdrop_path'])) {
                                    $set('banner', "https://image.tmdb.org/t/p/original" . $movie['backdrop_path']);
                                }

                                $set('tmdb_id', null);
                            }),
                    ]),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('genre')
                    ->required()
                    ->maxLength(255),
                TextInput::make('director')
                    ->required()
                    ->maxLength(255),
                TextInput::make('cast')
                    ->maxLength(500),
                TextInput::make('duration')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                TextInput::make('rating')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(10)
                    ->default(0.0),
                DatePicker::make('release_date')
                    ->required(),
                Select::make('status')
                    ->options([
                        'now_playing' => 'Now Playing',
                        'coming_soon' => 'Coming Soon',
                        'ended' => 'Ended',
                    ])
                    ->default('coming_soon')
                    ->required(),
                Select::make('age_rating')
                    ->options([
                        'SU' => 'SU',
                        '13+' => '13+',
                        '17+' => '17+',
                        '21+' => '21+',
                    ])
                    ->required()
                    ->default('SU'),
                TextInput::make('trailer_url')
                    ->url()
                    ->maxLength(255),
                TextInput::make('poster')
                    ->maxLength(255),
                TextInput::make('banner')
                    ->maxLength(255),
                Textarea::make('synopsis')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
