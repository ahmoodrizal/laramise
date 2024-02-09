<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'full_address', 'phone', 'prov_id', 'city_id', 'district_id', 'postal_code', 'is_default'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
