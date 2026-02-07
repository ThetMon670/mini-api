<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'price',
        'image',
        'user_id'
    ];

    protected static function booted()
    {
        // 🔄 delete old image when image is replaced
        static::updating(function ($menu) {
            if ($menu->isDirty('image')) {
                $oldImage = $menu->getOriginal('image');

                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        });

        // 🗑 delete image when model deleted
        static::deleting(function ($menu) {
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }
        });
    }
}