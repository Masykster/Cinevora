<?php

namespace Database\Seeders;

use App\Models\Cinema;
use App\Models\Studio;
use App\Models\Seat;
use Illuminate\Database\Seeder;

class CinemaSeeder extends Seeder
{
    public function run(): void
    {
        $cinemas = [
            [
                'name' => 'Cinevora Grand City',
                'city' => 'Jakarta',
                'address' => 'Jl. Sudirman No. 88, Grand City Mall Lt. 5, Jakarta Selatan',
                'description' => 'Bioskop premium dengan teknologi layar terbaru di jantung kota Jakarta.',
                'phone' => '021-5550001',
                'studios' => [
                    ['name' => 'Studio 1', 'type' => 'regular', 'rows' => 8, 'cols' => 12],
                    ['name' => 'Studio 2', 'type' => 'regular', 'rows' => 8, 'cols' => 12],
                    ['name' => 'Studio 3', 'type' => 'regular', 'rows' => 7, 'cols' => 10],
                    ['name' => 'IMAX Hall', 'type' => 'imax', 'rows' => 10, 'cols' => 16],
                    ['name' => 'VIP Lounge', 'type' => 'vip', 'rows' => 5, 'cols' => 8],
                ],
            ],
            [
                'name' => 'Cinevora Pakuwon',
                'city' => 'Surabaya',
                'address' => 'Jl. Pakuwon Indah No. 12, Pakuwon Mall Lt. 3, Surabaya',
                'description' => 'Pengalaman menonton film terbaik di Surabaya dengan fasilitas lengkap.',
                'phone' => '031-5550002',
                'studios' => [
                    ['name' => 'Studio 1', 'type' => 'regular', 'rows' => 8, 'cols' => 12],
                    ['name' => 'Studio 2', 'type' => 'regular', 'rows' => 7, 'cols' => 10],
                    ['name' => 'Studio 3', 'type' => 'regular', 'rows' => 7, 'cols' => 10],
                    ['name' => 'IMAX Theater', 'type' => 'imax', 'rows' => 10, 'cols' => 14],
                ],
            ],
            [
                'name' => 'Cinevora Dago',
                'city' => 'Bandung',
                'address' => 'Jl. Ir. H. Juanda No. 45, Dago Plaza Lt. 4, Bandung',
                'description' => 'Bioskop modern dengan suasana cozy di kawasan Dago, Bandung.',
                'phone' => '022-5550003',
                'studios' => [
                    ['name' => 'Studio 1', 'type' => 'regular', 'rows' => 8, 'cols' => 10],
                    ['name' => 'Studio 2', 'type' => 'regular', 'rows' => 7, 'cols' => 10],
                    ['name' => 'VIP Cinema', 'type' => 'vip', 'rows' => 4, 'cols' => 6],
                ],
            ],
            [
                'name' => 'Cinevora Paragon',
                'city' => 'Semarang',
                'address' => 'Jl. Pemuda No. 150, Paragon City Mall Lt. 5, Semarang',
                'description' => 'Destinasi hiburan terlengkap di Semarang dengan 3 studio berkualitas.',
                'phone' => '024-5550004',
                'studios' => [
                    ['name' => 'Studio 1', 'type' => 'regular', 'rows' => 8, 'cols' => 10],
                    ['name' => 'Studio 2', 'type' => 'regular', 'rows' => 7, 'cols' => 10],
                    ['name' => 'Studio 3', 'type' => 'regular', 'rows' => 6, 'cols' => 8],
                ],
            ],
            [
                'name' => 'Cinevora Kuta',
                'city' => 'Bali',
                'address' => 'Jl. Sunset Road No. 77, Beachwalk Mall Lt. 3, Kuta, Bali',
                'description' => 'Nikmati film favorit di pulau dewata dengan kenyamanan premium.',
                'phone' => '0361-5550005',
                'studios' => [
                    ['name' => 'Studio 1', 'type' => 'regular', 'rows' => 7, 'cols' => 10],
                    ['name' => 'Studio 2', 'type' => 'regular', 'rows' => 7, 'cols' => 10],
                    ['name' => 'IMAX Hall', 'type' => 'imax', 'rows' => 9, 'cols' => 14],
                    ['name' => 'VIP Suite', 'type' => 'vip', 'rows' => 4, 'cols' => 6],
                ],
            ],
        ];

        foreach ($cinemas as $cinemaData) {
            $studios = $cinemaData['studios'];
            unset($cinemaData['studios']);

            $cinema = Cinema::create(array_merge($cinemaData, ['is_active' => true]));

            foreach ($studios as $studioData) {
                $rows = $studioData['rows'];
                $cols = $studioData['cols'];

                $studio = Studio::create([
                    'cinema_id' => $cinema->id,
                    'name' => $studioData['name'],
                    'type' => $studioData['type'],
                    'capacity' => $rows * $cols,
                    'rows' => $rows,
                    'cols' => $cols,
                    'is_active' => true,
                ]);

                // Generate seats
                $seats = [];
                $now = now();
                for ($r = 0; $r < $rows; $r++) {
                    $rowLabel = chr(65 + $r);
                    for ($c = 1; $c <= $cols; $c++) {
                        $seats[] = [
                            'studio_id' => $studio->id,
                            'row_label' => $rowLabel,
                            'seat_number' => $c,
                            'code' => $rowLabel . $c,
                            'type' => $studioData['type'] === 'vip' ? 'vip' : 'regular',
                            'is_active' => true,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
                Seat::insert($seats);
            }
        }
    }
}
