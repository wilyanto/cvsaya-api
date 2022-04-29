<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Position;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use DateInterval;
use DateTime;
use DateTimeZone;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Contracts\Auditable;

class Employee extends Authenticatable implements Auditable
{
    use HasFactory, SoftDeletes;
    use CrudTrait;
    use HasRoles;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'employees';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $connection = 'mysql';

    protected $dates = [
        'joined_at',
    ];

    public $fillable = [
        'user_id',
        'position_id',
        'joined_at',
        'employment_type_id',
        'salary_type_id',
    ];

    public function position()
    {
        return $this->hasOne(Position::class, 'id', 'position_id')->withDefault();
    }

    public function profileDetail()
    {
        return $this->hasOne(CvProfileDetail::class, 'user_id', 'user_id')->withDefault();
    }

    public function company()
    {
        return $this->hasOneThrough(Company::class, Position::class, 'id', 'id', 'position_id', 'company_id')->withDefault();
    }

    public function level()
    {
        return $this->hasOneThrough(Level::class, Position::class, 'id', 'id', 'position_id', 'level_id')->withDefault();
    }

    public function department()
    {
        return $this->hasOneThrough(Department::class, Position::class, 'id', 'id', 'position_id', 'department_id')->withDefault();
    }

    public function employmentType()
    {
        return $this->hasOne(EmploymentType::class, 'id', 'employment_type_id')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_kustomer')->withDefault();
    }

    public function salaryTypes()
    {
        return $this->hasMany(EmployeeSalaryType::class);
    }

