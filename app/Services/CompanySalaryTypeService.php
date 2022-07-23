<?php

namespace App\Services;

use App\Enums\SalaryTypeEnum;
use App\Models\CompanySalaryType;
use App\Models\EmployeeSalaryType;
use App\Models\SalaryType;
use Spatie\QueryBuilder\QueryBuilder;

class CompanySalaryTypeService
{
    public function getAll()
    {
        $salaryTypes = QueryBuilder::for(CompanySalaryType::class)
            ->allowedIncludes(['salaryType', 'company'])
            ->get();

        return $salaryTypes;
    }

    public function getById($id)
    {
        $query = CompanySalaryType::where('id', $id);
        $salaryType = QueryBuilder::for($query)
            ->allowedIncludes(['salaryType', 'company'])
            ->firstOrFail();

        return $salaryType;
    }

    public function createCompanySalaryType($data)
    {
        $salaryType = CompanySalaryType::create([
            'company_id' => $data->company_id,
            'salary_type_id' => $data->salary_type_id,
        ]);

        return $salaryType;
    }

    public function updateCompanySalaryType($data, $id)
    {
        $salaryType = $this->getById($id);
        $salaryType->update([
            'company_id' => $data->company_id,
            'salary_type_id' => $data->salary_type_id,
        ]);

        return $salaryType;
    }

    public function deleteById($id)
    {
        $salaryType = CompanySalaryType::where('id', $id)->firstOrFail();
        $salaryType->delete();
        return true;
    }
}
