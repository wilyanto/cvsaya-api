<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeAdHocStoreRequest;
use App\Http\Requests\EmployeeAdHocUpdateRequest;
use App\Http\Requests\EmployeePayslipAdHocStoreRequest;
use App\Http\Requests\EmployeePayslipAdHocUpdateRequest;
use App\Http\Resources\EmployeePayslipAdHocResource;
use App\Services\EmployeePayslipAdHocService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class EmployeePayslipAdHocController extends Controller
{
    use ApiResponser;

    protected $employeePayslipAdHocService;

    public function __construct(EmployeePayslipAdHocService $employeePayslipAdHocService)
    {
        $this->employeePayslipAdHocService = $employeePayslipAdHocService;
    }

    public function index()
    {
        $employeePayslipAdHocs = $this->employeePayslipAdHocService->getAll();

        return $this->showAll(collect(EmployeePayslipAdHocResource::collection($employeePayslipAdHocs)));
    }

    public function store(EmployeePayslipAdHocStoreRequest $request, $payslipId)
    {
        $employeePayslipAdHoc = $this->employeePayslipAdHocService->createEmployeePayslipAdHoc($request, $payslipId);

        return $this->showOne(new EmployeePayslipAdHocResource($employeePayslipAdHoc));
    }

    public function show($payslipId, $id)
    {
        $employeePayslipAdHoc = $this->employeePayslipAdHocService->getById($id);

        return $this->showOne(new EmployeePayslipAdHocResource($employeePayslipAdHoc));
    }

    public function update(EmployeePayslipAdHocUpdateRequest $request, $payslipId, $id)
    {
        $employeePayslipAdHoc = $this->employeePayslipAdHocService->updateEmployeePayslipAdHoc($request, $id);

        return $this->showOne(new EmployeePayslipAdHocResource($employeePayslipAdHoc));
    }

    public function destroy($payslipId, $id)
    {
        $message = $this->employeePayslipAdHocService->deleteById($id);

        return response()->json(null, 204);
    }
}
