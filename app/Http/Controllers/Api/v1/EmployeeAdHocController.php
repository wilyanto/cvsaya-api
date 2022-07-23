<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeAdHocStoreRequest;
use App\Http\Requests\EmployeeAdHocUpdateRequest;
use App\Http\Resources\EmployeeAdHocResource;
use App\Services\EmployeeAdHocService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class EmployeeAdHocController extends Controller
{
    use ApiResponser;

    protected $employeeAdHocService;

    public function __construct(EmployeeAdHocService $employeeAdHocService)
    {
        $this->employeeAdHocService = $employeeAdHocService;
    }

    public function index()
    {
        $employeeAdHocs = $this->employeeAdHocService->getAll();

        return $this->showAll(collect(EmployeeAdHocResource::collection($employeeAdHocs)));
    }

    public function store(EmployeeAdHocStoreRequest $request)
    {
        $employeeAdHoc = $this->employeeAdHocService->createEmployeeAdHoc($request);

        return $this->showOne(new EmployeeAdHocResource($employeeAdHoc));
    }

    public function show($id)
    {
        $employeeAdHoc = $this->employeeAdHocService->getById($id);

        return $this->showOne(new EmployeeAdHocResource($employeeAdHoc));
    }

    public function update(EmployeeAdHocUpdateRequest $request, $id)
    {
        $employeeAdHoc = $this->employeeAdHocService->updateEmployeeAdHoc($request, $id);

        return $this->showOne(new EmployeeAdHocResource($employeeAdHoc));
    }

    public function destroy($id)
    {
        $message = $this->employeeAdHocService->deleteById($id);

        return response()->json(null, 204);
    }
}
