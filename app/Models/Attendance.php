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

    protected $fillable = [
        'employee_id',
        'shift_id',
        'clock_in_id',
        'clock_out_id',
        'start_break_id',
        'end_break_id',
        'date'
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function shift()
    {
        return $this->hasOne(Shift::class, 'id', 'shift_id');
    }

    public function clockInAttendanceDetail()
    {
        return $this->hasOne(AttendanceDetail::class, 'id', 'clock_in_id');
    }

    public function clockOutAttendanceDetail()
    {
        return $this->hasOne(AttendanceDetail::class, 'id', 'clock_out_id');
    }

    public function startBreakAttendanceDetail()
    {
        return $this->hasOne(AttendanceDetail::class, 'id', 'start_break_id');
    }

    public function endBreakAttendanceDetail()
    {
        return $this->hasOne(AttendanceDetail::class, 'id', 'end_break_id');
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
