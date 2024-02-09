<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'image'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // public function getImageAttribute()
    // {
    //     return asset('storage/images/' . $this->attributes['image']);
    // }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
