<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = 
        [
            [
                'title' => 'Spring Rolls',
                'slug' => 'spring-rolls',
                'category_id' => 1,
                'price' => 500,
                'image' => 'images/menus/spring-rolls.jpg',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Grilled Chicken',
                'slug' => 'grilled-chicken',
                'category_id' => 2,
                'price' => 1200,
                'image' => 'images/menus/grilled-chicken.jpg',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Chocolate Cake',
                'slug' => 'chocolate-cake',
                'category_id' => 3,
                'price' => 800,
                'image' => 'images/menus/chocolate-cake.jpg',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Fresh Orange Juice',
                'slug' => 'fresh-orange-juice',
                'category_id' => 4,
                'price' => 400,
                'image' => 'images/menus/orange-juice.jpg',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Cheeseburger',
                'slug' => 'cheeseburger',
                'category_id' => 5,
                'price' => 900,
                'image' => 'images/menus/cheeseburger.jpg',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Grilled Salmon',
                'slug' => 'grilled-salmon',
                'category_id' => 6,
                'price' => 1500,
                'image' => 'images/menus/grilled-salmon.jpg',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Vegetable Stir Fry',
                'slug' => 'vegetable-stir-fry',
                'category_id' => 7,
                'price' => 700,
                'image' => 'images/menus/veg-stir-fry.jpg',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'BBQ Ribs',
                'slug' => 'bbq-ribs',
                'category_id' => 8,
                'price' => 1600,
                'image' => 'images/menus/bbq-ribs.jpg',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Pancakes',
                'slug' => 'pancakes',
                'category_id' => 9,
                'price' => 600,
                'image' => 'images/menus/pancakes.jpg',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'French Fries',
                'slug' => 'french-fries',
                'category_id' => 10,
                'price' => 300,
                'image' => 'images/menus/french-fries.jpg',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('menus')->insert($menus);

    }
}
