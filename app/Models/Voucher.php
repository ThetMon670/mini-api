<?php

namespace App\Models;

use App\Enums\OrderType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    /** @use HasFactory<\Database\Factories\VoucherFactory> */
    use HasFactory;
    protected $fillable = [
        "customer_id",
        "date",
        "total",
        "tax",
        "cash",
        "change",
        "net_total",
        "voucher_items_count",
        "type",
        "user_id",

    ];

    protected $casts = [
        "voucher_items"    => "array",
    ];

    public function voucher_items()
    {
        return $this->hasMany(VoucherItem::class, "voucher_id","id");
    }
}
