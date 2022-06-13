<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutsideRadiusAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_detail_id',
        'note'
    ];

    public function Attendance()
    {
        return $this->hasOne(AttendanceDetail::class);
    }
}
