<?php

namespace App\Models;

use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceQrCode extends Model
{
    use HasFactory, SoftDeletes, UuidGenerator;

    protected $guard = 'id';

    public $fillable = [
        'location_name',
        'longitude',
        'latitude',
        'radius',
        'is_geo_strict',
        'company_id'
    ];

    // https://stackoverflow.com/questions/48288519/eloquent-casts-decimal-as-string
    public $casts = [
        'longitude' => 'float',
        'latitude' => 'float',
    ];
}
