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
        $employeeAdHocs = QueryBuilder::for(EmployeeAdHoc::class)
            ->allowedFilters([
                AllowedFilter::exact('employee_id'),
            ])
            ->get();

        return $employeeAdHocs;
    }

    public function getById($id)
    {
        $query = EmployeeAdHoc::where('id', $id);
        $employeeAdHoc = QueryBuilder::for($query)
            ->firstOrFail();

        return $employeeAdHoc;
    }

    public function createEmployeeAdHoc($data)
    {
        $employeeAdHoc = EmployeeAdHoc::create([
            'employee_id' => $data->employee_id,
            'company_salary_type_id' => $data->company_salary_type_id,
            'name' => $data->name,
            'date' => $data->date,
            'amount' => $data->amount,
            'note' => $data->note,
        ]);

        return $employeeAdHoc;
    }

    public function updateEmployeeAdHoc($data, $id)
    {
        $employeeAdHoc = $this->getById($id);
        $employeeAdHoc->update([
            'employee_id' => $data->employee_id,
            'company_salary_type_id' => $data->company_salary_type_id,
            'name' => $data->name,
            'date' => $data->date,
            'amount' => $data->amount,
            'note' => $data->note,
        ]);

        return $employeeAdHoc;
    }

    public function deleteById($id)
    {
        $employeeAdHoc = EmployeeAdHoc::where('id', $id)->firstOrFail();
        $employeeAdHoc->delete();
        return true;
    }
}
