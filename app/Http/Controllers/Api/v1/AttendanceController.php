<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AttendanceType;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\ShiftEmployee;
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
                'date_format:Y-m-d\TH:i:s.u\Z',
                'required'
            ],
        ]);

        $user = auth()->user();
        // dump($user);
        $employee = Employee::where('user_id', $user->id_kustomer)->firstOrfail();
        $data = [];
        $data['employee'] = [
            'id' => $employee->id,
            'name' => $employee->getUserName(),
        ];
        $data['attendances'] = $employee->getShifts($request->started_at, $request->ended_at);

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
        ]);
        $attendanceType = AttendanceType::findOrFail($request->type);
        $documentType = DocumentType::where('name', 'attendances')->firstOrFail();
        $employee = Employee::where('user_id', $user->id_kustomer)->firstOrFail();

        // $time = date('Y-m-d_H-i-s', time());
        // $randomNumber =  CvDocumentController::random4Digits();

        // $finfo = new \finfo(FILEINFO_MIME_TYPE);
        // $mimeType = $finfo->file($request->file('file')); // variable
        // $extension = CvDocumentController::getExtension($mimeType);

        // $filenameWithoutExtenstion = $time . '_' . $user->id_kustomer . '_' . $randomNumber;
        // $filename = $filenameWithoutExtenstion . '.' . $extension;

        // $request->file('file')->storeAs('public/attendances/' . $attendanceType->name, $filename);
        // Document::create([
        //     'file_name' => $filenameWithoutExtenstion,
        //     'user_id' => $user->id_kustomer,
        //     'mime_type' => $mimeType,
        //     'type_id' => $documentType->id,
        //     'original_file_name' => $request->file->getClientOriginalName(),
        // ]);
        // dump($attendanceType);
        $time = new \DateTime("now", new DateTimeZone('Asia/Jakarta'));
        $date =  new \DateTime("today", new DateTimeZone('Asia/Jakarta'));
        $startDate = $date->format('Y-m-d\TH:i:s.u\Z');
        $interval = DateInterval::createFromDateString('+23 hour +59 minute + 59 second');
        $endDate = $date->add($interval)->format('Y-m-d\TH:i:s.u\Z');
        $shift = ShiftEmployee::whereBetween(
            'date',
            [
                $startDate,
                $endDate
            ]
        )->first();
        $columnName = $attendanceType->name;
        $dutyAt = $shift->shift->$columnName;
        if ($columnName == 'break_ended_at') {
            $attendance = Attendance::whereBetween(
                'duty_at',
                [
                    $startDate,
                    $endDate
                ]
            )->where('attendance_type_id', 2)->first();
            if($attendance){
                $dutyAt = date('Y-m-d\TH:i:s.u\Z', strtotime($attendance->checked_at . ' +' . $shift->shift->break_duration . 'hour'));
            }
        }
        Attendance::create([
            'checked_at' => $time,
            'duty_at' => $dutyAt,
            'employee_id' => $employee->id,
            'attendance_type_id' => $attendanceType->id,
            'validated_at' => $attendanceType->name == AttendanceType::CLOCKIN ? null : time(),
        ]);
        // $startDate = date('Y-m-d\TH:i:s.u\Z',strtotime($date. '-7 hour'));
        // $endDate = date('Y-m-d\TH:i:s.u\Z',strtotime($endDayOfDate. '-7 hour'));
        return $this->showOne($employee->getShifts($startDate, $endDate, true));
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
