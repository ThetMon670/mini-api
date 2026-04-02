<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'image',
        'date_of_birth',
        'user_id'
    ];

    protected $with = ['user'];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    
}
