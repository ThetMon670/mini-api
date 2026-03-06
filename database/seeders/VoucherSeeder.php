<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vouchers = [

            [
                'voucher_number' => 'V-0001',
                'customer_id' => 1,
                'date' => '2024-09-01',
                'total' => 12000,
                'tax' => 600,
                'net_total' => 12600,
                'cash' => 15000,
                'change' => 2400,
                'voucher_items_count' => 2,
                'type' => 'dine in',
                'user_id' => 1,
                'created_at' => '2024-09-01 10:15:00',
                'updated_at' => '2024-09-01 10:15:00'
            ],
            [
                'voucher_number' => 'V-0002',
                'customer_id' => 2,
                'date' => '2024-09-02',
                'total' => 8500,
                'tax' => 425,
                'net_total' => 8925,
                'cash' => 10000,
                'change' => 1075,
                'voucher_items_count' => 1,
                'type' => 'take away',
                'user_id' => 1,
                'created_at' => '2024-09-02 11:20:00',
                'updated_at' => '2024-09-02 11:20:00'
            ],
            [
                'voucher_number' => 'V-0003',
                'customer_id' => 3,
                'date' => '2024-09-03',
                'total' => 15000,
                'tax' => 750,
                'net_total' => 15750,
                'cash' => 20000,
                'change' => 4250,
                'voucher_items_count' => 3,
                'type' => 'dine in',
                'user_id' => 1,
                'created_at' => '2024-09-03 12:00:00',
                'updated_at' => '2024-09-03 12:00:00'
            ],
            [
                'voucher_number' => 'V-0004',
                'customer_id' => 4,
                'date' => '2024-09-04',
                'total' => 6400,
                'tax' => 320,
                'net_total' => 6720,
                'cash' => 7000,
                'change' => 280,
                'voucher_items_count' => 1,
                'type' => 'take away',
                'user_id' => 1,
                'created_at' => '2024-09-04 09:45:00',
                'updated_at' => '2024-09-04 09:45:00'
            ],
            [
                'voucher_number' => 'V-0005',
                'customer_id' => 5,
                'date' => '2024-09-05',
                'total' => 20000,
                'tax' => 1000,
                'net_total' => 21000,
                'cash' => 25000,
                'change' => 4000,
                'voucher_items_count' => 4,
                'type' => 'dine in',
                'user_id' => 1,
                'created_at' => '2024-09-05 14:30:00',
                'updated_at' => '2024-09-05 14:30:00'
            ],
            [
                'voucher_number' => 'V-0006',
                'customer_id' => 6,
                'date' => '2024-09-06',
                'total' => 9800,
                'tax' => 490,
                'net_total' => 10290,
                'cash' => 11000,
                'change' => 710,
                'voucher_items_count' => 2,
                'type' => 'take away',
                'user_id' => 1,
                'created_at' => '2024-09-06 13:00:00',
                'updated_at' => '2024-09-06 13:00:00'
            ],
            [
                'voucher_number' => 'V-0007',
                'customer_id' => 7,
                'date' => '2024-09-07',
                'total' => 17500,
                'tax' => 875,
                'net_total' => 18375,
                'cash' => 20000,
                'change' => 1625,
                'voucher_items_count' => 3,
                'type' => 'dine in',
                'user_id' => 1,
                'created_at' => '2024-09-07 18:15:00',
                'updated_at' => '2024-09-07 18:15:00'
            ],
            [
                'voucher_number' => 'V-0008',
                'customer_id' => 8,
                'date' => '2024-09-08',
                'total' => 7200,
                'tax' => 360,
                'net_total' => 7560,
                'cash' => 8000,
                'change' => 440,
                'voucher_items_count' => 1,
                'type' => 'take away',
                'user_id' => 1,
                'created_at' => '2024-09-08 11:10:00',
                'updated_at' => '2024-09-08 11:10:00'
            ],
            [
                'voucher_number' => 'V-0009',
                'customer_id' => 9,
                'date' => '2024-09-09',
                'total' => 13400,
                'tax' => 670,
                'net_total' => 14070,
                'cash' => 15000,
                'change' => 930,
                'voucher_items_count' => 2,
                'type' => 'dine in',
                'user_id' => 1,
                'created_at' => '2024-09-09 12:45:00',
                'updated_at' => '2024-09-09 12:45:00'
            ],
            [
                'voucher_number' => 'V-0010',
                'customer_id' => 10,
                'date' => '2024-09-10',
                'total' => 5600,
                'tax' => 280,
                'net_total' => 5880,
                'cash' => 6000,
                'change' => 120,
                'voucher_items_count' => 1,
                'type' => 'take away',
                'user_id' => 1,
                'created_at' => '2024-09-10 10:25:00',
                'updated_at' => '2024-09-10 10:25:00'
            ],

        ];

        Voucher::insert($vouchers);
    }
}
