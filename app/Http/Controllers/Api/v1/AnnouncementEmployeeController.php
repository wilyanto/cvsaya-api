<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementEmployeeBatchStoreRequest;
use App\Http\Requests\AnnouncementEmployeeStoreRequest;
use App\Http\Requests\AnnouncementEmployeeUpdateRequest;
use App\Http\Resources\AnnouncementEmployeeResource;
use App\Services\AnnouncementEmployeeService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class AnnouncementEmployeeController extends Controller
{
    use ApiResponser;

    protected $announcementEmployeeService;

    public function __construct(AnnouncementEmployeeService $announcementEmployeeService)
    {
        $this->announcementEmployeeService = $announcementEmployeeService;
    }

    public function index()
    {
        $announcementEmployees = $this->announcementEmployeeService->getAll();

        return $this->showAll(collect(AnnouncementEmployeeResource::collection($announcementEmployees)));
    }

    public function store(AnnouncementEmployeeStoreRequest $request)
    {
        $announcementEmployee = $this->announcementEmployeeService->createAnnouncementEmployee($request);

        return $this->showOne(new AnnouncementEmployeeResource($announcementEmployee));
    }

    public function storeBatch(AnnouncementEmployeeBatchStoreRequest $request)
    {
        $announcementEmployees = $this->announcementEmployeeService->createAnnouncementEmployees($request);

        return $this->showAll(collect(AnnouncementEmployeeResource::collection($announcementEmployees)));
    }

    public function show($id)
    {
        $announcementEmployee = $this->announcementEmployeeService->getById($id);

        return $this->showOne(new AnnouncementEmployeeResource($announcementEmployee));
    }

    public function showByEmployeeId(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);
        $employeeId = $request->employee_id;
        $announcementEmployees = $this->announcementEmployeeService->getUnreadAnnouncementByEmployeeId($employeeId);

        return $this->showAll(collect(AnnouncementEmployeeResource::collection($announcementEmployees)));
    }

    public function update(AnnouncementEmployeeUpdateRequest $request, $id)
    {
        $announcementEmployee = $this->announcementEmployeeService->updateAnnouncementEmployee($request, $id);

        return $this->showOne(new AnnouncementEmployeeResource($announcementEmployee));
    }

    public function destroy($id)
    {
        $message = $this->announcementEmployeeService->deleteById($id);

        return response()->json(null, 204);
    }
}
