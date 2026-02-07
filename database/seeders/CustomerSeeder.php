<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first(); // Admin user

        if (! $user) {
            return; // Safety check
        }

        $customers = [
            [
                'user_id' => $user->id,
                'name' => 'Aung Min',
                'email' => 'aungmin@example.com',
                'phone' => '09791234567',
                'address' => 'Yangon, Myanmar',
                'date_of_birth' => '1990-01-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'Thiri Hlaing',
                'email' => 'thirihlaing@example.com',
                'phone' => '09791234568',
                'address' => 'Mandalay, Myanmar',
                'date_of_birth' => '1988-05-22',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'Ko Ko Lwin',
                'email' => 'kokolwin@example.com',
                'phone' => '09791234569',
                'address' => 'Naypyidaw, Myanmar',
                'date_of_birth' => '1992-11-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'Su Su Kyi',
                'email' => 'susukyi@example.com',
                'phone' => '09791234570',
                'address' => 'Bago, Myanmar',
                'date_of_birth' => '1995-03-05',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'Min Thu',
                'email' => 'minthu@example.com',
                'phone' => '09791234571',
                'address' => 'Mawlamyine, Myanmar',
                'date_of_birth' => '1987-07-12',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'Hnin Ei Mon',
                'email' => 'hnineimon@example.com',
                'phone' => '09791234572',
                'address' => 'Taunggyi, Myanmar',
                'date_of_birth' => '1993-09-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'Kyaw Zin',
                'email' => 'kyawzin@example.com',
                'phone' => '09791234573',
                'address' => 'Monywa, Myanmar',
                'date_of_birth' => '1991-12-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'Thet Mon',
                'email' => 'thetmon@example.com',
                'phone' => '09791234574',
                'address' => 'Pathein, Myanmar',
                'date_of_birth' => '1989-06-18',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'Zaw Htet',
                'email' => 'zawhtet@example.com',
                'phone' => '09791234575',
                'address' => 'Hpa-an, Myanmar',
                'date_of_birth' => '1994-02-28',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'Ei Mon Kyaing',
                'email' => 'eimonkyaing@example.com',
                'phone' => '09791234576',
                'address' => 'Pyay, Myanmar',
                'date_of_birth' => '1990-08-08',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('customers')->insert($customers);
    }
}
