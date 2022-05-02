<?php

namespace App\Models;

use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Attendance extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'attendances';

    protected $dates = [
        'checked_at',
        'duty_at',
        'validated_at'
    ];

    public $fillable = [
        'id',
        'checked_at',
        'duty_at',
        'validated_at',
        'employee_id',
        'attendance_type_id'
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function attendanceType()
    {
        return $this->hasOne(AttendanceType::class, 'id', 'attendance_type_id');
    }

    public function attendancePenalty()
    {
        return $this->hasOne(AttendancePenalty::class, 'attendance_id', 'id');
    }

    public function penalty()
    {
        return $this->hasOne(AttendancePenalty::class, 'penalty_id', 'id')->withDefault();
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

    public function attendancePenalty(){
        return $this->hasOne(AttendancePenalty::class,'attendance_id','id');
    }
}
