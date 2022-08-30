<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EarlyClockOutAttendanceUpdateStatusRequest;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\EarlyClockOutAttendanceResource;
use App\Services\EarlyClockOutAttendanceService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class EarlyClockOutAttendanceController extends Controller
{
    use ApiResponser;

    protected $earlyClockOutAttendanceService;

    public function __construct(EarlyClockOutAttendanceService $earlyClockOutAttendanceService)
    {
        $this->earlyClockOutAttendanceService = $earlyClockOutAttendanceService;
    }

    public function index(Request $request, $companyId)
    {
        $pageSize = $request->input('page_size', 10);
        $earlyClockOutAttendances = $this->earlyClockOutAttendanceService->getAll($companyId, $pageSize);

        return $this->showAll(collect(AttendanceResource::collection($earlyClockOutAttendances)));
    }

    public function show($companyId, $id)
    {
        $earlyClockOutAttendance = $this->earlyClockOutAttendanceService->getById($id);

        return $this->showOne(new EarlyClockOutAttendanceResource($earlyClockOutAttendance));
    }

    public function update(EarlyClockOutAttendanceUpdateStatusRequest $request, $companyId, $id)
    {
        $earlyClockOutAttendance = $this->earlyClockOutAttendanceService->updateStatus($request, $id);

        return $this->showOne(new EarlyClockOutAttendanceResource($earlyClockOutAttendance));
    }

    public function destroy($companyId, $id)
    {
        $message = $this->earlyClockOutAttendanceService->deleteById($id);

        return response()->json(null, 204);
    }
}
