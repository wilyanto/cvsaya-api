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
    ];
}
