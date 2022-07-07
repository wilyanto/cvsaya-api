<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeResignationStoreRequest;
use App\Http\Requests\EmployeeResignationUpdateRequest;
use App\Http\Resources\EmployeeResignationResource;
use App\Services\EmployeeResignationService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class EmployeeResignationController extends Controller
{
    use ApiResponser;

    protected $employeeResignationService;

    public function __construct(EmployeeResignationService $employeeResignationService)
    {
        $this->employeeResignationService = $employeeResignationService;
    }

    public function index()
    {
        $employeeResignations = $this->employeeResignationService->getAll();

        return $this->showAll(collect(EmployeeResignationResource::collection($employeeResignations)));
    }

    public function store(EmployeeResignationStoreRequest $request)
    {
        $employeeResignation = $this->employeeResignationService->createEmployeeResignation($request);

        return $this->showOne(new EmployeeResignationResource($employeeResignation));
    }

    public function show($id)
    {
        $employeeResignation = $this->employeeResignationService->getById($id);

        return $this->showOne(new EmployeeResignationResource($employeeResignation));
    }

    public function update(EmployeeResignationUpdateRequest $request, $id)
    {
        $employeeResignation = $this->employeeResignationService->updateEmployeeResignation($request, $id);

        return $this->showOne(new EmployeeResignationResource($employeeResignation));
    }

    public function destroy($id)
    {
        $message = $this->employeeResignationService->deleteById($id);

        return response()->json(null, 204);
    }
}
