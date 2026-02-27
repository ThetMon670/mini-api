<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Voucher;
use App\Models\VoucherItem;
use Illuminate\Database\Seeder;

class VoucherItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vouchers = Voucher::all();
        $menus = Menu::all();

        foreach ($vouchers as $voucher) {

            $items = [];
            $total = 0;
            for ($i = 0; $i < rand(1, 5); $i++) {
                $menu = $menus->random();
                $quantity = rand(1, 10);
                $cost = $quantity * $menu->price;
                $price = $menu->price;
                $items[] = [
                    'voucher_id' => $voucher->id,
                    'user_id' => 1,
                    'menu_id' => $menu->id,
                    'menu' => json_encode($menu),
                    'quantity' => $quantity,
                    'cost' => $cost,
                    'price' => $price,
                    'created_at' => $voucher->created_at,
                    'updated_at' => $voucher->updated_at
                ];

                $total += $cost;
            }

            VoucherItem::insert($items);

            $voucher->update([
                'total' => $total,
                'tax' => $total * 0.07,
                'net_total' => $total + $total * 0.07,
            ]);
        }
    }
}
