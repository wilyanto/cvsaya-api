<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceEmployee extends Model
{
    use HasFactory;

    protected $table = 'attendances_employees';

    public function attendance()
    {
        return $this->hasOne(Attendance::class, 'id', 'attendance_id');
    }
}
