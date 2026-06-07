<?php echo App\Models\Schedule::where('movie_id', 53)->whereHas('studio.cinema', function($q){ $q->where('name', 'like', '%Tunjungan%'); })->get()->toJson(JSON_PRETTY_PRINT);
