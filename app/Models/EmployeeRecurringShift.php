<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeRecurringShift extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'shift_id',
        'day',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function attendances()
    {
        return $this->hasManyThrough(Attendance::class, Shift::class, 'id', 'shift_id', 'shift_id', 'id');
    }

    public function getAttendances($date)
    {
        return $this->attendances()->whereDate('scheduled_at', $date)
            ->where('shift_id', $this->shift->id)
            ->where('employee_id', $this->employee->id)
            ->with('attendancePenalty')
            ->get();
    }
}
