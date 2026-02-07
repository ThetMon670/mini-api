<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = 
        [
            [
                'title' => 'Appetizers',
                'slug' => 'appetizers',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Main Course',
                'slug' => 'main-course',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Desserts',
                'slug' => 'desserts',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Beverages',
                'slug' => 'beverages',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Fast Food',
                'slug' => 'fast-food',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Seafood',
                'slug' => 'seafood',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Vegetarian',
                'slug' => 'vegetarian',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'BBQ',
                'slug' => 'bbq',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Breakfast',
                'slug' => 'breakfast',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Snacks',
                'slug' => 'snacks',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];


        DB::table('categories')->insert($categories);

    }
}
