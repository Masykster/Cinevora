<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $food = Category::where('slug', 'food')->first();
        $beverage = Category::where('slug', 'beverage')->first();
        $snack = Category::where('slug', 'snack')->first();
        $combo = Category::where('slug', 'combo')->first();

        $products = [
            // Food
            ['category_id' => $food->id, 'name' => 'Hot Dog Classic', 'description' => 'Hot dog dengan saus mustard dan ketchup.', 'price' => 28000],
            ['category_id' => $food->id, 'name' => 'Chicken Nuggets (6 pcs)', 'description' => 'Nugget ayam crispy dengan saus pilihan.', 'price' => 32000],
            ['category_id' => $food->id, 'name' => 'French Fries Large', 'description' => 'Kentang goreng renyah ukuran besar.', 'price' => 25000],
            ['category_id' => $food->id, 'name' => 'Cheese Burger', 'description' => 'Burger daging sapi dengan keju cheddar melted.', 'price' => 38000],

            // Beverage
            ['category_id' => $beverage->id, 'name' => 'Coca Cola Large', 'description' => 'Coca Cola dingin ukuran besar (500ml).', 'price' => 22000],
            ['category_id' => $beverage->id, 'name' => 'Iced Tea', 'description' => 'Es teh manis segar.', 'price' => 15000],
            ['category_id' => $beverage->id, 'name' => 'Mineral Water', 'description' => 'Air mineral 600ml.', 'price' => 10000],
            ['category_id' => $beverage->id, 'name' => 'Caramel Frappe', 'description' => 'Minuman kopi blended dengan karamel dan whipped cream.', 'price' => 35000],
            ['category_id' => $beverage->id, 'name' => 'Mango Smoothie', 'description' => 'Smoothie mangga segar dengan yogurt.', 'price' => 30000],

            // Snack
            ['category_id' => $snack->id, 'name' => 'Popcorn Small', 'description' => 'Popcorn caramel ukuran kecil.', 'price' => 20000],
            ['category_id' => $snack->id, 'name' => 'Popcorn Large', 'description' => 'Popcorn caramel ukuran besar.', 'price' => 35000],
            ['category_id' => $snack->id, 'name' => 'Nachos & Cheese', 'description' => 'Tortilla chips dengan saus keju hangat.', 'price' => 30000],
            ['category_id' => $snack->id, 'name' => 'Onion Rings', 'description' => 'Bawang goreng crispy.', 'price' => 22000],

            // Combo
            ['category_id' => $combo->id, 'name' => 'Movie Combo A', 'description' => 'Popcorn Large + 2x Coca Cola Large. Hemat Rp12.000!', 'price' => 65000],
            ['category_id' => $combo->id, 'name' => 'Movie Combo B', 'description' => 'Popcorn Large + 2x Iced Tea + Nachos & Cheese. Hemat Rp15.000!', 'price' => 75000],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, ['is_available' => true]));
        }
    }
}
