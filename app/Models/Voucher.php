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
        "voucher_number",
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
    protected $with = ['customer', 'user', 'voucherItems'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function voucherItems()
    {
        return $this->hasMany(VoucherItem::class);
    }
}
