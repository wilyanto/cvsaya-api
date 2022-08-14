<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Position;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use DateInterval;
use DateTimeZone;
use Spatie\Permission\Traits\HasRoles;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Contracts\Auditable;
use App\Enums\AttendanceType;
use App\Enums\LeavePermissionStatusType;
use App\Enums\SalaryTypeEnum;

class Employee extends Authenticatable implements Auditable
{
    use HasFactory, SoftDeletes, CrudTrait, HasRoles;

    use \OwenIt\Auditing\Auditable;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $dates = [
        'joined_at',
    ];

    protected $fillable = [
        'candidate_id',
        'position_id',
        'joined_at',
        'type',
        'is_default',
        'is_attendance_required',
        'salary_type_id',
    ];

    public $casts = [
        'is_default' => 'boolean',
        'is_attendance_required' => 'boolean'
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function profileDetail()
    {
        return $this->hasOneThrough(CvProfileDetail::class, Candidate::class, 'id', 'candidate_id', 'candidate_id', 'id');
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

    public function oneTimeShifts()
    {
        return $this->hasMany(EmployeeOneTimeShift::class);
    }

    public function recurringShifts()
    {
        return $this->hasMany(EmployeeRecurringShift::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_kustomer');
    }

    public function employeeSalaryTypes()
    {
        return $this->hasMany(EmployeeSalaryType::class);
    }

    public function getAllowanceSalaryTypes()
    {
        return $this->employeeSalaryTypes()->whereRelation('companySalaryType.salaryType', 'type', SalaryTypeEnum::allowance())->get();
    }

    public function getDeductionSalaryTypes()
    {
        return $this->employeeSalaryTypes()->whereRelation('companySalaryType.salaryType', 'type', SalaryTypeEnum::deduction())->get();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'id');
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class, 'id', 'candidate_id');
    }

    public function payslips()
    {
        return $this->hasMany(EmployeePayslip::class);
    }


    public function typeOfSalary()
    {
        $employeeSalaryTypes = $this->salaryTypes;
        if ($employeeSalaryTypes->isNotEmpty()) {
            return $employeeSalaryTypes->map(function ($employeeSalaryType) {
                return [
                    // 'salary_type_id' => $employeeSalaryType->salaryType->id,
                    // 'name' => $employeeSalaryType->salaryType->name,
                    // 'amount' => $employeeSalaryType->amount,
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
        return $this->candidate->phone_number;
    }

    public function getUserName()
    {
        if ($this->candidate) {
            return $this->candidate->name;
        }
    }

    public function interviewerDetail()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'position' => $this->position,
            'name' => $this->candidate->name
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
            'name' => $this->candidate->name,
            'company' => $this->company,
            'department' => $this->department->onlyNameAndId(),
            'level' => $this->level->onlyNameAndId(),
            'position' => $this->position->toCandidate(),
            'parent_id' => $this->position->parent_id,
            'joined_at' => $this->joined_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'one_time_shifts' => $this->oneTimeShifts,
            'recurring_shifts' => $this->recurringShifts
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
        return $this->hasMany(ShiftEmployee::class, 'employee_id', 'id');
    }

    public function resignations()
    {
        return $this->hasMany(EmployeeResignation::class, 'employee_id', 'id');
    }

    public function companySalaryTypes()
    {
        return $this->belongsToMany(CompanySalaryType::class, 'employees_salary_types', 'employee_id', 'company_salary_type_id')->with('salaryType')->withTimestamps();
    }

    public function getAllowanceEmployeeSalaryTypes()
    {
        return $this->companySalaryTypes()->whereRelation('salaryType', 'type', SalaryTypeEnum::allowance())->get();
    }

    public function getDeductionEmployeeSalaryTypes()
    {
        return $this->companySalaryTypes()->whereRelation('salaryType', 'type', SalaryTypeEnum::deduction())->get();
    }

    public function bankAccount()
    {
        return $this->hasOne(EmployeeBankAccount::class);
    }

    public function getOneTimeShifts($date)
    {
        $date = new Carbon($date);

        return QueryBuilder::for(EmployeeOneTimeShift::class)
            ->allowedIncludes([
                'employee'
            ])
            ->whereDate('date', $date->toDateString())
            ->where('employee_id', $this->id)
            ->with('shift')
            ->get();
    }

    public function getRecurringShifts($date)
    {
        $date = new Carbon($date);
        return EmployeeRecurringShift::where('day', $date->dayOfWeek)
            ->where('employee_id', $this->id)
            ->with('shift')
            ->get();
    }

    public function getShifts($date)
    {
        $date = new Carbon($date);
        $shifts = EmployeeOneTimeShift::whereDate('date', $date->toDateString())
            ->where('employee_id', $this->id)
            ->with('shift')
            ->get();
        if (!$shifts->isEmpty()) {
            return $shifts;
        }

        $getTodayDay = $date->dayOfWeek;
        $shifts = EmployeeRecurringShift::where('day', $getTodayDay)
            ->where('employee_id', $this->id)
            ->with('shift')
            ->get();
        if ($shifts) {
            return $shifts;
        }

        return null;
    }

    public function getShift($shiftId, $date = null)
    {
        $date = $date == null ? Carbon::now() : new Carbon($date);
        $shift = EmployeeOneTimeShift::whereDate('date', $date->toDateString())
            ->where('shift_id', $shiftId)
            ->where('employee_id', $this->id)
            ->with('shift')
            ->first();
        if ($shift) {
            return $shift;
        }

        $getTodayDay = $date->dayOfWeek;
        $shift = EmployeeRecurringShift::where('day', $getTodayDay)
            ->where('shift_id', $shiftId)
            ->where('employee_id', $this->id)
            ->with('shift')
            ->first();
        if ($shift) {
            return $shift;
        }

        return null;
    }

    public function getShiftId($date = null)
    {
        $date = $date == null ? Carbon::now() : new Carbon($date);
        $shift = EmployeeOneTimeShift::whereDate('date', $date->toDateString())
            ->where('employee_id', $this->id)
            ->with('shift')
            ->first();
        if ($shift) {
            return $shift->shift_id;
        }

        $getTodayDay = Carbon::now()->dayOfWeek;
        $shift = EmployeeRecurringShift::where('day', $getTodayDay)
            ->where('employee_id', $this->id)
            ->with('shift')
            ->first();
        if ($shift) {
            return $shift->shift_id;
        }

        return null;
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
        )->where('attendance_type_id', AttendanceType::clockIn())
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

    public function getCertainDateOneTimeShifts($date)
    {
        return $this->hasManyThrough(
            Shift::class,
            EmployeeOneTimeShift::class,
            'employee_id',
            'id',
            'id',
            'shift_id'
        )->whereDate('date', $date)->get();
    }

    public function getCertainDateRecurringShifts($date)
    {
        return $this->hasManyThrough(
            Shift::class,
            EmployeeRecurringShift::class,
            'employee_id',
            'id',
            'id',
            'shift_id'
        )->where('day', (new Carbon($date))->dayOfWeek)->get();
    }

    public function getAttendances($startDate, $endDate)
    {
        return $this->attendances()
            ->whereBetween('attendances.date', [$startDate, $endDate])
            ->get();
    }

    public function getAttendanceCount($startDate, $endDate)
    {
        return $this->getAttendances($startDate, $endDate)->count();
    }

    public function attendancePenalties()
    {
        return $this->hasManyDeep(
            AttendancePenalty::class,
            [Attendance::class, AttendanceDetail::class]
        );
    }

    public function getAttendancePenaltyTotalAmount($startDate, $endDate)
    {
        return $this->attendancePenalties()->whereBetween('attendances.created_at', [$startDate, $endDate])->dd();
    }

    public function leavePermissions()
    {
        return $this->hasMany(LeavePermission::class);
    }

    public function getLeavePermissionsByDateRange($startDate, $endDate)
    {
        return $this->leavePermissions()
            ->where('status', LeavePermissionStatusType::accepted())
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereDate('started_at', '>=', $startDate)
                    ->whereDate('ended_at', '<=', $endDate);
            })->orWhere(function ($query) use ($startDate, $endDate) {
                $query->whereDate('started_at', '>=', $startDate)
                    ->whereDate('started_at', '<=', $endDate);
            })->orWhere(function ($query) use ($startDate, $endDate) {
                $query->whereDate('ended_at', '>=', $startDate)
                    ->whereDate('ended_at', '<=', $endDate);
            })
            ->get();
    }
}
