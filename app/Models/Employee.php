<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Position;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use DateInterval;
use DateTimeZone;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Contracts\Auditable;

class Employee extends Authenticatable implements Auditable
{
    use HasFactory, SoftDeletes, CrudTrait, HasRoles;

    use \OwenIt\Auditing\Auditable;

    protected $dates = [
        'joined_at',
    ];

    protected $fillable = [
        'user_id',
        'position_id',
        'joined_at',
        'type',
        'is_default',
        'salary_type_id',
    ];

    public $casts = [
        'is_default' => 'boolean',
    ];

    public function position()
    {
        return $this->hasOne(Position::class, 'id', 'position_id');
    }

    public function profileDetail()
    {
        return $this->hasOne(CvProfileDetail::class, 'user_id', 'user_id');
    }

    public function company()
    {
        return $this->hasOneThrough(Company::class, Position::class, 'id', 'id', 'position_id', 'company_id');
    }

    public function level()
    {
        return $this->hasOneThrough(Level::class, Position::class, 'id', 'id', 'position_id', 'level_id');
    }

    public function department()
    {
        return $this->hasOneThrough(Department::class, Position::class, 'id', 'id', 'position_id', 'department_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_kustomer');
    }

    public function salaryTypes()
    {
        return $this->hasMany(EmployeeSalaryType::class);
    }

    public function typeOfSalary()
    {
        $employeeSalaryTypes = $this->salaryTypes;
        if ($employeeSalaryTypes->isNotEmpty()) {
            return $employeeSalaryTypes->map(function ($employeeSalaryType) {
                return [
                    'salary_type_id' => $employeeSalaryType->salaryType->id,
                    'name' => $employeeSalaryType->salaryType->name,
                    'amount' => $employeeSalaryType->amount,
                ];
            });
        }

        return [];
    }

    public function getCompanyName()
    {
        if ($this->company) {
            return $this->company->name;
        }
    }

    public function getPhoneNumber()
    {
        return $this->user->telpon;
    }

    public function getUserName()
    {
        if ($this->profileDetail) {
            return $this->profileDetail->first_name;
        }
    }

    public function interviewerDetail()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'position' => $this->position,
            'first_name' => $this->profileDetail->first_name,
            'last_name' => $this->profileDetail->last_name
        ];
    }

    // public function toArray()
    // {
    //     return [
    //         'id' => $this->id,
    //         'user_id' => $this->user_id,
    //         'position' => $this->position->toArrayEmployee(),
    //         'first_name' => $this->profileDetail->first_name,
    //         'last_name' => $this->profileDetail->last_name,
    //         'salary' => $this->salary,
    //     ];
    // }

    public function toArrayEmployee()
    {
        return [
            'id' => $this->id,
            'name' => $this->profileDetail->first_name . ' ' . $this->profileDetail->last_name,
            'salary_types' => $this->typeOfSalary(),
            'company' => $this->company,
            'department' => $this->department->onlyNameAndId(),
            'level' => $this->level->onlyNameAndId(),
            'position' => $this->position->toCandidate(),
            'parent_id' => $this->position->parent_id,
            'joined_at' => $this->joined_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    // public function getMasterAttendanceType($attendType){
    //    if()
    // }

    public function shiftPositions()
    {
        return $this->hasMany(ShiftPositions::class, 'position_id', 'position_id');
    }

    public function shiftEmployees()
    {
        return  $this->hasMany(ShiftEmployee::class, 'employee_id', 'id');
    }

    public function getShift($date)
    {
        $date = new \DateTime($date, new DateTimeZone('Asia/Jakarta'));
        $shift =  EmployeeOneTimeShift::whereDate('date', $date->format(' '))->first();
        if (!$shift) {
            $getTodayDay = $date->format('N');
            $shift = ShiftPositions::where('day', $getTodayDay)->where('position_id', $this->position->id)->first();
            if (!$shift) {
                return null;
            }
        };
        return $shift;
    }

    public function isWorkToday($date)
    {
        $isWorkToday = false;
        $date = new \DateTime($date, new DateTimeZone('Asia/Jakarta'));
        $tempDate = new \DateTime($date->format('Y-m-d\TH:i:s.u\Z'), new DateTimeZone('Asia/Jakarta'));
        $interval = DateInterval::createFromDateString('+23 hour +59 minute + 59 second');
        $endDayOfDate =  $tempDate->add($interval)->format('Y-m-d\TH:i:s.u\Z');
        if ($this->getShift($date->format('Y-m-d\TH:i:s.u\Z')) == null) {
            $isWorkToday = true;
            return $isWorkToday;
        }
        $attendances = Attendance::whereBetween(
            'duty_at',
            [
                $date->format('Y-m-d\TH:i:s.u\Z'),
                $endDayOfDate
            ]
        )->where('attendance_type_id', AttendanceType::CLOCK_IN_ID)
            ->where('employee_id', $this->id)
            ->whereNotNull('validated_at')
            ->first();
        if ($attendances) {
            $isWorkToday = true;
        }
        return $isWorkToday;
    }

    public function getAllPenalty($startDate, $untilDate, AttendanceType $penaltyType = null)
    {
        $penalty = 0;

        $startDate = new \DateTime($startDate, new DateTimeZone('Asia/Jakarta'));
        $untilDate = new \DateTime($untilDate, new DateTimeZone('Asia/Jakarta'));
        $attendances = Attendance::whereBetween('checked_at', [
            $startDate->format('Y-m-d\TH:i:s.u\Z'),
            $untilDate->format('Y-m-d\TH:i:s.u\Z')
        ])->where('employee_id', $this->id)
            ->where(function ($query) use ($penaltyType) {
                if ($penaltyType) {
                    $query->where('attendance_type_id', $penaltyType->id);
                }
            })->get();

        foreach ($attendances as $attendance) {
            $penalty += $attendance->penalty->amount;
        }
        return $penalty;
    }
}