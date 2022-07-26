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
}
