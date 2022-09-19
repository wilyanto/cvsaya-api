<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarlyClockOutAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_detail_id',
        'note',
        'status'
    ];

    public function attendanceDetail()
    {
        return $this->belongsTo(AttendanceDetail::class);
    }
}
