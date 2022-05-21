<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AttendancePenalty extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    const UPDATED_AT = null;

    public $fillable = [
        'attendance_id',
        'penalty_id',
        'penalty_name',
        'penalty_amount',
        'attendance_employee_id',
        'note'
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }
}
