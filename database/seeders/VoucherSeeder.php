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
                'customer_id' => 1,
                'date' => '2024-09-01',
                'total' => 12000,
                'tax' => 600,
                'net_total' => 12600,
                'voucher_items_count' => 2,
                'order_type' => 'dine_in',
                'user_id' => 1,
                'created_at' => '2024-09-01 10:15:00',
                'updated_at' => '2024-09-01 10:15:00'
            ],
            [
                'customer_id' => 2,
                'date' => '2024-09-02',
                'total' => 8500,
                'tax' => 425,
                'net_total' => 8925,
                'voucher_items_count' => 1,
                'order_type' => 'take_away',
                'user_id' => 1,
                'created_at' => '2024-09-02 11:20:00',
                'updated_at' => '2024-09-02 11:20:00'
            ],
            [
                'customer_id' => 3,
                'date' => '2024-09-03',
                'total' => 15000,
                'tax' => 750,
                'net_total' => 15750,
                'voucher_items_count' => 3,
                'order_type' => 'dine_in',
                'user_id' => 1,
                'created_at' => '2024-09-03 12:00:00',
                'updated_at' => '2024-09-03 12:00:00'
            ],
            [
                'customer_id' => 4,
                'date' => '2024-09-04',
                'total' => 6400,
                'tax' => 320,
                'net_total' => 6720,
                'voucher_items_count' => 1,
                'order_type' => 'take_away',
                'user_id' => 1,
                'created_at' => '2024-09-04 09:45:00',
                'updated_at' => '2024-09-04 09:45:00'
            ],
            [
                'customer_id' => 5,
                'date' => '2024-09-05',
                'total' => 20000,
                'tax' => 1000,
                'net_total' => 21000,
                'voucher_items_count' => 4,
                'order_type' => 'dine_in',
                'user_id' => 1,
                'created_at' => '2024-09-05 14:30:00',
                'updated_at' => '2024-09-05 14:30:00'
            ],

            // 6–30

            [
                'customer_id' => 6,
                'date' => '2024-09-06',
                'total' => 9800,
                'tax' => 490,
                'net_total' => 10290,
                'voucher_items_count' => 2,
                'order_type' => 'take_away',
                'user_id' => 1,
                'created_at' => '2024-09-06 13:00:00',
                'updated_at' => '2024-09-06 13:00:00'
            ],
            [
                'customer_id' => 7,
                'date' => '2024-09-07',
                'total' => 17500,
                'tax' => 875,
                'net_total' => 18375,
                'voucher_items_count' => 3,
                'order_type' => 'dine_in',
                'user_id' => 1,
                'created_at' => '2024-09-07 18:15:00',
                'updated_at' => '2024-09-07 18:15:00'
            ],
            [
                'customer_id' => 8,
                'date' => '2024-09-08',
                'total' => 7200,
                'tax' => 360,
                'net_total' => 7560,
                'voucher_items_count' => 1,
                'order_type' => 'take_away',
                'user_id' => 1,
                'created_at' => '2024-09-08 11:10:00',
                'updated_at' => '2024-09-08 11:10:00'
            ],
            [
                'customer_id' => 9,
                'date' => '2024-09-09',
                'total' => 13400,
                'tax' => 670,
                'net_total' => 14070,
                'voucher_items_count' => 2,
                'order_type' => 'dine_in',
                'user_id' => 1,
                'created_at' => '2024-09-09 12:45:00',
                'updated_at' => '2024-09-09 12:45:00'
            ],
            [
                'customer_id' => 10,
                'date' => '2024-09-10',
                'total' => 5600,
                'tax' => 280,
                'net_total' => 5880,
                'voucher_items_count' => 1,
                'order_type' => 'take_away',
                'user_id' => 1,
                'created_at' => '2024-09-10 10:25:00',
                'updated_at' => '2024-09-10 10:25:00'
            ],

            [
                'customer_id' => 11,
                'date' => '2024-09-11',
                'total' => 22300,
                'tax' => 1115,
                'net_total' => 23415,
                'voucher_items_count' => 5,
                'order_type' => 'dine_in',
                'user_id' => 1,
                'created_at' => '2024-09-11 19:30:00',
                'updated_at' => '2024-09-11 19:30:00'
            ],
            [
                'customer_id' => 12,
                'date' => '2024-09-12',
                'total' => 8800,
                'tax' => 440,
                'net_total' => 9240,
                'voucher_items_count' => 2,
                'order_type' => 'take_away',
                'user_id' => 1,
                'created_at' => '2024-09-12 08:55:00',
                'updated_at' => '2024-09-12 08:55:00'
            ],
            [
                'customer_id' => 13,
                'date' => '2024-09-13',
                'total' => 14300,
                'tax' => 715,
                'net_total' => 15015,
                'voucher_items_count' => 3,
                'order_type' => 'dine_in',
                'user_id' => 1,
                'created_at' => '2024-09-13 16:40:00',
                'updated_at' => '2024-09-13 16:40:00'
            ],
            [
                'customer_id' => 14,
                'date' => '2024-09-14',
                'total' => 6700,
                'tax' => 335,
                'net_total' => 7035,
                'voucher_items_count' => 1,
                'order_type' => 'take_away',
                'user_id' => 1,
                'created_at' => '2024-09-14 13:15:00',
                'updated_at' => '2024-09-14 13:15:00'
            ],
            [
                'customer_id' => 15,
                'date' => '2024-09-15',
                'total' => 19800,
                'tax' => 990,
                'net_total' => 20790,
                'voucher_items_count' => 4,
                'order_type' => 'dine_in',
                'user_id' => 1,
                'created_at' => '2024-09-15 20:05:00',
                'updated_at' => '2024-09-15 20:05:00'
            ],

        ];
        Voucher::insert($vouchers);
    }
}