    public function typeOfSalary()
    {
        // $salaries = EmployeeSalaryType::where('employee_id',$this->id)->get();
        $salaries = $this->salaryTypes;
        if (count($salaries)) {
            $salaries = $salaries->map(function ($item) {
                return [
                    'salary_type_id' => $item->salaryType->id,
                    'name' => $item->salaryType->name,
                    'amount' => $item->amount,
                ];
            });
        }

        return $salaries;
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

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'position' => $this->position->toArrayEmployee(),
            'first_name' => $this->profileDetail->first_name,
            'last_name' => $this->profileDetail->last_name,
            'salary' => $this->salary,
        ];
    }
    public function toArrayEmployee()
    {
        return [
            'id' => $this->id,
            'name' => $this->profileDetail->first_name . ' ' . $this->profileDetail->last_name,
            'employment_type' => $this->employmentType,
            // 'salary_types' => $this->typeOfSalary(),
            'company' => $this->company,
            'department' => $this->department->onlyNameAndId(),
            'level' => $this->level->onlyNameAndId(),
            'position' => $this->position->toCandidate(),
            'parent_id' => $this->parent_id,
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

    public function getShifts($startedAt, $endedAt, $penaltyType = null)
    {
        $startedAt = new \DateTime($startedAt, new DateTimeZone('Asia/Jakarta'));
        $endedAt = new \DateTime($endedAt, new DateTimeZone('Asia/Jakarta'));
        $shifts = [];
        $data = [];
        $attendanceTypes = AttendanceType::all();
        $penalties = Penalty::all();
        $attendancesFull = Attendance::where('employee_id', $this->id)->get();
        for ($date = $startedAt; $date <= $endedAt; $date->modify('+1 day')) {
            $startDayOfDate =  $date->format('Y-m-d\TH:i:s.u\Z');
            $tempDate = new \DateTime($date->format('Y-m-d\TH:i:s.u\Z'), new DateTimeZone('Asia/Jakarta'));
            $data = [];
            $data['date'] = $startDayOfDate;
            $interval = DateInterval::createFromDateString('+23 hour +59 minute + 59 second');
            $endDayOfDate =  $tempDate->add($interval)->format('Y-m-d\TH:i:s.u\Z');
            $attendancesPerDays = $attendancesFull->whereBetween(
                'checked_at',
                [
                    date('Y-m-d\TH:i:s.u\Z', strtotime($startDayOfDate . '-14 hours')),
                    date('Y-m-d\TH:i:s.u\Z', strtotime($endDayOfDate . '-14 hours'))
                ]
            )->where('employee_id', $this->id)->all();
            foreach ($attendanceTypes as $attendanceType) {
                $attendancesPerDays = collect($attendancesPerDays);
                $attendance = $attendancesPerDays->where('attendance_type_id', $attendanceType->id)->first();
                if ($attendance) {
                    $checkedAt = $attendance ? new \DateTime($attendance->checked_at, new DateTimeZone('Asia/Jakarta')) : null;
                    $dutyAt =  $attendance ? new \DateTime($attendance->duty_at, new DateTimeZone('Asia/Jakarta')) : null;
                    $data[$attendanceType->name] = [
                        'checked_at' => $checkedAt ? $checkedAt->format('Y-m-d\TH:i:s.u\Z') : null,
                        'duty_at' => $attendance ? $dutyAt->format('Y-m-d\TH:i:s.u\Z') : null,
                        'penalty' => $this->getPenaltiesValue(
                            $attendance,
                            $attendanceType,
                            $penaltyType == $attendanceType->name ? true : false,
                            $penalties
                        ),
                    ];
                } else {
                    $shift = ShiftEmployee::whereBetween(
                        'date',
                        [
                            $startDayOfDate,
                            $endDayOfDate
                        ]
                    )->first();
                    if (!$shift) {
                        $getTodayDay = $date->format('N');
                        $shift = ShiftPositions::where('day', $getTodayDay)->where('position_id', $this->position->id)->first();
                    }

                    $columnName = $attendanceType->name;
                    $dutyAt = $shift->shift->$columnName;
                    if ($columnName == 'break_ended_at') {
                        $attendanceTemp = Attendance::whereBetween(
                            'duty_at',
                            [
                                $startDayOfDate,
                                $endDayOfDate
                            ]
                        )->where('attendance_type_id', 2)->first();
                        if ($attendanceTemp) {
                            $dutyAt = date('Y-m-d\TH:i:s.u\Z', strtotime($attendance->checked_at . ' +' . $shift->shift->break_duration . 'hour'));
                        }
                    }
                    if (time() <= strtotime($dutyAt)) {
                        $data[$attendanceType->name] = [
                            'checked_at' => null,
                            'duty_at' => null,
                            'penalty' => null
                        ];
                    } else {
                        $isCreateNewPenalties =  $penaltyType == $attendanceType->name ? true : false;
                        $data[$attendanceType->name] = [
                            'checked_at' => null,
                            'duty_at' => null,
                            'penalty' =>  $this->getPenaltiesValue(
                                $attendance,
                                $attendanceType,
                                $penaltyType == $isCreateNewPenalties,
                                $penalties
                            ),
                        ];
                    }
                }
            }

            $shifts[] = $data;
        }
        return $shifts;
    }

    public static function getPenaltiesValue(
        $attendance,
        AttendanceType $type,
        bool $isCreateNewPenalties,
        $penalties
    ) {
        if ($type->name == AttendanceType::CLOCKIN) {
            if ($attendance && $attendance->validated_at != null) {
                $checkedAt = strtotime($attendance->checked_at);
                if ($checkedAt > strtotime($attendance->duty_at)) {
                    $late = date('H:i:s', $checkedAt - strtotime($attendance->duty_at));
                    $penaltiesByTypes =  $penalties->where('attendance_types_id', $type->id)->sortByDesc('passing_at');
                    foreach ($penaltiesByTypes as $penalty) {
                        if (strtotime($penalty->passing_at) <= strtotime($late)) {
                            if ($isCreateNewPenalties && $penalty->amount) {
                                AttendancePenalty::create([
                                    'amount' => $penalty->amount,
                                    'attendance_id' => $attendance->id,
                                    'penalty_id' => $penalty->id,
                                ]);
                            }
                            return $penalty->amount;
                        }
                    }
                }
            } else {
                $penalty =  $penalties->where('attendance_types_id', $type->id)->sortBy(['passing_at', 'desc'])->first();
                if ($penalty) {

                    if ($isCreateNewPenalties && $penalty->amount && $attendance->validated_at != null) {
                        AttendancePenalty::create([
                            'amount' => $penalty->amount,
                            'attendance_id' => null,
                            'penalty_id' => $penalty->id,
                        ]);
                    }
                    return $penalty->amount;
                }
            }
        } elseif ($type->name == AttendanceType::CLOCKOUT) {
            if ($attendance && $attendance->validated_at != null) {
                $checkedAt = strtotime($attendance->checked_at);
                if ($checkedAt < strtotime($attendance->duty_at)) {
                    $late =   date('H:i:s', strtotime($attendance->duty_at) - $checkedAt);
                    // dump($late);
                    $penaltiesByTypes =  $penalties->where('attendance_types_id', $type->id)->sortBy(['passing_at', 'desc']);
                    foreach ($penaltiesByTypes as $penalty) {
                        if (strtotime($penalty->passing_at) <= strtotime($late)) {
                            if ($isCreateNewPenalties && $penalty->amount) {
                                AttendancePenalty::create([
                                    'amount' => $penalty->amount,
                                    'attendance_id' => $attendance->id,
                                    'penalty_id' => $penalty->id,
                                ]);
                            }
                            return $penalty->amount;
                        }
                    }
                }
            } else {
                $penalty =  $penalties->where('attendance_types_id', $type->id)->sortBy(['passing_at', 'desc'])->first();
                if ($penalty) {
                    if ($isCreateNewPenalties && $penalty->amount && $attendance->validated_at != null) {
                        AttendancePenalty::create([
                            'amount' => $penalty->amount,
                            'attendance_id' => null,
                            'penalty_id' => $penalty->id,
                        ]);
                    }
                    return $penalty->amount;
                }
            }
        } elseif ($type->name == AttendanceType::BREAKSTARTEDAT) {
            if ($attendance && $attendance->validated_at != null) {
                $checkedAt = strtotime($attendance->checked_at);
                if ($checkedAt > strtotime($attendance->duty_at)) {
                    $late = date('H:i:s', $checkedAt - strtotime($attendance->duty_at));
                    $penaltiesByTypes =  $penalties->where('attendance_types_id', $type->id)->sortBy(['passing_at', 'desc']);
                    foreach ($penaltiesByTypes as $penalty) {
                        if (strtotime($penalty->passing_at) <= strtotime($late)) {
                            if ($isCreateNewPenalties && $penalty->amount) {
                                AttendancePenalty::create([
                                    'amount' => $penalty->amount,
                                    'attendance_id' => $attendance->id,
                                    'penalty_id' => $penalty->id,
                                ]);
                            }
                            return $penalty->amount;
                        }
                    }
                }
            } else {
                $penalty =  $penalties->where('attendance_types_id', $type->id)->sortBy(['passing_at', 'desc'])->first();
                if ($penalty) {
                    if ($isCreateNewPenalties && $penalty->amount && $attendance->validated_at != null) {
                        AttendancePenalty::create([
                            'amount' => $penalty->amount,
                            'attendance_id' => null,
                            'penalty_id' => $penalty->id,
                        ]);
                    }
                    return $penalty->amount;
                }
            }
        } elseif ($type->name == AttendanceType::BREAKENDEDAT) {
            if ($attendance && $attendance->validated_at != null) {
                $checkedAt = strtotime($attendance->checked_at);
                if ($checkedAt > strtotime($attendance->duty_at)) {
                    $late = date('H:i:s', $checkedAt - strtotime($attendance->duty_at));
                    $penaltiesByTypes =  $penalties->where('attendance_types_id', $type->id)->sortBy(['passing_at', 'desc']);
                    foreach ($penaltiesByTypes as $penalty) {
                        if (strtotime($penalty->passing_at) <= strtotime($late)) {
                            if ($isCreateNewPenalties && $penalty->amount) {
                                AttendancePenalty::create([
                                    'amount' => $penalty->amount,
                                    'attendance_id' => $attendance->id,
                                    'penalty_id' => $penalty->id,
                                ]);
                            }
                            return $penalty->amount;
                        }
                    }
                }
            } else {
                $penalty =  $penalties->where('attendance_types_id', $type->id)->sortBy(['passing_at', 'desc'])->first();
                if ($penalty) {
                    if ($isCreateNewPenalties && $penalty->amount && $attendance->validated_at != null) {
                        AttendancePenalty::create([
                            'amount' => $penalty->amount,
                            'attendance_id' => null,
                            'penalty_id' => $penalty->id,
                        ]);
                    }
                    return $penalty->amount;
                }
            }
        }
        return 0;
    }
}
