<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AttendancePenalty extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'attendances_penalties';

    public $fillable = [
        'duty_at',
        'amount',
        'attendance_id',
        'penalty_id',
    ];

    public function employee(){
        return $this->hasOne(Employee::class,'id','employee_id');
    }
}
