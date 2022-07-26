<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeePayslipResource;
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

    public function show($id)
    {
        $payslip = $this->payslipService->getById($id);

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
}
