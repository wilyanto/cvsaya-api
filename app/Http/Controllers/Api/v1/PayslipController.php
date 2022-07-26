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
}
