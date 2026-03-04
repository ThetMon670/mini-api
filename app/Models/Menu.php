<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Menu extends Model
{
    protected $fillable = [
        'title',
        'unit',
        'slug',
        'category_id',
        'price',
        'image',
        'user_id'
    ];

    protected static function booted()
    {
        parent::boot();

        static::creating(function ($menu) {
            $menu->slug = Str::slug($menu->title);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}