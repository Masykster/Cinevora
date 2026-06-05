<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TmdbService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.themoviedb.org/3';

    public function __construct()
    {
        $this->apiKey = env('TMDB_API_KEY', '');
    }

    /**
     * Search movies by title.
     */
    public function searchMovies(string $query): array
    {
        if (empty($this->apiKey) || empty($query)) {
            return [];
        }

        $cacheKey = 'tmdb_search_' . md5(strtolower(trim($query)));

        return Cache::remember($cacheKey, now()->addHours(2), function () use ($query) {
            try {
                $response = Http::get("{$this->baseUrl}/search/movie", [
                    'api_key' => $this->apiKey,
                    'query' => $query,
                ]);

                if ($response->successful()) {
                    return $response->json()['results'] ?? [];
                }
            } catch (\Exception $e) {
                Log::error('TMDB Search Error: ' . $e->getMessage());
            }

            return [];
        });
    }

    /**
     * Get detailed movie information, including credits.
     */
    public function getMovieDetails(int $id): array
    {
        if (empty($this->apiKey)) {
            return [];
        }

        $cacheKey = 'tmdb_movie_' . $id;

        return Cache::remember($cacheKey, now()->addHours(2), function () use ($id) {
            try {
                $response = Http::get("{$this->baseUrl}/movie/{$id}", [
                    'api_key' => $this->apiKey,
                    'append_to_response' => 'credits',
                ]);

                if ($response->successful()) {
                    return $response->json();
                }
            } catch (\Exception $e) {
                Log::error('TMDB Details Error: ' . $e->getMessage());
            }

            return [];
        });
    }
}
