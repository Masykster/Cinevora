<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        $movies = [
            [
                'title' => 'Cahaya di Ujung Malam',
                'synopsis' => 'Seorang fotografer muda menemukan kembali makna hidupnya setelah kehilangan orang yang dicintainya. Melalui perjalanan spiritual ke pedalaman Kalimantan, ia belajar bahwa cahaya selalu hadir bahkan di kegelapan terdalam.',
                'genre' => 'Drama, Romance',
                'director' => 'Joko Anwar',
                'cast' => 'Reza Rahadian, Dian Sastrowardoyo, Adipati Dolken',
                'duration' => 125,
                'rating' => 8.2,
                'release_date' => '2026-04-01',
                'status' => 'now_playing',
                'age_rating' => '13+',
            ],
            [
                'title' => 'Nusantara: Rise of Majapahit',
                'synopsis' => 'Epic sejarah yang mengisahkan bangkitnya Kerajaan Majapahit dari reruntuhan Singhasari. Gajah Mada muda bersumpah untuk menyatukan Nusantara di bawah satu bendera.',
                'genre' => 'Action, History',
                'director' => 'Hanung Bramantyo',
                'cast' => 'Iko Uwais, Joe Taslim, Chelsea Islan',
                'duration' => 148,
                'rating' => 8.7,
                'release_date' => '2026-03-15',
                'status' => 'now_playing',
                'age_rating' => '13+',
            ],
            [
                'title' => 'Quantum Paradox',
                'synopsis' => 'Sebuah eksperimen fisika kuantum yang gagal membuka portal ke dimensi paralel. Tim ilmuwan harus menemukan cara menutup portal sebelum dua realitas bertabrakan dan menghancurkan keduanya.',
                'genre' => 'Sci-Fi, Thriller',
                'director' => 'Christopher Nolan',
                'cast' => 'Timothée Chalamet, Zendaya, Robert Downey Jr.',
                'duration' => 152,
                'rating' => 9.1,
                'release_date' => '2026-04-10',
                'status' => 'now_playing',
                'age_rating' => '13+',
            ],
            [
                'title' => 'Petualangan Si Kancil',
                'synopsis' => 'Animasi 3D pertama Indonesia yang mengisahkan petualangan Si Kancil dan teman-temannya menyelamatkan Hutan Nusantara dari ancaman penebangan liar. Film keluarga yang mengajarkan cinta alam.',
                'genre' => 'Animation, Adventure',
                'director' => 'Riri Riza',
                'cast' => 'Raline Shah (voice), Denny Cagur (voice), Fatin Shidqia (voice)',
                'duration' => 95,
                'rating' => 7.8,
                'release_date' => '2026-04-05',
                'status' => 'now_playing',
                'age_rating' => 'SU',
            ],
            [
                'title' => 'The Last Ronin',
                'synopsis' => 'Di Jepang abad ke-18, seorang ronin terakhir dari klan yang dimusnahkan mengejar balas dendam melintasi musim dingin yang kejam. Ia harus memilih antara kehormatan dan pengampunan.',
                'genre' => 'Action, Drama',
                'director' => 'Takeshi Kitano',
                'cast' => 'Hiroyuki Sanada, Ken Watanabe, Rinko Kikuchi',
                'duration' => 138,
                'rating' => 8.5,
                'release_date' => '2026-03-28',
                'status' => 'now_playing',
                'age_rating' => '17+',
            ],
            [
                'title' => 'Hantu Rumah Tua',
                'synopsis' => 'Sebuah keluarga muda pindah ke rumah warisan di pinggiran Yogyakarta. Mereka tidak tahu bahwa rumah itu menyimpan rahasia kelam dari masa penjajahan Belanda yang masih menghantui penghuninya.',
                'genre' => 'Horror, Mystery',
                'director' => 'Timo Tjahjanto',
                'cast' => 'Pevita Pearce, Jefri Nichol, Christine Hakim',
                'duration' => 110,
                'rating' => 7.5,
                'release_date' => '2026-04-12',
                'status' => 'now_playing',
                'age_rating' => '17+',
            ],
            [
                'title' => 'Cinta di Seoul',
                'synopsis' => 'Kisah cinta antara mahasiswi Indonesia yang berkuliah di Seoul dan seorang musisi jalanan Korea. Perbedaan budaya menjadi tantangan mereka, namun musik menjadi bahasa universal yang menyatukan hati.',
                'genre' => 'Romance, Comedy',
                'director' => 'Yandy Laurens',
                'cast' => 'Maudy Ayunda, Park Seo-joon, Angga Yunanda',
                'duration' => 112,
                'rating' => 7.9,
                'release_date' => '2026-04-18',
                'status' => 'now_playing',
                'age_rating' => '13+',
            ],
            [
                'title' => 'Elysium Protocol',
                'synopsis' => 'Di tahun 2089, umat manusia telah berkoloni di Mars. Ketika sebuah sinyal misterius diterima dari luar tata surya, kapten kapal eksplorasi harus memimpin misi berbahaya ke tepi galaksi.',
                'genre' => 'Sci-Fi, Adventure',
                'director' => 'Denis Villeneuve',
                'cast' => 'Oscar Isaac, Saoirse Ronan, Idris Elba',
                'duration' => 165,
                'rating' => 8.9,
                'release_date' => '2026-05-15',
                'status' => 'coming_soon',
                'age_rating' => '13+',
            ],
            [
                'title' => 'Komedi Moderen Gokil 3',
                'synopsis' => 'Trio sahabat yang sudah menginjak usia 40-an terjebak dalam situasi konyol ketika mereka memutuskan untuk memulai bisnis startup. Kekacauan demi kekacauan terjadi dengan hasil yang tak terduga.',
                'genre' => 'Comedy',
                'director' => 'Fajar Nugros',
                'cast' => 'Raditya Dika, Boris Bokir, Indro Warkop',
                'duration' => 100,
                'rating' => 7.2,
                'release_date' => '2026-05-20',
                'status' => 'coming_soon',
                'age_rating' => '13+',
            ],
            [
                'title' => 'Whispers of the Deep',
                'synopsis' => 'Sebuah tim peneliti kelautan menemukan kota kuno yang terendam di palung Mariana. Semakin dalam mereka menyelam, semakin banyak misteri yang terungkap — dan semakin dekat mereka dengan bahaya yang tak terjelaskan.',
                'genre' => 'Thriller, Mystery',
                'director' => 'James Cameron',
                'cast' => 'Ana de Armas, John Boyega, Cate Blanchett',
                'duration' => 142,
                'rating' => 8.4,
                'release_date' => '2026-06-01',
                'status' => 'coming_soon',
                'age_rating' => '13+',
            ],
        ];

        foreach ($movies as $movieData) {
            Movie::create($movieData);
        }
    }
}
