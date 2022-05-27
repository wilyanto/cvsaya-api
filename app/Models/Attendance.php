<?php

namespace App\Models;

use App\Traits\UuidGenerator;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Attendance extends Model implements Auditable
{
    use HasFactory, UuidGenerator;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'attendances';

    protected $dates = [
        'checked_at',
        'duty_at',
        'validated_at'
    ];

    protected $guarded = [];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'attendances_employees')->withTimestamps();
    }


    public function attendanceType()
    {
        return $this->hasOne(AttendanceType::class, 'id', 'attendance_type_id');
    }

    public function attendancePenalty()
    {
        return $this->hasOneThrough(AttendancePenalty::class, AttendanceEmployee::class, 'attendance_id', 'attendance_employee_id', 'id', 'id');
    }

    public function penalty()
    {
        return $this->hasOne(AttendancePenalty::class, 'penalty_id', 'id')->withDefault();
    }

    public function outsideRadiusAttendance()
    {
        return $this->hasOne(OutsideRadiusAttendance::class);
    }

    public function getCheckedAtAttribute($date)
    {
        if ($date) {
            $date = new \DateTime($date, new DateTimeZone('Asia/Jakarta'));
            return $date->format('Y-m-d\TH:i:s.v\Z');
        }
    }

    public function getDutyAtAttribute($date)
    {
        if ($date) {
            $date = new \DateTime($date, new DateTimeZone('Asia/Jakarta'));
            return $date->format('Y-m-d\TH:i:s.v\Z');
        }
    }
}
