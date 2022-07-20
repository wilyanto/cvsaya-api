<?php

namespace App\Services;

use App\Enums\SalaryTypeEnum;
use App\Models\CompanySalaryType;
use App\Models\EmployeeSalaryType;
use App\Models\SalaryType;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeeSalaryTypeService
{
    protected $salaryTypeService;

    public function __construct(SalaryTypeService $salaryTypeService)
    {
        $this->salaryTypeService = $salaryTypeService;
    }

    public function getAll()
    {
        $employeeSalaryTypes = QueryBuilder::for(EmployeeSalaryType::class)
            ->get();

        return $employeeSalaryTypes;
    }

    public function getById($id)
    {
        $query = EmployeeSalaryType::where('id', $id);
        $employeeSalaryType = QueryBuilder::for($query)
            ->firstOrFail();

        return $employeeSalaryType;
    }

    public function createEmployeeSalaryType($data)
    {
        $employeeSalaryType = EmployeeSalaryType::create([
            'employee_id' => $data->employee_id,
            'company_salary_type_id' => $data->company_salary_type_id,
            'amount_type' => $data->amount_type,
            'type' => $data->type,
        ]);

        return $employeeSalaryType;
    }

    public function updateEmployeeSalaryType($data, $id)
    {
        $employeeSalaryType = $this->getById($id);
        $employeeSalaryType->update([
            'employee_id' => $data->employee_id,
            'company_salary_type_id' => $data->company_salary_type_id,
            'amount_type' => $data->amount_type,
            'type' => $data->type,
        ]);

        return $employeeSalaryType;
    }

    public function deleteById($id)
    {
        $employeeSalaryType = EmployeeSalaryType::where('id', $id)->firstOrFail();
        $employeeSalaryType->delete();
        return true;
    }
}
