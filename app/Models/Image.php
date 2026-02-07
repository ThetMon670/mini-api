<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $fillable = [
        'url',
        'user_id'
    ];

    protected static function booted()
    {
        static::deleting(function ($image) {
            if ($image->url && Storage::disk('public')->exists($image->url)) {
                Storage::disk('public')->delete($image->url);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
