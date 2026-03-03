<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('asdffdsa'),
                'profile_image' => 'images/images2.jpg',
            ],
            [
                'name' => 'test',
                'email' => 'test@gmail.com',
                'password' => Hash::make('asdffdsa'),
                'profile_image' => null,
            ]
        ];

        foreach ($users as $user) {
            // This will insert the user if they don't exist, or update if they do
            User::updateOrCreate(
                ['email' => $user['email']], // condition to check existing record
                array_merge($user, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
