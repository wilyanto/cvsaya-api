<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AttendanceType;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Traits\ApiResponser;

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

    public function indexAttendanceType(Request $request){
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

        $time = date('Y-m-d_H-i-s', time());
        $randomNumber = $this->random4Digits();

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($request->file('file')); // variable
        $extension = $this->getExtension($mimeType);

        $filenameWithoutExtenstion = $time . '_' . $user->id_kustomer . '_' . $randomNumber;
        $filename = $filenameWithoutExtenstion . '.' . $extension;

        $request->file('file')->storeAs('public/attendances/' . $attendanceType->name, $filename);
        $document = Document::create([
            'file_name' => $filenameWithoutExtenstion,
            'user_id' => $user->id_kustomer,
            'mime_type' => $mimeType,
            'type_id' => $documentType->id,
            'original_file_name' => $request->file->getClientOriginalName(),
        ]);
        Attendance::create([
            'checked_at' => time(),
            'checked_at' => time(),
            'attendance_type_id' => $attendanceType,
            'validated_at' => $attendanceType->name == AttendanceType::CLOCKIN ? null : time(),
        ]);

        return $this->showOne($document->toIdDocuments());
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
