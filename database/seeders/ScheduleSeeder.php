<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Studio;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $nowPlayingMovies = Movie::where('status', 'now_playing')->get();
        $studios = Studio::where('is_active', true)->get();

        $showTimes = ['10:00', '13:00', '15:30', '18:00', '20:30'];

        // Generate schedules for the next 7 days
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(6);

        foreach ($nowPlayingMovies as $movie) {
            // Assign movie to random studios (2-5 studios per movie)
            $assignedStudios = $studios->random(min($studios->count(), rand(2, 5)));

            foreach ($assignedStudios as $studio) {
                // Assign 2-3 show times per studio
                $studioShowTimes = collect($showTimes)->random(rand(2, 3))->sort()->values();

                for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                    $isWeekend = in_array($date->dayOfWeek, [0, 6]);

                    // Base prices by studio type
                    $basePrices = match ($studio->type) {
                        'imax' => ['weekday' => 75000, 'weekend' => 100000],
                        'vip' => ['weekday' => 100000, 'weekend' => 150000],
                        default => ['weekday' => 40000, 'weekend' => 55000],
                    };

                    foreach ($studioShowTimes as $time) {
                        // Check no conflict
                        $exists = Schedule::where('studio_id', $studio->id)
                            ->where('show_date', $date->toDateString())
                            ->where('show_time', $time)
                            ->exists();

                        if (!$exists) {
                            Schedule::create([
                                'movie_id' => $movie->id,
                                'studio_id' => $studio->id,
                                'show_date' => $date->toDateString(),
                                'show_time' => $time,
                                'price_weekday' => $basePrices['weekday'],
                                'price_weekend' => $basePrices['weekend'],
                                'is_active' => true,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
