<?php $s = App\Models\Schedule::find(786); echo $s->show_date->format('Y-m-d') . ' ' . $s->show_time . ' - ' . ($s->is_bookable ? 'YES' : 'NO');
