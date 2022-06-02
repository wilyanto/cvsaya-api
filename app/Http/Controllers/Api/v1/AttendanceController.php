<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AttendancePenalty;
use App\Enums\AttendanceType;
use App\Models\AttendanceCompanyGroup;
use App\Models\AttendanceEmployee;
use App\Models\AttendanceQrCode;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\EmployeeOneTimeShift;
use App\Models\EmployeeRecurringShift;
use App\Models\OutsideRadiusAttendance;
use App\Models\Penalty;
use App\Models\Shift;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Illuminate\Support\Facades\Storage;
use PDO;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Position;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

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
        $candidate = Candidate::where('user_id', $user->id_kustomer)->firstOrFail();
        $employee = Employee::where('candidate_id', $candidate->id)->firstOrFail();

        // TODO : Fix Profile Detail
        $data['employee'] = [
            'id' => $employee->id,
            'name' => $employee->candidate->name,
        ];
        $attendance = [];
        $startedAt = new \DateTime($request->started_at, new DateTimeZone('Asia/Jakarta'));
        $endedAt = new \DateTime($request->ended_at, new DateTimeZone('Asia/Jakarta'));
        for ($date = $startedAt; $date <= $endedAt; $date->modify('+1 day')) {
            // dd($date);
            $shifts['date'] = $date->format('Y-m-d\TH:i:s.v\Z');
            // dd($shifts);
            $attendancesPerDays = null;
            $tempDate = Carbon::now('Asia/Jakarta');
            $interval = DateInterval::createFromDateString('+23 hour +59 minute + 59 second');
            $employeeShifts = $employee->getShifts($date->format('Y-m-d\TH:i:s.v\Z'));
            return $this->showAll($employeeShifts);
        }
        // $attendances =

        //     if ($shift == null) {
        //         foreach ($attendanceTypes as $attendanceType) {
        //             $shift[$attendanceType->name] = [
        //                 'checked_at' => null,
        //                 'duty_at' => null,
        //                 'penalty' => null,
        //             ];
        //         }
        //         $shifts['shift'] = $shift;
        //         $attendances[] = $shifts;
        //         continue;
        //     }
        //     if ($shift->shift->clock_out < $shift->shift->clock_in) {
        //         $interval = DateInterval::createFromDateString('+1 day +23 hour +59 minute + 59 second');
        //     }
        //     $shift = null;
        //     $endDayOfDate =  $tempDate->add($interval);
        //     $attendancesPerDays = Attendance::whereBetween(
        //         'checked_at',
        //         [
        //             $date->format('Y-m-d\TH:i:s.v\Z'),
        //             $endDayOfDate->format('Y-m-d\TH:i:s.v\Z'),
        //         ]
        //     )->where('employee_id', $employee->id)->get();
        //     foreach ($attendanceTypes as $attendanceType) {
        //         if (
        //             count($attendancesPerDays) &&
        //             ($employee->isWorkToday($date->format('Y-m-d\TH:i:s.v\Z'))
        //                 || $endDayOfDate->format('Y-m-d\TH:i:s.v\Z')
        //             )
        //         ) {
        //             $attendance = collect($attendancesPerDays);
        //             if ($attendanceType->id == AttendanceType::CLOCK_IN_ID) {
        //                 $attendance = $attendance->where('attendance_type_id', $attendanceType->id)->first();
        //             } elseif ($attendanceType->id == AttendanceType::CLOCK_OUT_ID) {
        //                 $attendance = $attendance->where('attendance_type_id', $attendanceType->id)->first();
        //             } elseif ($attendanceType->id == AttendanceType::BREAK_STARTED_AT_ID) {
        //                 $attendance = $attendance->where('attendance_type_id', $attendanceType->id)->first();
        //             } elseif ($attendanceType->id == AttendanceType::BREAK_ENDED_AT_ID) {
        //                 $attendance = $attendance->where('attendance_type_id', $attendanceType->id)->first();
        //             }
        //             if ($attendance) {
        //                 $shift[$attendanceType->name] = [
        //                     'checked_at' => $attendance->checked_at,
        //                     'duty_at' => $attendance->duty_at,
        //                     'penalty' => $attendance->penalty->amount,
        //                 ];
        //             } else {
        //                 $shift[$attendanceType->name] = [
        //                     'checked_at' => null,
        //                     'duty_at' => null,
        //                     'penalty' => null,
        //                 ];
        //             }
        //         } else {
        //             $shift[$attendanceType->name] = [
        //                 'checked_at' => null,
        //                 'duty_at' => null,
        //                 'penalty' => null,
        //             ];
        //         }
        //     }
        //     $shifts['shift'] = $shift;
        //     $attendances[] = $shifts;
        // }
        // $data['attendance'] = $attendances;
        // return $this->showOne($data);
    }


    // public function indexAttendanceType(Request $request)
    // {
    //     $attendanceTypes = AttendanceType::all();

    //     return $this->showAll($attendanceTypes);
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */

    public function store(Request $request)
    {
        $user = auth();
        $request->validate([
            'file' => 'file|required|mimes:jpeg,png',
            'attendance_type' => 'string|required',
            'longitude' => 'required',
            'latitude' => 'required',
            'attendance_qr_code_id' => 'required|exists:attendance_qr_codes,id',
            'outside_radius_note' => 'string',
            'penalty_note' => 'string',
            'shift_id' => 'required|exists:shifts,id'
        ]);

        $shiftId = $request->shift_id;
        $isParentCompany = false;
        $attendanceQRcode = AttendanceQrCode::where('id', $request->attendance_qr_code_id)->firstOrFail();
        $companyId = $attendanceQRcode->company_id;

        $candidate = Candidate::where('user_id', $user->id())->first();
        if (AttendanceCompanyGroup::where('candidate_id', $candidate->id)->count() != 0) {
            $parentCompanyId = 0;
            $parentCompany = AttendanceCompanyGroup::where('candidate_id', $candidate->id)
                ->where('company_parent_id', $companyId)
                ->first();
            if ($parentCompany) {
                $parentCompanyId = $parentCompany->company_parent_id;
            }
            $isParentCompany = $companyId == $parentCompanyId;
        }
        $positionIds = Position::where('company_id', $companyId)->pluck('id');
        $employee = Employee::where(
            'candidate_id',
            $candidate->id
        )->whereIn('position_id', $positionIds)->first();

        if (!$employee) {
            return $this->errorResponse('Employee Not Found', 422, 42200);
        }

        if (!$employee->getShift($shiftId)) {
            return $this->errorResponse('No Shift registered', 422, 42201);
        }

        $attendanceType = $request->attendance_type;
        $employeeShift = $employee->getShift($shiftId)->shift;
        $shiftTime = new Carbon($employeeShift->$attendanceType);

        // handle if blocked

        if (
            $attendanceType == AttendanceType::clockIn() &&
            Attendance::where('attendance_type', $attendanceType)
            ->where('shift_id', $shiftId)
            ->where('employee_id', $employee->id)
            ->whereDate('scheduled_at', today())
            ->exists()
        ) {
            return $this->errorResponse('Already Clock In', 422, 42200);
        }

        if (
            $attendanceType == AttendanceType::breakStartedAt() &&
            Attendance::where('attendance_type', $attendanceType)
            ->where('shift_id', $shiftId)
            ->where('employee_id', $employee->id)
            ->whereDate('scheduled_at', today())
            ->exists()
        ) {
            return $this->errorResponse('Already Scan Break Out', 422, 42201);
        }

        if (
            $attendanceType == AttendanceType::breakEndedAt() &&
            Attendance::where('attendance_type', $attendanceType)
            ->where('shift_id', $shiftId)
            ->where('employee_id', $employee->id)
            ->whereDate('scheduled_at', today())
            ->exists()
        ) {
            return $this->errorResponse('Already Scan Break In', 422, 42202);
        }


        if (
            $attendanceType == AttendanceType::clockOut() &&
            Attendance::where('attendance_type', $attendanceType)
            ->where('shift_id', $shiftId)
            ->where('employee_id', $employee->id)
            ->whereDate('scheduled_at', today())
            ->exists()
        ) {
            return $this->errorResponse('Already Clock Out', 422, 42202);
        }

        if (
            $attendanceType == AttendanceType::breakStartedAt() &&
            now()->lt(Carbon::today()->addSeconds($shiftTime->secondsSinceMidnight()))
        ) {
            return $this->errorResponse('Break Time not started yet', 422, 42202);
        }

        if (
            $attendanceType == AttendanceType::clockOut() &&
            now()->lt(Carbon::today()->addSeconds($shiftTime->secondsSinceMidnight()))
        ) {
            return $this->errorResponse('Not yet Clock Out Time !!!', 422, 42203);
        }

        $distance = $this->vincentyGreatCircleDistance($attendanceQRcode->latitude, $attendanceQRcode->longitude, $request->latitude, $request->longitude);
        $isOutsideAttendanceRadius = $this->isOutsideAttendanceRadius($distance, $attendanceQRcode);

        if ($isOutsideAttendanceRadius && $attendanceQRcode->is_geo_strict) {
            return $this->errorResponse('Not allowed to submit because outside radius', 422, 42204);
        }

        DB::transaction(function () use ($request, $employee, $companyId, $isOutsideAttendanceRadius, $isParentCompany) {
            $this->createAttendance($request, $employee, $companyId, $isOutsideAttendanceRadius, $isParentCompany);
        });
        return $this->showOne('Success');
    }


    // TODO
    public function validationBySecurity(Request $request)
    {
        // $employee = Employee::where('')->firstOrFail();
        // get customer id from kada API
        // perlu company?
        // get shift
        $customerId = 34953;
        $security = Candidate::where('user_id', auth()->id())->firstOrFail();
        $verifiedBy = Employee::where('candidate_id', $security->id)->firstOrFail();
        $candidate = Candidate::where('user_id', $customerId)->firstOrFail();
        $employeeIds = Employee::where('candidate_id', $candidate->id)->pluck('id');
        $employeeAttendances = AttendanceEmployee::whereIn('employee_id', $employeeIds)
            ->whereDate('created_at', today())
            ->get();
        foreach ($employeeAttendances as $employeeAttendance) {
            if (($employeeAttendance->attendance->attendance_type == AttendanceType::clockIn())) {
                $employeeAttendance->attendance->update([
                    'verified_by' => $verifiedBy->id,
                    'verified_at' => now(),
                ]);
            }
        }
    }


    public function createAttendance($request, Employee $employee, $companyId, $isOutsideAttendanceRadius, $isParentCompany)
    {
        $image = $request->file;
        // $shiftId = $request->shift_id;
        $img = Image::make($image)->encode($image->extension(), 70);
        $attendanceType = $request->attendance_type;
        $fileName = time() . '.' . $image->extension();

        $employees = [$employee];

        if ($isParentCompany) {
            $employees = Employee::where('candidate_id', $employee->candidate_id)->get();
        }
        // TODO: need to handle multiple shift, for now only handle 1 shift
        foreach ($employees as $employee) {
            $shiftId = $request->shift_id;
            $penalty = $this->getPenalty($request, $employee, $companyId);
            $employeeShift = $employee->getShift($shiftId)->shift;
            $shiftTime = new Carbon($employeeShift->$attendanceType);

            $verifiedAt = null;
            $verifiedBy = null;

            if (
                $attendanceType == AttendanceType::breakStartedAt() ||
                $attendanceType == AttendanceType::breakEndedAt() ||
                ($attendanceType == AttendanceType::clockOut() &&
                    now()->lt(Carbon::today()->addSeconds($shiftTime->secondsSinceMidnight())))
            ) {
                $verifiedAt = now();
                $verifiedBy = auth()->user()->id;
            }

            $attendance = Attendance::create([
                'attendance_type' => $attendanceType,
                'attended_at' => Carbon::now(),
                'scheduled_at' => Carbon::today()->addSeconds($shiftTime->secondsSinceMidnight()),
                'attendance_qr_code_id' => $request->attendance_qr_code_id,
                'image' => $fileName,
                'ip' => $request->ip(),
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'verified_by' => $verifiedBy,
                'verified_at' => $verifiedAt,
                'shift_id' => $employeeShift->id,
                'employee_id' => $employee->id
            ]);
            Storage::disk('public')->put('images/attendances/' . $fileName, $img);

            if ($penalty) {
                $this->createPenalty($penalty, $attendance, $request->penalty_note);
            }

            if ($isOutsideAttendanceRadius) {
                OutsideRadiusAttendance::create([
                    'attendance_id' => $attendance->id,
                    'note' => $request->outside_radius_note
                ]);
            }
        }
    }

    public static function createPenalty($penalty, Attendance $attendance, $note)
    {
        AttendancePenalty::create([
            'penalty_amount' => $penalty->amount,
            'attendance_id' => $attendance->id,
            'penalty_id' => $penalty->id,
            'penalty_name' => $penalty->name,
            'note' => $note ? $note : ''
        ]);
    }

    public static function getPenalty($request, $employee, $companyId)
    {
        $shiftId = $request->shift_id;
        if ($employee->getShift($shiftId) instanceof EmployeeOneTimeShift) {
            return null;
        }
        $attendanceType = $request->attendance_type;
        $now = Carbon::now();
        $employeeShift = $employee->getShift($shiftId)->shift;
        $shiftTime = new Carbon($employeeShift->$attendanceType);
        $scheduledAt = Carbon::today()->addSeconds($shiftTime->secondsSinceMidnight());
        if ($attendanceType == AttendanceType::clockIn() && $now->gt($scheduledAt)) {
            $interval = $scheduledAt->diffInMinutes($now);
            return Penalty::where('attendance_type', $attendanceType)
                ->where('lateness', '<=', $interval)
                ->where('company_id', $companyId)
                ->orderBy('lateness', 'DESC')
                ->first();
        }

        if (
            $attendanceType == AttendanceType::breakEndedAt() &&
            $scheduledAt->diffInMinutes($now) <= $shiftTime->AttendanceType::breakTime()
        ) {
            $interval = $scheduledAt->diffInMinutes($now);
            return Penalty::where('attendance_type', $attendanceType)
                ->where('lateness', '<=', $interval)
                ->where('company_id', $companyId)
                ->orderBy('lateness', 'DESC')
                ->first();
        }
    }

    public static function isOutsideAttendanceRadius($distance, $attendanceQRcode)
    {
        return $distance >= $attendanceQRcode->radius;
    }

    // https://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php
    /**
     * Calculates the great-circle distance between two points, with
     * the Vincenty formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    public static function vincentyGreatCircleDistance(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo,
        $earthRadius = 6371000
    ) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }


    public function getAttendancesByCompany(Request $request)
    {
        // pagination and search by user
        $request->validate([
            'started_at' => 'required',
            'ended_at' => 'required',
            'company_id' => 'required|exists:companies,id'
        ]);

        $keyword = $request->keyword;
        $startDate = Carbon::parse($request->started_at);
        $endDate = Carbon::parse($request->ended_at);
        $period = CarbonPeriod::create($startDate, $endDate);
        $companyId = $request->company_id;
        $company = Company::where('id', $companyId)->first();
        $employees = $company->employees()->with('position')
            ->whereHas('candidate', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            })->get();
        $data = [];

        foreach ($period as $date) {
            $array['date'] = $date->toDateString();
            $employeeAttendances = [];
            foreach ($employees as $employee) {
                $attendances = $employee->getAttendances($date->copy()->startOfDay(), $date->copy()->endOfDay());
                $shiftAttendances = $attendances->groupBy('shift_id');

                $shifts = [];
                $employeeShifts = $attendances->unique('shift_id');
                foreach ($employeeShifts as $employeeShift) {
                    $attendances = [];
                    foreach ($shiftAttendances[$employeeShift->shift_id] as $attendance) {
                        $attendances[] = [
                            'attendance' => $attendance,
                            'penalty' => $attendance->attendancePenalty
                        ];
                    }
                    $shifts[] = [
                        'id' => $employeeShift->shift_id,
                        'attendances' => $attendances,
                    ];
                }
                $employeeAttendance['profile_detail'] = $employee->candidate;
                $employeeAttendance['profile_detail'] = $employee;
                $employeeAttendance['shifts'] = $shifts;
                $employeeAttendances[] = $employeeAttendance;
            }

            $array['employees'] = $employeeAttendances;
            array_push($data, $array);
        }

        return $this->showAll(collect($data));
    }

    public function getAttendancesByDateRange(Request $request)
    {
        $request->validate([
            'started_at' => 'required',
            'ended_at' => 'required',
        ]);
        $startDate = Carbon::parse($request->started_at);
        $endDate = Carbon::parse($request->ended_at);
        $userId = auth()->id();
        $candidate = Candidate::where('user_id', $userId)->first();
        $employees = Employee::where('candidate_id', $candidate->id)->get();
        $period = CarbonPeriod::create($startDate, $endDate);

        $data = [];

        foreach ($period as $date) {
            $array['date'] = $date->toDateString();
            $employeeAttendances = [];

            foreach ($employees as $employee) {
                $attendances = $employee->getAttendances($date->copy()->startOfDay(), $date->copy()->endOfDay());
                $shiftAttendances = $attendances->groupBy('shift_id');

                $shifts = [];
                $employeeShifts = $attendances->unique('shift_id');
                foreach ($employeeShifts as $employeeShift) {
                    $attendances = [];
                    foreach ($shiftAttendances[$employeeShift->shift_id] as $attendance) {
                        $attendances[] = [
                            'attendance' => $attendance,
                        ];
                    }
                    $shifts[] = [
                        'id' => $employeeShift->shift_id,
                        'attendances' => $attendances,
                    ];
                }
                $employeeAttendances[] = $shifts;
            }

            $array['shifts'] = $employeeAttendances;
            array_push($data, $array);
        }

        return $this->showAll(collect($data));
    }

    // public function isExistsAttendance(DateTime $now, AttendanceType $type, Employee $employee, DateTime $day)
    // {
    //     $exists = false;
    //     $attendance = Attendance::whereBetween(
    //         'checked_at',
    //         [
    //             $day,
    //             $now
    //         ]
    //     )->where('attendance_type_id', $type->id)
    //         ->where('employee_id', $employee->id)
    //         ->first();
    //     if ($attendance) {
    //         $exists = true;
    //     }
    //     return $exists;
    // }


    // public static function getPenaltyValue(Shift $shift, AttendanceType $type, DateTime $now)
    // {
    //     $penalty = null;
    //     $columnName = $type->name;

    //     $dutyAt = new \DateTime($shift->$columnName, new DateTimeZone('Asia/Jakarta'));
    //     if ($type->id == AttendanceType::CLOCK_IN_ID) {

    //         if ($dutyAt <= $now) {

    //             $interval =  $dutyAt->diff($now);
    //             return Penalty::where('attendance_types_id', $type->id)
    //                 ->where('passing_at', '>=', $interval->format('H:i:s'))
    //                 ->orderBy('passing_at', 'DESC')
    //                 ->first();
    //         }
    //     } elseif ($type->id == AttendanceType::CLOCK_OUT_ID) {
    //         if ($dutyAt >= $now) {
    //             $interval = $now->diff($dutyAt);
    //             return Penalty::where('attendance_types_id', $type->id)
    //                 ->where('passing_at', '>=', $interval->format('H:i:s'))
    //                 ->orderBy('passing_at', 'DESC')
    //                 ->first();
    //         }
    //     } elseif ($type->id == AttendanceType::BREAK_STARTED_AT_ID) {
    //         if ($dutyAt <= $now) {

    //             $interval =  $dutyAt->diff($now);
    //             return Penalty::where('attendance_types_id', AttendanceType::BREAK_ENDED_AT_ID)
    //                 ->where('passing_at', '<=', $interval->format('H:i:s'))
    //                 ->orderBy('passing_at', 'DESC')
    //                 ->first();
    //         }
    //     } elseif ($type->id == AttendanceType::BREAK_ENDED_AT_ID) {
    //         $day = new \DateTime('today', new DateTimeZone('Asia/Jakarta'));
    //         $attendance = Attendance::whereBetween(
    //             'checked_at',
    //             [
    //                 $day,
    //                 $now,
    //             ]
    //         )->where('attendance_type_id', AttendanceType::BREAK_STARTED_AT_ID)
    //             ->first();
    //         if ($attendance) {
    //             $dutyAt = new \DateTime($attendance->checked_at, new DateTimeZone('Asia/Jakarta'));
    //         }
    //         if ($dutyAt >= $now) {
    //             $interval =  $dutyAt->diff($now);
    //             return Penalty::where('attendance_types_id', $type->id)
    //                 ->where('passing_at', '>=', $interval->format('H:i:s'))
    //                 ->orderBy('passing_at', 'DESC')
    //                 ->first();
    //         }
    //     }
    //     return $penalty;
    // }

    // public function validationBySecurity(Request $request)
    // {
    //     $request->validate([
    //         'employees' => ['array', 'required'],
    //     ]);
    //     foreach ($request->employees as $employee) {
    //         $employee = Employee::findOrFail($employee);
    //         $time =  new \DateTime('now', new DateTimeZone('Asia/Jakarta'));
    //         $interval = DateInterval::createFromDateString('+5 minute');
    //         $time->add($interval);
    //         $attendance = Attendance::where('checked_at', '<=', $time->format('Y-m-d\TH:i:s.u\Z'))->firstOrFail();
    //         $attendance->validated_at = time();
    //         $attendance->save();
    //     }

    //     return $this->showOne('Success');
    // }
    // public function edit(Attendance $attendance)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\Attendance  $attendance
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, Attendance $attendance)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Models\Attendance  $attendance
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Attendance $attendance)
    // {
    //     //
    // }
}
