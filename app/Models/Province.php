<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $casts = [
        'longitude' => 'float',
        'latitude' => 'float'
    ];

    public function Cities()
    {
        return $this->hasMany(City::class, 'province_code', 'code');
    }
}
