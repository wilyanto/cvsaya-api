<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PayslipStoreRequest;
use App\Http\Requests\PayslipUpdateRequest;
use App\Http\Resources\EmployeePayslipResource;
use App\Models\EmployeePayslip;
use App\Services\PayslipService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class PayslipController extends Controller
{
    use ApiResponser;

    protected $payslipService;

    public function __construct(PayslipService $payslipService)
    {
        $this->payslipService = $payslipService;
    }

    public function index(Request $request)
    {
        $pageSize = $request->input('page_size', 10);
        $payslips = $this->payslipService->getAll($pageSize);

        return $this->showPaginate('payslips', collect(EmployeePayslipResource::collection($payslips)), collect($payslips));
    }

    public function show($id)
    {
        $payslip = $this->payslipService->getById($id);

        return $this->showOne(new EmployeePayslipResource($payslip));
    }

    public function store(PayslipStoreRequest $request)
    {
        $payslip = $this->payslipService->createPayslip($request);

        return $this->showOne(new EmployeePayslipResource($payslip));
    }

    public function update(PayslipUpdateRequest $request, $id)
    {
        $payslip = $this->payslipService->updatePayslip($request, $id);

        return $this->showOne(new EmployeePayslipResource($payslip));
    }

    public function generatePayslip(Request $request, $id)
    {
        $request->validate([
            'generated_by' => 'required|exists:employees,id'
        ]);
        $generatedBy = $request->generated_by;
        $payslip = $this->payslipService->generatePayslip($id, $generatedBy);

        return $this->showOne(new EmployeePayslipResource($payslip));
    }

    public function payPayslip(Request $request, $id)
    {
        $request->validate([
            'paid_by' => 'required|exists:employees,id'
        ]);
        $paidBy = $request->paid_by;
        $payslip = $this->payslipService->payPayslip($id, $paidBy);

        return $this->showOne(new EmployeePayslipResource($payslip));
    }

    public function generateAndPayPayslip(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id'
        ]);
        $employeeId = $request->employee_id;
        $payslip = $this->payslipService->generateAndPayPayslip($id, $employeeId);

        return $this->showOne(new EmployeePayslipResource($payslip));
    }

    public function showPayslipByEmployee($id)
    {
        $payslips = $this->payslipService->getByEmployeeId($id);

        return $this->showAll(collect(EmployeePayslipResource::collection($payslips)));
    }

    public function showPayslipByEmployeeMobile(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);
        $id = $request->employee_id;
        $payslips = $this->payslipService->getByEmployeeId($id);

        return $this->showAll(collect(EmployeePayslipResource::collection($payslips)));
    }
}
