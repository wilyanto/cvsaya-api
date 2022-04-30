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
use App\Models\ShiftEmployee;
use App\Models\ShiftPositions;
use App\Traits\ApiResponser;
use DateInterval;
use DateTime;
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
                'date_format:Y-m-d\TH:i:s.u\Z',
                'required'
            ],
            'ended_at' => [
                'nullable',
                'date_format:Y-m-d\TH:i:s.u\Z'
            ],
        ]);

        $user = auth()->user();
        // dump($user);
        $employee = Employee::where('user_id', $user->id_kustomer)->firstOrFail();
        $data['employee'] = [
            'id' => $employee->id,
            'name' => $employee->profileDetail->first_name . ' ' . $employee->profileDetail->last_name,
        ];
        $startedAt = new \DateTime($request->started_at, new DateTimeZone('Asia/Jakarta'));
        $endedAt = new \DateTime($request->ended_at, new DateTimeZone('Asia/Jakarta'));

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
    // public function store(Request $request)
    // {
    //     $user = auth()->user();

    //     $request->validate([
    //         'file' => 'file|required',
    //         'type' => [
    //             'integer',
    //             'exists:App\Models\AttendanceType,id',
    //         ],
    //         'note' => 'string|nullable',
    //     ]);
    //     $attendanceType = AttendanceType::findOrFail($request->type);
    //     $documentType = DocumentType::where('name', 'attendances')->firstOrFail();
    //     $employee = Employee::where('user_id', $user->id_kustomer)->firstOrFail();

    //     $time = new \DateTime("now", new DateTimeZone('Asia/Jakarta'));
    //     $date =  new \DateTime("today", new DateTimeZone('Asia/Jakarta'));
    //     $startDate = $date->format('Y-m-d\TH:i:s.u\Z');
    //     $interval = DateInterval::createFromDateString('+23 hour +59 minute + 59 second');
    //     $endDate = $date->add($interval)->format('Y-m-d\TH:i:s.u\Z');
    //     $attendances = Attendance::whereBetween(
    //         'duty_at',
    //         [
    //             $startDate,
    //             $endDate
    //         ]
    //     )->whereNotNull('validated_at')->get();
    //     $shift = ShiftEmployee::whereBetween(
    //         'date',
    //         [
    //             $startDate,
    //             $endDate
    //         ]
    //     )->first();
    //     if (!$shift) {
    //         $getTodayDay = $time->format('N');
    //         $shift = ShiftPositions::where('day', $getTodayDay)->where('position_id', $employee->position->id)->first();
    //     }

    //     $columnName = $attendanceType->name;
    //     $dutyAt = $shift->shift->$columnName;
    //     if ($columnName == 'break_ended_at') {
    //         $attendance = Attendance::whereBetween(
    //             'duty_at',
    //             [
    //                 $startDate,
    //                 $endDate
    //             ]
    //         )->where('attendance_type_id', 2)->first();
    //         if ($attendance) {
    //             $dutyAt = date('Y-m-d\TH:i:s.u\Z', strtotime($attendance->checked_at . ' +' . $shift->shift->break_duration . 'hour'));
    //         }
    //     }

    //     if ($attendanceType->id >= AttendanceType::CLOCKOUTID) {
    //         if ($attendances->where('attendance_type_id', AttendanceType::CLOCKIN)->first() != null && $dutyAt) {
    //             return $this->errorResponse('You have to attend clock_in first', 422, 42201);
    //         }
    //     }
    //     if (!$attendances->where('attendance_type_id', $attendanceType->id)->first()) {

    //         $timeDocument = date('Y-m-d_H-i-s', time());
    //         $randomNumber =  CvDocumentController::random4Digits();

    //         $finfo = new \finfo(FILEINFO_MIME_TYPE);
    //         $mimeType = $finfo->file($request->file('file')); // variable
    //         $extension = CvDocumentController::getExtension($mimeType);

    //         $filenameWithoutExtenstion = $timeDocument . '_' . $user->id_kustomer . '_' . $randomNumber;
    //         $filename = $filenameWithoutExtenstion . '.' . $extension;

    //         $request->file('file')->storeAs('public/attendances/' . $attendanceType->name, $filename);
    //         Document::create([
    //             'file_name' => $filenameWithoutExtenstion,
    //             'user_id' => $user->id_kustomer,
    //             'mime_type' => $mimeType,
    //             'type_id' => $documentType->id,
    //             'original_file_name' => $request->file->getClientOriginalName(),
    //         ]);
    //         Attendance::create([
    //             'checked_at' => $time,
    //             'duty_at' => $dutyAt,
    //             'employee_id' => $employee->id,
    //             'attendance_type_id' => $attendanceType->id,
    //             'validated_at' => $attendanceType->name == AttendanceType::CLOCKIN ? null : time(),
    //             'note' => $request->note,
    //         ]);

    //         $employee->getShifts($startDate, $endDate, $attendanceType->name);
    //         return $this->showOne('Success');
    //     } else {
    //         return $this->errorResponse('You have attend ' . $attendanceType->name . ' already', 422, 42202);
    //     }
    // }

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
        // $timeDocument = date('Y-m-d_H-i-s', time());
        // $randomNumber =  CvDocumentController::random4Digits();

        // $finfo = new \finfo(FILEINFO_MIME_TYPE);
        // $mimeType = $finfo->file($request->file('file')); // variable
        // $extension = CvDocumentController::getExtension($mimeType);

        // $filenameWithoutExtenstion = $timeDocument . '_' . $user->id_kustomer . '_' . $randomNumber;
        // $filename = $filenameWithoutExtenstion . '.' . $extension;

        // $request->file('file')->storeAs('public/attendances/' . $attendanceType->name, $filename);
        // Document::create([
        //     'file_name' => $filenameWithoutExtenstion,
        //     'user_id' => $user->id_kustomer,
        //     'mime_type' => $mimeType,
        //     'type_id' => $documentType->id,
        //     'original_file_name' => $request->file->getClientOriginalName(),
        // ]);
        return $employee;
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
        $duty = new \DateTime($shift->shift->$columnName, new DateTimeZone('Asia/Jakarta'));
        $attendance = Attendance::create([
            'checked_at' => $now->format('Y-m-d\TH:i:s.u\Z'),
            'employee_id' => $employee->id,
            'duty_at' => $duty->format('Y-m-d\TH:i:s.u\Z'),
            'attendance_type_id' => $type->id,
            'validated_at' => $type->id == AttendanceType::CLOCK_IN_ID ? null : $now->format('Y-m-d\TH:i:s.u\Z')
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
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
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
