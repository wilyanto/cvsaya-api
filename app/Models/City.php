<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $casts = [
        'longitude' => 'float',
        'latitude' => 'float'
    ];

    public function SubDistricts()
    {
        return $this->hasMany(SubDistrict::class, 'city_code', 'code');
    }
}
