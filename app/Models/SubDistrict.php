<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    use HasFactory;

    protected $casts = [
        'longitude' => 'float',
        'latitude' => 'float'
    ];

    public function Villages()
    {
        return $this->hasMany(Village::class, 'sub_district_code', 'code');
    }
}
