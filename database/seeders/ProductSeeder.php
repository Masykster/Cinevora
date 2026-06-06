<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $promo = Category::where('slug', 'promo')->first();
        $combo = Category::where('slug', 'combo')->first();
        $popcorn = Category::where('slug', 'popcorn')->first();
        $fritters = Category::where('slug', 'fritters')->first();
        $lightMeal = Category::where('slug', 'light-meal')->first();
        $bakery = Category::where('slug', 'bakery')->first();
        $drinks = Category::where('slug', 'drinks')->first();
        $merchandise = Category::where('slug', 'merchandise')->first();

        $products = [
            // Promo
            ['category_id' => $promo->id, 'name' => 'Special XXI Promo Combo', 'description' => 'Popcorn Sweet Caramel Small + Iced Milo. Hemat s.d 30%!', 'price' => 45000],

            // Combo
            ['category_id' => $combo->id, 'name' => 'Movie Combo Double', 'description' => 'Popcorn Large + 2x Coca Cola Large. Pas buat nonton berdua.', 'price' => 85000],
            ['category_id' => $combo->id, 'name' => 'Single Combo', 'description' => 'Popcorn Small + 1x Coca Cola Large.', 'price' => 50000],

            // Popcorn
            ['category_id' => $popcorn->id, 'name' => 'Popcorn Sweet Caramel Large', 'description' => 'Popcorn manis karamel renyah ukuran besar.', 'price' => 38000],
            ['category_id' => $popcorn->id, 'name' => 'Popcorn Sweet Caramel Small', 'description' => 'Popcorn manis karamel renyah ukuran kecil.', 'price' => 25000],
            ['category_id' => $popcorn->id, 'name' => 'Popcorn Salty Butter Large', 'description' => 'Popcorn gurih mentega ukuran besar.', 'price' => 35000],

            // Fritters
            ['category_id' => $fritters->id, 'name' => 'Onion Rings Crispy', 'description' => 'Bawang bombay goreng tepung renyah dengan saus.', 'price' => 25000],
            ['category_id' => $fritters->id, 'name' => 'French Fries Salted', 'description' => 'Kentang goreng klasik dengan garam gurih.', 'price' => 28000],

            // Light Meal
            ['category_id' => $lightMeal->id, 'name' => 'XXI Hot Dog Classic', 'description' => 'Hot dog dengan sosis sapi premium dan mustard.', 'price' => 32000],
            ['category_id' => $lightMeal->id, 'name' => 'Cheese Burger Double', 'description' => 'Burger keju ganda dengan beef patty panggang.', 'price' => 42000],
            ['category_id' => $lightMeal->id, 'name' => 'Chicken Nuggets Premium', 'description' => 'Nugget dada ayam olahan berkualitas isi 6 pcs.', 'price' => 35000],

            // Bakery
            ['category_id' => $bakery->id, 'name' => 'Croissant Butter Warm', 'description' => 'Roti croissant mentega hangat renyah.', 'price' => 22000],
            ['category_id' => $bakery->id, 'name' => 'Choco Lava Cake', 'description' => 'Kue cokelat panggang dengan cokelat meleleh di dalam.', 'price' => 28000],

            // Drinks
            ['category_id' => $drinks->id, 'name' => 'Coca Cola Large', 'description' => 'Coca Cola dingin ukuran 500ml.', 'price' => 22000],
            ['category_id' => $drinks->id, 'name' => 'Iced Tea Sweet', 'description' => 'Es teh manis segar pelepas dahaga.', 'price' => 15000],
            ['category_id' => $drinks->id, 'name' => 'Caramel Frappe Coffee', 'description' => 'Blended coffee dengan saus karamel dan whipped cream.', 'price' => 38000],
            ['category_id' => $drinks->id, 'name' => 'Mineral Water Nestlé', 'description' => 'Air mineral botol 600ml dingin.', 'price' => 12000],

            // Merchandise
            ['category_id' => $merchandise->id, 'name' => 'Collectible Cup: Spider-Man', 'description' => 'Gelas collectible edisi khusus Spider-Man.', 'price' => 65000],
            ['category_id' => $merchandise->id, 'name' => 'IMAX Exclusive Tumbler', 'description' => 'Tumbler stainless steel eksklusif logo IMAX.', 'price' => 95000],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, ['is_available' => true]));
        }
    }
}
