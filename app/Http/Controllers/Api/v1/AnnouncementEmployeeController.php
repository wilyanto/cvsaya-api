<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementEmployeeBatchStoreRequest;
use App\Http\Requests\AnnouncementEmployeeNoteUpdateRequest;
use App\Http\Requests\AnnouncementEmployeeStoreRequest;
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

    public function index($announcementId)
    {
        $announcementEmployees = $this->announcementEmployeeService->getAll($announcementId);

        return $this->showAll(collect(AnnouncementEmployeeResource::collection($announcementEmployees)));
    }

    public function store(AnnouncementEmployeeStoreRequest $request, $announcementId)
    {
        $announcementEmployee = $this->announcementEmployeeService->createAnnouncementEmployee($request, $announcementId);

        return $this->showOne(new AnnouncementEmployeeResource($announcementEmployee));
    }

    public function storeBatch(AnnouncementEmployeeBatchStoreRequest $request, $announcementId)
    {
        $announcementEmployees = $this->announcementEmployeeService->createAnnouncementEmployees($request, $announcementId);

        return $this->showAll(collect(AnnouncementEmployeeResource::collection($announcementEmployees)));
    }

    public function show($announcementId, $id)
    {
        $announcementEmployee = $this->announcementEmployeeService->getById($id);

        return $this->showOne(new AnnouncementEmployeeResource($announcementEmployee));
    }

    public function showByEmployeeId(Request $request)
    {
        $employeeId = $request->employee_id;
        $announcementEmployee = $this->announcementEmployeeService->getUnreadAnnouncementByEmployeeId($employeeId);

        return $this->showOne(new AnnouncementEmployeeResource($announcementEmployee));
    }

    public function updateNote(AnnouncementEmployeeNoteUpdateRequest $request, $announcementId, $id)
    {
        $announcementEmployee = $this->announcementEmployeeService->updateAnnouncementEmployeeNote($request, $id);

        return $this->showOne(new AnnouncementEmployeeResource($announcementEmployee));
    }

    public function updateStatus($announcementId, $id)
    {
        $announcementEmployee = $this->announcementEmployeeService->updateAnnouncementEmployeeStatus($id);

        return $this->showOne(new AnnouncementEmployeeResource($announcementEmployee));
    }

    public function destroy($announcementId, $id)
    {
        $message = $this->announcementEmployeeService->deleteById($id);

        return response()->json(null, 204);
    }
}
