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
    public function index(Request $request, $companyId)
    {
        $attendanceQrCodes = AttendanceQrCode::where('company_id', $companyId)->paginate($request->input('size', 10));

        return $this->showPagination('attendance_qr_codes', $attendanceQrCodes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttendanceQrCodeRequest $request, $companyId)
    {
        $attendanceQrCode = AttendanceQrCode::create([
            'location_name' => $request->location_name,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'radius' => $request->radius,
            'is_geo_strict' => $request->is_geo_strict,
            'company_id' => $companyId
        ]);

        return $this->showOne($attendanceQrCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AttendanceQrCode  $attendanceQrCode
     * @return \Illuminate\Http\Response
     */
    public function show($companyId, $id)
    {
        $attendanceQrCode = AttendanceQrCode::findOrFail($id);

        return $this->showOne($attendanceQrCode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AttendanceQrCode  $attendanceQrCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $companyId, $id)
    {
        $attendanceQrCode = AttendanceQrCode::findOrFail($id);
        $attendanceQrCode->update([
            'location_name' => $request->location_name,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'radius' => $request->radius,
            'is_geo_strict' => $request->is_geo_strict,
            'company_id' => $companyId
        ]);

        return $this->showOne($attendanceQrCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AttendanceQrCode  $attendanceQrCode
     * @return \Illuminate\Http\Response
     */
    public function destroy($companyId, $id)
    {
        $attendanceQrCode = AttendanceQrCode::destroy($id);

        return $this->showOne(null);
    }

    public function getById($id)
    {
        $attendanceQrCode = AttendanceQrCode::findOrFail($id);

        return $this->showOne($attendanceQrCode);
    }
}
