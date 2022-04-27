<?php

namespace App\Models;

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

    public function employee(){
        return $this->hasOne(Employee::class,'id','employee_id');
    }

    public function attendanceType(){
        return $this->hasOne(AttendanceType::class,'id','attendance_type_id');
    }
}
