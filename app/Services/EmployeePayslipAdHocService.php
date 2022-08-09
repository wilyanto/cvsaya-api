<?php

namespace App\Services;

use App\Enums\SalaryTypeEnum;
use App\Models\EmployeePayslipAdHoc;
use App\Models\SalaryType;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeePayslipAdHocService
{
    public function getAll()
    {
        $employeePayslipAdHocs = QueryBuilder::for(EmployeePayslipAdHoc::class)
            ->allowedIncludes([
                'companySalaryType.salaryType'
            ])
            ->get();

        return $employeePayslipAdHocs;
    }

    public function getById($id)
    {
        $query = EmployeePayslipAdHoc::where('id', $id);
        $employeePayslipAdHoc = QueryBuilder::for($query)
            ->allowedIncludes([
                'companySalaryType.salaryType'
            ])
            ->firstOrFail();

        return $employeePayslipAdHoc;
    }

    public function createEmployeePayslipAdHoc($data, $payslipId)
    {
        $employeePayslipAdHoc = EmployeePayslipAdHoc::create([
            'employee_payslip_id' => $payslipId,
            'company_salary_type_id' => $data->company_salary_type_id,
            'name' => $data->name,
            'amount' => $data->amount,
            'note' => $data->note,
            'date' =>  $data->date
        ]);

        return $employeePayslipAdHoc;
    }

    public function updateEmployeePayslipAdHoc($data, $id)
    {
        $employeePayslipAdHoc = $this->getById($id);
        $employeePayslipAdHoc->update([
            'company_salary_type_id' => $data->company_salary_type_id,
            'name' => $data->name,
            'amount' => $data->amount,
            'note' => $data->note,
            'date' =>  $data->date
        ]);

        return $employeePayslipAdHoc;
    }

    public function deleteById($id)
    {
        $employeePayslipAdHoc = EmployeePayslipAdHoc::where('id', $id)->firstOrFail();
        $employeePayslipAdHoc->delete();
        return true;
    }
}
