<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Movie;

class SyncTmdbMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cinevora:sync-tmdb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Now Playing and Upcoming movies from TMDB API';

    private const GENRE_MAP = [
        28 => 'Action', 12 => 'Adventure', 16 => 'Animation', 35 => 'Comedy', 80 => 'Crime',
        99 => 'Documentary', 18 => 'Drama', 10751 => 'Family', 14 => 'Fantasy', 36 => 'History',
        27 => 'Horror', 10402 => 'Music', 9648 => 'Mystery', 10749 => 'Romance', 878 => 'Sci-Fi',
        10770 => 'TV Movie', 53 => 'Thriller', 10752 => 'War', 37 => 'Western'
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiKey = env('TMDB_API_KEY');

        if (!$apiKey) {
            $this->error('TMDB_API_KEY is missing in .env');
            return Command::FAILURE;
        }

        $this->info('Starting TMDB sync...');

        $this->syncCategory('now_playing', 'now_playing', $apiKey);
        $this->syncCategory('upcoming', 'coming_soon', $apiKey);

        $this->info('TMDB sync completed successfully!');
        return Command::SUCCESS;
    }

    private function syncCategory(string $endpoint, string $status, string $apiKey)
    {
        $this->info("Fetching $endpoint movies...");
        
        $url = "https://api.themoviedb.org/3/movie/{$endpoint}?api_key={$apiKey}&language=id-ID&region=ID&page=1";
        
        try {
            $response = Http::withoutVerifying()->get($url);
            
            if (!$response->successful()) {
                $this->error("Failed to fetch $endpoint: " . $response->body());
                return;
            }

            $movies = $response->json('results');
            
            if (empty($movies)) {
                $this->warn("No movies found for $endpoint.");
                return;
            }

            $count = 0;
            foreach ($movies as $apiMovie) {
                if (empty($apiMovie['title']) || empty($apiMovie['poster_path'])) {
                    continue;
                }

                // Map genres
                $genres = [];
                if (!empty($apiMovie['genre_ids'])) {
                    foreach (array_slice($apiMovie['genre_ids'], 0, 2) as $gid) {
                        if (isset(self::GENRE_MAP[$gid])) {
                            $genres[] = self::GENRE_MAP[$gid];
                        }
                    }
                }
                $genreString = !empty($genres) ? implode(', ', $genres) : 'Drama';

                Movie::updateOrCreate(
                    ['title' => $apiMovie['title']], // Unique constraint representation
                    [
                        'synopsis' => $apiMovie['overview'] ?: 'Sinopsis belum tersedia.',
                        'genre' => $genreString,
                        'director' => 'TBD',
                        'cast' => 'TBD',
                        'duration' => 120, // Default duration requested by user
                        'poster' => 'https://image.tmdb.org/t/p/w500' . $apiMovie['poster_path'],
                        'banner' => !empty($apiMovie['backdrop_path']) 
                            ? 'https://image.tmdb.org/t/p/w1280' . $apiMovie['backdrop_path'] 
                            : null,
                        'rating' => $apiMovie['vote_average'] ?? 0,
                        'release_date' => !empty($apiMovie['release_date']) ? $apiMovie['release_date'] : now(),
                        'status' => $status,
                        'age_rating' => '13+', // Default age rating requested by user
                    ]
                );
                
                $count++;
            }

            $this->info("Synced $count movies for $status.");

        } catch (\Exception $e) {
            $this->error("Error syncing $endpoint: " . $e->getMessage());
        }
    }
}
