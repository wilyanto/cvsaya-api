<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AttendancePenalty;
use App\Models\AttendanceType;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\Penalty;
use App\Models\Shift;
use App\Traits\ApiResponser;
use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;

class AttendanceController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'started_at' => [
                'date_format:Y-m-d\TH:i:s.v\Z',
                'required'
            ],
            'ended_at' => [
                'nullable',
                'date_format:Y-m-d\TH:i:s.v\Z'
            ],
        ]);

        $user = auth()->user();
        // dump($user);
        $employee = Employee::where('user_id', $user->id_kustomer)->firstOrFail();
        $attendanceTypes = AttendanceType::all();
        $data['employee'] = [
            'id' => $employee->id,
            'name' => $employee->profileDetail->first_name . ' ' . $employee->profileDetail->last_name,
        ];
        $attendance = [];
        $startedAt = new \DateTime($request->started_at, new DateTimeZone('Asia/Jakarta'));
        $endedAt = new \DateTime($request->ended_at, new DateTimeZone('Asia/Jakarta'));
        for ($date = $startedAt; $date <= $endedAt; $date->modify('+1 day')) {
            // dump($date);
            $shifts['date'] = $date->format('Y-m-d\TH:i:s.v\Z');
            $attendancesPerDays = null;
            $tempDate = new \DateTime($date->format('Y-m-d\TH:i:s.v\Z'), new DateTimeZone('Asia/Jakarta'));
            $interval = DateInterval::createFromDateString('+23 hour +59 minute + 59 second');
            $shift = $employee->getShift($date->format('Y-m-d\TH:i:s.v\Z'));

            if ($shift == null) {
                foreach ($attendanceTypes as $attendanceType) {
                    $shift[$attendanceType->name] = [
                        'checked_at' => null,
                        'duty_at' => null,
                        'penalty' => null,
                    ];
                }
                $shifts['shift'] = $shift;
                $attendances[] = $shifts;
                continue;
            }
            if ($shift->shift->clock_out < $shift->shift->clock_in) {
                $interval = DateInterval::createFromDateString('+1 day +23 hour +59 minute + 59 second');
            }
            $shift = null;
            $endDayOfDate =  $tempDate->add($interval);
            $attendancesPerDays = Attendance::whereBetween(
                'checked_at',
                [
                    $date->format('Y-m-d\TH:i:s.v\Z'),
                    $endDayOfDate->format('Y-m-d\TH:i:s.v\Z'),
                ]
            )->where('employee_id', $employee->id)->get();
            foreach ($attendanceTypes as $attendanceType) {
                if (
                    count($attendancesPerDays) &&
                    ($employee->isWorkToday($date->format('Y-m-d\TH:i:s.v\Z'))
                        || $endDayOfDate->format('Y-m-d\TH:i:s.v\Z')
                    )
                ) {
                    $attendance = collect($attendancesPerDays);
                    if ($attendanceType->id == AttendanceType::CLOCK_IN_ID) {
                        $attendance = $attendance->where('attendance_type_id', $attendanceType->id)->first();
                    } elseif ($attendanceType->id == AttendanceType::CLOCK_OUT_ID) {
                        $attendance = $attendance->where('attendance_type_id', $attendanceType->id)->first();
                    } elseif ($attendanceType->id == AttendanceType::BREAK_STARTED_AT_ID) {
                        $attendance = $attendance->where('attendance_type_id', $attendanceType->id)->first();
                    } elseif ($attendanceType->id == AttendanceType::BREAK_ENDED_AT_ID) {
                        $attendance = $attendance->where('attendance_type_id', $attendanceType->id)->first();
                    }
                    if ($attendance) {
                        $shift[$attendanceType->name] = [
                            'checked_at' => $attendance->checked_at,
                            'duty_at' => $attendance->duty_at,
                            'penalty' => $attendance->penalty->amount,
                        ];
                    } else {
                        $shift[$attendanceType->name] = [
                            'checked_at' => null,
                            'duty_at' => null,
                            'penalty' => null,
                        ];
                    }
                } else {
                    $shift[$attendanceType->name] = [
                        'checked_at' => null,
                        'duty_at' => null,
                        'penalty' => null,
                    ];
                }
            }
            $shifts['shift'] = $shift;
            $attendances[] = $shifts;
        }
        $data['attendance'] = $attendances;
        return $this->showOne($data);
    }

    public function indexAttendanceType(Request $request)
    {
        $attendanceTypes = AttendanceType::all();

        return $this->showAll($attendanceTypes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'file' => 'file|required',
            'type' => [
                'integer',
                'exists:App\Models\AttendanceType,id',
            ],
            'note' => 'string|nullable',
        ]);
        $employee = Employee::where('user_id', $user->id_kustomer)->firstOrFail();
        $day = new \DateTime('today', new DateTimeZone('Asia/Jakarta'));
        $yesterday = new \DateTime('yesterday', new DateTimeZone('Asia/Jakarta'));
        $shift = $employee->getShift($day->format('Y-m-d\TH:i:s.u\Z'));
        $shiftYesterday = $employee->getShift($yesterday->format('Y-m-d\TH:i:s.u\Z'));
        $now = new \DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $attendanceType = AttendanceType::findOrfail($request->type);
        $documentType = DocumentType::where('name', 'attendances')->firstOrFail();
        $columnName = $attendanceType->name;
        if ($this->isExistsAttendance($now, $attendanceType, $employee, $day)) {
            return $this->errorResponse('you have attend ' . $attendanceType->name . ' already', 422, 42201);
        }
        if ($request->type == AttendanceType::CLOCK_IN_ID) {
            if ($shift) {
                $penaltyAmount = $this->getPenaltyValue($shift->shift, $attendanceType, $now);
                $this->createAttendance($shift, $now, $attendanceType, $penaltyAmount, $employee);
            } else {
                $this->createAttendance($shift, $now, $attendanceType, null, $employee);
            }
        } else {
            if ($shiftYesterday && ($shiftYesterday->shift->clock_out < $shiftYesterday->shift->clock_in)) {
                if ($employee->isWorkToday($yesterday->format('Y-m-d\TH:i:s.u\Z'))) {
                    $penaltyAmount = $this->getPenaltyValue($shiftYesterday->shift, $attendanceType, $now);
                    $this->createAttendance($shiftYesterday, $now, $attendanceType, $penaltyAmount, $employee);
                } else {
                    return $this->errorResponse('You have to attend clock_in first or You need to validated with Security', 422, 42201);
                }
            } elseif (!$employee->isWorkToday($day->format('Y-m-d\TH:i:s.u\Z'))) {
                return $this->errorResponse('You have to attend clock_in first or You need to validated with Security', 422, 42202);
            } else {
                if (!$employee->isWorkToday($day->format('Y-m-d\TH:i:s.u\Z'))) {
                    return $this->errorResponse('You have to attend clock_in first or You need to validated with Security', 422, 42203);
                }
                if ($shift) {
                    if ($attendanceType->id == AttendanceType::BREAK_STARTED_AT_ID) {

                        $time = new \DateTime($shift->shift->$columnName, new DateTimeZone('Asia/Jakarta'));
                        if ($time > $now) {
                            return $this->errorResponse('Break Time not started yet', 422, 42202);
                        }
                    } elseif ($attendanceType->id == AttendanceType::BREAK_ENDED_AT_ID) {
                        $attendance = Attendance::whereBetween(
                            'checked_at',
                            [
                                $day,
                                $now,
                            ]
                        )->where('attendance_type_id', AttendanceType::BREAK_STARTED_AT_ID)
                            ->first();
                        if (!$attendance) {
                            return $this->errorResponse('You have to attend ' . AttendanceType::BREAK_STARTED_AT . ' first', 422, 42201);
                        }
                    }
                    $penaltyAmount = $this->getPenaltyValue($shift->shift, $attendanceType, $now);
                    $this->createAttendance($shift, $now, $attendanceType, $penaltyAmount, $employee);
                } else {
                    $this->createAttendance($shift, $now, $attendanceType, null, $employee);
                }
            }
        }
        $timeDocument = date('Y-m-d_H-i-s', time());
        $randomNumber =  CvDocumentController::random4Digits();

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($request->file('file')); // variable
        $extension = CvDocumentController::getExtension($mimeType);

        $filenameWithoutExtenstion = $timeDocument . '_' . $user->id_kustomer . '_' . $randomNumber;
        $filename = $filenameWithoutExtenstion . '.' . $extension;

        $request->file('file')->storeAs('public/attendances/' . $attendanceType->name, $filename);
        Document::create([
            'file_name' => $filenameWithoutExtenstion,
            'user_id' => $user->id_kustomer,
            'mime_type' => $mimeType,
            'type_id' => $documentType->id,
            'original_file_name' => $request->file->getClientOriginalName(),
        ]);
        return $this->showOne('Success');
    }

    public function isExistsAttendance(DateTime $now, AttendanceType $type, Employee $employee, DateTime $day)
    {
        $exists = false;
        $attendance = Attendance::whereBetween(
            'checked_at',
            [
                $day,
                $now
            ]
        )->where('attendance_type_id', $type->id)
            ->where('employee_id', $employee->id)
            ->first();
        if ($attendance) {
            $exists = true;
        }
        return $exists;
    }

    public function createAttendance($shift, DateTime $now, AttendanceType $type, $penalty, Employee $employee)
    {
        $columnName = $type->name;
        // $formatInsert = new \DateTimeInterface('RFC822');
        $duty = new \DateTime($shift->shift->$columnName, new DateTimeZone('Asia/Jakarta'));
        $attendance = Attendance::create([
            'checked_at' => $now->format(DateTimeInterface::ISO8601),
            'employee_id' => $employee->id,
            'duty_at' => $duty->format(DateTimeInterface::ISO8601),
            'attendance_type_id' => $type->id,
            'validated_at' => $type->id == AttendanceType::CLOCK_IN_ID ? null : $now->format(DateTimeInterface::RFC822)
        ]);

        if ($penalty) {
            $this->createPenalty($penalty, $attendance);
        }
    }

    public static function createPenalty($penalty, Attendance $attendance)
    {
        AttendancePenalty::create([
            'amount' => $penalty->amount,
            'attendance_id' => $attendance->id,
            'penalty_id' => $penalty->id,
        ]);
    }

    public static function getPenaltyValue(Shift $shift, AttendanceType $type, DateTime $now)
    {
        $penalty = null;
        $columnName = $type->name;

        $dutyAt = new \DateTime($shift->$columnName, new DateTimeZone('Asia/Jakarta'));
        if ($type->id == AttendanceType::CLOCK_IN_ID) {

            if ($dutyAt <= $now) {

                $interval =  $dutyAt->diff($now);
                return Penalty::where('attendance_types_id', $type->id)
                    ->where('passing_at', '>=', $interval->format('H:i:s'))
                    ->orderBy('passing_at', 'DESC')
                    ->first();
            }
        } elseif ($type->id == AttendanceType::CLOCK_OUT_ID) {
            if ($dutyAt >= $now) {
                $interval = $now->diff($dutyAt);
                return Penalty::where('attendance_types_id', $type->id)
                    ->where('passing_at', '>=', $interval->format('H:i:s'))
                    ->orderBy('passing_at', 'DESC')
                    ->first();
            }
        } elseif ($type->id == AttendanceType::BREAK_STARTED_AT_ID) {
            if ($dutyAt <= $now) {

                $interval =  $dutyAt->diff($now);
                return Penalty::where('attendance_types_id', AttendanceType::BREAK_ENDED_AT_ID)
                    ->where('passing_at', '<=', $interval->format('H:i:s'))
                    ->orderBy('passing_at', 'DESC')
                    ->first();
            }
        } elseif ($type->id == AttendanceType::BREAK_ENDED_AT_ID) {
            $day = new \DateTime('today', new DateTimeZone('Asia/Jakarta'));
            $attendance = Attendance::whereBetween(
                'checked_at',
                [
                    $day,
                    $now,
                ]
            )->where('attendance_type_id', AttendanceType::BREAK_STARTED_AT_ID)
                ->first();
            if ($attendance) {
                $dutyAt = new \DateTime($attendance->checked_at, new DateTimeZone('Asia/Jakarta'));
            }
            if ($dutyAt >= $now) {
                $interval =  $dutyAt->diff($now);
                return Penalty::where('attendance_types_id', $type->id)
                    ->where('passing_at', '>=', $interval->format('H:i:s'))
                    ->orderBy('passing_at', 'DESC')
                    ->first();
            }
        }
        return $penalty;
    }

    public function validationBySecurity(Request $request)
    {
        $request->validate([
            'employees' => ['array', 'required'],
        ]);
        foreach ($request->employees as $employee) {
            $employee = Employee::findOrFail($employee);
            $time =  new \DateTime('now', new DateTimeZone('Asia/Jakarta'));
            $interval = DateInterval::createFromDateString('+5 minute');
            $time->add($interval);
            $attendance = Attendance::where('checked_at', '<=', $time->format('Y-m-d\TH:i:s.u\Z'))->firstOrFail();
            $attendance->validated_at = time();
            $attendance->save();
        }

        return $this->showOne('Success');
    }
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
