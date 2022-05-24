<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $casts = [
        'longitude' => 'float',
        'latitude' => 'float'
    ];

    public function Province()
    {
        return $this->hasMany(Province::class, 'country_code', 'code');
    }
}
