<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PayrollPeriodStoreRequest;
use App\Http\Requests\PayrollPeriodUpdateRequest;
use App\Http\Resources\PayrollPeriodResource;
use App\Services\PayrollPeriodService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class PayrollPeriodController extends Controller
{
    use ApiResponser;

    protected $payrollPeriodService;

    public function __construct(PayrollPeriodService $payrollPeriodService)
    {
        $this->payrollPeriodService = $payrollPeriodService;
    }

    public function index()
    {
        $payrollPeriods = $this->payrollPeriodService->getAll();

        return $this->showAll(collect(PayrollPeriodResource::collection($payrollPeriods)));
    }

    public function store(PayrollPeriodStoreRequest $request)
    {
        $payrollPeriod = $this->payrollPeriodService->createPayrollPeriod($request);

        return $this->showOne(new PayrollPeriodResource($payrollPeriod));
    }

    public function show($id)
    {
        $payrollPeriod = $this->payrollPeriodService->getById($id);

        return $this->showOne(new PayrollPeriodResource($payrollPeriod));
    }

    public function update(PayrollPeriodUpdateRequest $request, $id)
    {
        $payrollPeriod = $this->payrollPeriodService->updatePayrollPeriod($request, $id);

        return $this->showOne(new PayrollPeriodResource($payrollPeriod));
    }

    public function destroy($id)
    {
        $message = $this->payrollPeriodService->deleteById($id);

        return response()->json(null, 204);
    }

    public function indexByCompanyId(Request $request, $companyId)
    {
        $payrollPeriods = $this->payrollPeriodService->getAllByCompanyId($request, $companyId);

        return $this->showPaginate('payroll_periods', collect(PayrollPeriodResource::collection($payrollPeriods)), collect($payrollPeriods));
    }
}
