<?php

namespace App\Services;

use App\Http\Common\Filter\FilterPayrollPeriodSearch;
use App\Models\PayrollPeriod;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PayrollPeriodService
{
    protected $payslipService;

    public function __construct(PayslipService $payslipService)
    {
        $this->payslipService = $payslipService;
    }

    public function getAll()
    {
        $payrollPeriods = QueryBuilder::for(PayrollPeriod::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterPayrollPeriodSearch),
            ])
            ->get();

        return $payrollPeriods;
    }

    public function getById($id)
    {
        $query = PayrollPeriod::where('id', $id);
        $payrollPeriod = QueryBuilder::for($query)
            ->firstOrFail();

        return $payrollPeriod;
    }

    public function createPayrollPeriod($data)
    {
        $payrollPeriod = PayrollPeriod::create([
            'name' => $data->name,
            'started_at' => $data->started_at,
            'ended_at' => $data->ended_at,
            'company_id' => $data->company_id,
            'working_day_count' => $data->working_day_count
        ]);

        // TODO: implement generate payslip
        $this->payslipService->createPayslip($payrollPeriod->id);

        return $payrollPeriod;
    }

    public function updatePayrollPeriod($data, $id)
    {
        $payrollPeriod = $this->getById($id);
        $payrollPeriod->update([
            'name' => $data->name,
            'started_at' => $data->started_at,
            'ended_at' => $data->ended_at,
            'company_id' => $data->company_id,
            'working_day_count' => $data->working_day_count
        ]);

        return $payrollPeriod;
    }

    public function deleteById($id)
    {
        $payrollPeriod = PayrollPeriod::where('id', $id)->firstOrFail();
        $payrollPeriod->delete();
        return true;
    }

    public function getAllByCompanyId($request, $companyId)
    {
        $pageSize = $request->input('page_size', 10);
        $payrollPeriods = QueryBuilder::for(PayrollPeriod::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterPayrollPeriodSearch),
            ])
            ->where('company_id', $companyId)
            ->paginate($pageSize);

        return $payrollPeriods;
    }
}
