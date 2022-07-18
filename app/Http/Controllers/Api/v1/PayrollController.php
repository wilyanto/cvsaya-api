<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Services\PayrollService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    use ApiResponser;

    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function indexByCompanyId(Request $request, $companyId)
    {
        // get all employees
        $payrolls = $this->payrollService->showAllByCompanyId($request, $companyId);
        // calculate each employee's payroll

        return $this->showPaginate('payrolls', collect(EmployeeResource::collection($payrolls)), collect($payrolls));
    }
}
