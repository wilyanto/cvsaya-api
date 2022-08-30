<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarlyClockOutAttendance extends Model
{
    use HasFactory;

    public function attendanceDetail()
    {
        return $this->belongsTo(AttendanceDetail::class);
    }
}
