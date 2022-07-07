<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeResignation;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeeResignationService
{
    public function getAll()
    {
        $employeeResignations = QueryBuilder::for(EmployeeResignation::class)
            ->get();

        return $employeeResignations;
    }

    public function getById($id)
    {
        $query = EmployeeResignation::where('id', $id);
        $employeeResignation = QueryBuilder::for($query)
            ->firstOrFail();

        return $employeeResignation;
    }

    public function createEmployeeResignation($data)
    {
        $employeeResignation = EmployeeResignation::create([
            'employee_id' => $data->employee_id,
            'note' => $data->note,
            'resignation_date' => $data->resignation_date,
        ]);

        return $employeeResignation;
    }

    public function updateEmployeeResignation($data, $id)
    {
        $employeeResignation = $this->getById($id);
        $employeeResignation->update([
            'employee_id' => $data->employee_id,
            'note' => $data->note,
            'resignation_date' => $data->resignation_date,
        ]);

        return $employeeResignation;
    }

    public function deleteById($id)
    {
        $employeeResignation = EmployeeResignation::where('id', $id)->firstOrFail();
        $employeeResignation->delete();
        return true;
    }

    public function showResignationsByCompany($companyId)
    {
        $company = Company::findOrFail($companyId);
        $pageSize = 10;
        $employeeResignations = $company->resignations()->paginate($pageSize);

        return $employeeResignations;
    }

    public function showResignationsByEmployee($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $employeeResignations = $employee->resignations;

        return $employeeResignations;
    }
}
