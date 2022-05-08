<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceQrCodeRequest;
use App\Models\AttendanceQrCode;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class AttendanceQrCodeController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendanceQrCodes = AttendanceQrCode::all();

        return $this->showAll($attendanceQrCodes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttendanceQrCodeRequest $request)
    {
        $attendanceQrCode = AttendanceQrCode::create($request->all());

        return $this->showOne($attendanceQrCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AttendanceQrCode  $attendanceQrCode
     * @return \Illuminate\Http\Response
     */
    public function show(AttendanceQrCode $attendanceQrCode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AttendanceQrCode  $attendanceQrCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AttendanceQrCode $attendanceQrCode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AttendanceQrCode  $attendanceQrCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttendanceQrCode $attendanceQrCode)
    {
        //
    }
}
