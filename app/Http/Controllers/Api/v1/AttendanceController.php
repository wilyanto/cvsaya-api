<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AttendancePenalty;
use App\Enums\AttendanceType;
use App\Enums\EarlyClockOutAttendanceStatusEnum;
use App\Http\Resources\AttendanceDetailResource;
use App\Http\Resources\AttendanceResource;
use App\Models\AttendanceCompanyGroup;
use App\Models\AttendanceDetail;
use App\Models\AttendanceEmployee;
use App\Models\AttendanceQrCode;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\EarlyClockOutAttendance;
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
use Illuminate\Support\Facades\Http;
use MatanYadaev\EloquentSpatial\Objects\Point;

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
            'early_clock_out_note' => 'string',
            'penalty_note' => 'string',
            'shift_id' => 'required|exists:shifts,id'
        ]);

        $shiftId = $request->shift_id;
        $isParentCompany = false;
        $attendanceQRcode = AttendanceQrCode::where('id', $request->attendance_qr_code_id)->firstOrFail();
        $companyId = $attendanceQRcode->company_id;

        $candidate = Candidate::where('user_id', $user->id())->firstOrFail();
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

        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('shift_id', $shiftId)
            ->whereDate('date', today())
            ->first();

        if (!$attendance) {
            $attendance = Attendance::create([
                'employee_id' => $employee->id,
                'shift_id' => $shiftId,
                'date' => today()
            ]);
        }

        // handle if blocked


        if (
            $attendanceType == AttendanceType::clockIn() &&
            $attendance->clockInAttendanceDetail
        ) {
            return $this->errorResponse('Already Clock In', 422, 42200);
        }

        if (
            $attendanceType == AttendanceType::clockOut() &&
            $attendance->clockOutAttendanceDetail
        ) {
            return $this->errorResponse('Already Clock Out', 422, 42201);
        }

        // clock out without clock in
        if (
            $attendanceType == AttendanceType::endBreak() &&
            !$attendance->startBreakAttendanceDetail
        ) {
            return $this->errorResponse('Must scan start break before end break', 422, 42202);
        }

        if (
            $attendanceType == AttendanceType::startBreak() &&
            $attendance->startBreakAttendanceDetail
        ) {
            return $this->errorResponse('Already Scan Start Break', 422, 42201);
        }

        if (
            $attendanceType == AttendanceType::endBreak() &&
            $attendance->endBreakAttendanceDetail
        ) {
            return $this->errorResponse('Already Scan End Break', 422, 42203);
        }

        if (
            $attendanceType == AttendanceType::startBreak() &&
            now()->lt(today()->addSeconds($shiftTime->secondsSinceMidnight()))
        ) {
            return $this->errorResponse('Break Time not started yet', 422, 42206);
        }

        if (
            $attendanceType == AttendanceType::endBreak() &&
            now()->gt(today()->addSeconds($shiftTime->secondsSinceMidnight()))
        ) {
            return $this->errorResponse('Already out of break duration', 422, 42207);
        }

        $distance = $this->vincentyGreatCircleDistance($attendanceQRcode->latitude, $attendanceQRcode->longitude, $request->latitude, $request->longitude);
        $isOutsideRadius = $this->isOutsideRadius($distance, $attendanceQRcode);

        if ($isOutsideRadius && $attendanceQRcode->is_geo_strict) {
            return $this->errorResponse('Not allowed to submit because outside radius', 422, 42208);
        }

        DB::transaction(function () use ($request, $employee, $companyId, $isOutsideRadius, $isParentCompany) {
            $this->createAttendanceDetail($request, $employee, $companyId, $isOutsideRadius, $isParentCompany);
        });
        return $this->showOne('Success');
    }


    public function validationBySecurity(Request $request)
    {
        $phoneNumber = $request->country_code . $request->phone_number;
        $url = env('KADA_URL') . "/v1/customer/get-customer";
        $response = Http::withHeaders(['internal_api_key' => env('INTERNAL_API_KEY')])
            ->post($url, ['phone_number' => $phoneNumber]);

        if ($response->failed()) {
            return $this->errorResponse($response->json()['data'], $response->status(), $response->status() . '00');
        }

        $customer = $response->json()['data'];
        if (!$customer) {
            return $this->errorResponse('Data not found', 404, 40400);
        }

        $customerId = $customer['id_kustomer'];
        $security = Candidate::where('user_id', auth()->id())->firstOrFail();
        $verifiedBy = Employee::where('candidate_id', $security->id)->firstOrFail();
        $candidate = Candidate::where('user_id', $customerId)->firstOrFail();
        $employeeIds = Employee::where('candidate_id', $candidate->id)->pluck('id');
        $attendances = Attendance::whereIn('employee_id', $employeeIds)
            ->whereDate('date', today())
            ->get();
        $attendanceDetails = [];
        foreach ($attendances as $attendance) {
            $attendanceDetail = $attendance->clockInAttendanceDetail;

            if (!$attendanceDetail) {
                continue;
            }

            $attendanceDetail->update([
                'verified_by' => $verifiedBy->id,
                'verified_at' => now(),
            ]);

            array_push($attendanceDetails, $attendanceDetail);
        }

        return $this->showAll(collect(AttendanceDetailResource::collection($attendanceDetails)));
    }



    public function createAttendanceDetail($request, Employee $employee, $companyId, $isOutsideRadius, $isParentCompany)
    {
        $image = $request->file;
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
                $attendanceType == AttendanceType::startBreak() ||
                $attendanceType == AttendanceType::endBreak() ||
                ($attendanceType == AttendanceType::clockOut() &&
                    now()->gt(today()->addSeconds($shiftTime->secondsSinceMidnight())))
            ) {
                $verifiedAt = now();
                $verifiedBy = $employee->id;
            }

            $attendanceDetail = AttendanceDetail::create([
                'attendance_type' => $attendanceType,
                'attended_at' => now(),
                'scheduled_at' => today()->addSeconds($shiftTime->secondsSinceMidnight()),
                'attendance_qr_code_id' => $request->attendance_qr_code_id,
                'image' => $fileName,
                'ip' => $request->ip(),
                'location' => new Point($request->latitude, $request->longitude),
                'verified_by' => $verifiedBy,
                'verified_at' => $verifiedAt,
            ]);

            $attendance = Attendance::where('employee_id', $employee->id)
                ->where('shift_id', $shiftId)
                ->whereDate('date', today())
                ->first();

            if ($attendanceType == AttendanceType::clockIn()) {
                $attendance->update(['clock_in_id' => $attendanceDetail->id]);
            } else if ($attendanceType == AttendanceType::clockOut()) {
                $attendance->update(['clock_out_id' => $attendanceDetail->id]);
            } else if ($attendanceType == AttendanceType::startBreak()) {
                $attendance->update(['start_break_id' => $attendanceDetail->id]);
            } else if ($attendanceType == AttendanceType::endBreak()) {
                $attendance->update(['end_break_id' => $attendanceDetail->id]);
            }

            Storage::disk('public')->put('images/attendance-details/' . $fileName, $img);

            if (
                $attendanceType == AttendanceType::clockOut() &&
                now()->lt(today()->addSeconds($shiftTime->secondsSinceMidnight()))
            ) {
                // create early clock out attendance request
                EarlyClockOutAttendance::create([
                    'attendance_detail_id' => $attendanceDetail->id,
                    'note' => $request->early_clock_out_note,
                    'status' => EarlyClockOutAttendanceStatusEnum::pending()
                ]);
            }

            if ($penalty) {
                $this->createPenalty($penalty, $attendanceDetail, $request->penalty_note);
            }

            if ($isOutsideRadius) {
                OutsideRadiusAttendance::create([
                    'attendance_detail_id' => $attendanceDetail->id,
                    'note' => $request->outside_radius_note
                ]);
            }
        }
    }

    public static function createPenalty($penalty, AttendanceDetail $attendanceDetail, $note)
    {
        AttendancePenalty::create([
            'penalty_amount' => $penalty->amount,
            'attendance_detail_id' => $attendanceDetail->id,
            'penalty_id' => $penalty->id,
            'penalty_name' => $penalty->name,
            'note' => $note ?? '',
        ]);
    }

    public static function getPenalty($request, $employee, $companyId)
    {
        $shiftId = $request->shift_id;
        if ($employee->getShift($shiftId) instanceof EmployeeOneTimeShift) {
            return null;
        }
        $attendanceType = $request->attendance_type;
        $now = now();
        $employeeShift = $employee->getShift($shiftId)->shift;
        $shiftTime = new Carbon($employeeShift->$attendanceType);
        $scheduledAt = today()->addSeconds($shiftTime->secondsSinceMidnight());
        if ($attendanceType == AttendanceType::clockIn() && $now->gt($scheduledAt)) {
            $interval = $scheduledAt->diffInMinutes($now);
            return Penalty::where('attendance_type', $attendanceType)
                ->where('lateness', '<=', $interval)
                ->where('company_id', $companyId)
                ->orderBy('lateness', 'DESC')
                ->first();
        }

        $breakDuration = AttendanceType::breakDuration();
        if (
            $attendanceType == AttendanceType::endBreak() &&
            $scheduledAt->diffInMinutes($now) >= $employeeShift->$breakDuration
        ) {
            $interval = $scheduledAt->diffInMinutes($now);
            return Penalty::where('attendance_type', AttendanceType::breakTime())
                ->where('lateness', '<=', $interval)
                ->where('company_id', $companyId)
                ->orderBy('lateness', 'DESC')
                ->first();
        }
    }

    public static function isOutsideRadius($distance, $attendanceQRcode)
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
        $request->validate([
            'started_at' => 'required',
            'ended_at' => 'required',
            'company_id' => 'required|exists:companies,id'
        ]);

        $keyword = $request->keyword;
        $startDate = Carbon::parse($request->started_at);
        $endDate = Carbon::parse($request->ended_at);
        $companyId = $request->company_id;
        $company = Company::where('id', $companyId)->first();
        $employeeIds = $company->employees()
            ->whereHas('candidate', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')->orderBy('name');
            })->pluck('employees.id');

        $attendances = Attendance::whereIn('employee_id', $employeeIds)
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->orderBy('date')
            ->paginate($request->input('page_size', 10));

        return $this->showPaginate('attendances', collect(AttendanceResource::collection($attendances)), collect($attendances));
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
        $employeeIds = Employee::where('candidate_id', $candidate->id)->pluck('id');

        $attendances = Attendance::whereIn('employee_id', $employeeIds)
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->orderBy('date', 'DESC')
            ->paginate($request->input('page_size', 10));

        return $this->showPaginate('attendances', collect(AttendanceResource::collection($attendances)), collect($attendances));
    }

    public function getAttendancesByEmployee(Request $request, $employeeId)
    {
        $request->validate([
            'started_at' => 'required',
            'ended_at' => 'required',
        ]);
        $startDate = Carbon::parse($request->started_at);
        $endDate = Carbon::parse($request->ended_at);
        $employee = Employee::findOrFail($employeeId);

        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->orderBy('date')
            ->paginate($request->input('page_size', 10));

        return $this->showPaginate('attendances', collect(AttendanceResource::collection($attendances)), collect($attendances));
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
