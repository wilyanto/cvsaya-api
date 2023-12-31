<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeOneTimeShift extends Model implements Auditable
{
    use HasFactory, SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'employee_id',
        'shift_id',
        'date',
        'is_enabled'
    ];

    public $casts = [
        'is_enabled' => 'boolean',
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
            ->with(['attendancePenalty', 'outsideRadiusAttendance'])
            ->get();
    }
}
