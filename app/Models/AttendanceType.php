<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class AttendanceType extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'attendance_types';

    protected $dates = [
        'name'
    ];

    public $fillable = [
        'id',
        'name'
    ];
}
