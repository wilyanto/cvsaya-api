<?php

namespace App\Services;

use App\Enums\SalaryTypeEnum;
use App\Models\EmployeeAdHoc;
use App\Models\SalaryType;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeeAdHocService
{
    public function getAll()
    {
        $salaryTypes = QueryBuilder::for(EmployeeAdHoc::class)
            ->allowedFilters([
                AllowedFilter::exact('employee_id'),
            ])
            ->get();

        return $salaryTypes;
    }

    public function getById($id)
    {
        $query = EmployeeAdHoc::where('id', $id);
        $salaryType = QueryBuilder::for($query)
            ->firstOrFail();

        return $salaryType;
    }

    public function createEmployeeAdHoc($data)
    {
        $salaryType = EmployeeAdHoc::create([
            'employee_id' => $data->employee_id,
            'company_salary_type_id' => $data->company_salary_type_id,
            'name' => $data->name,
            'date' => $data->date,
            'amount' => $data->amount,
            'note' => $data->note,
        ]);

        return $salaryType;
    }

    public function updateEmployeeAdHoc($data, $id)
    {
        $salaryType = $this->getById($id);
        $salaryType->update([
            'employee_id' => $data->employee_id,
            'company_salary_type_id' => $data->company_salary_type_id,
            'name' => $data->name,
            'date' => $data->date,
            'amount' => $data->amount,
            'note' => $data->note,
        ]);

        return $salaryType;
    }

    public function deleteById($id)
    {
        $salaryType = EmployeeAdHoc::where('id', $id)->firstOrFail();
        $salaryType->delete();
        return true;
    }
}
