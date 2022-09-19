<?php

namespace App\Services;

use App\Enums\EmployeeResignationStatusEnum;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeResignation;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeeResignationService
{
    public function getAll()
    {
        $employeeResignations = QueryBuilder::for(EmployeeResignation::class)
            ->allowedIncludes(['employee'])
            ->get();

        return $employeeResignations;
    }

    public function getById($id)
    {
        $query = EmployeeResignation::where('id', $id);
        $employeeResignation = QueryBuilder::for($query)
            ->allowedIncludes(['employee'])
            ->firstOrFail();

        return $employeeResignation;
    }

    public function createEmployeeResignation($data)
    {
        $employeeResignation = EmployeeResignation::create([
            'employee_id' => $data->employee_id,
            'note' => $data->note,
            'resignation_date' => $data->resignation_date,
            'status' => EmployeeResignationStatusEnum::pending(),
            'consideration' => $data->consideration
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
            'consideration' => $data->consideration
        ]);

        return $employeeResignation;
    }

    public function deleteById($id)
    {
        $employeeResignation = EmployeeResignation::where('id', $id)->firstOrFail();
        $employeeResignation->delete();
        return true;
    }

    public function updateEmployeeResignationStatus($data, $employeeResignationId)
    {
        $employeeResignation = $this->getById($employeeResignationId);
        $employeeResignation->update([
            'status' => $data->status,
        ]);

        return $employeeResignation;
    }

    public function showResignationsByCompany($request, $companyId)
    {
        $pageSize = $request->input('page_size', 10);
        $keyword = $request->input('keyword', '');
        $status = $request->input('status', null);
        $company = Company::findOrFail($companyId);
        $query = $company->resignations();
        if ($status) {
            $query = $query->where('employee_resignations.status', $status);
        }
        $employeeResignations = $query
            ->whereHas('employee', function ($query) use ($keyword) {
                $query->whereHas('candidate', function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', '%' . $keyword . '%');
                });
            })->latest()
            ->paginate($pageSize);

        foreach ($employeeResignations as $employeeResignation) {
            $employeeResignation = $employeeResignation->load(['employee']);
        }

        return $employeeResignations;
    }

    public function showResignationsByEmployee($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $employeeResignations = $employee->resignations;

        foreach ($employeeResignations as $employeeResignation) {
            $employeeResignation = $employeeResignation->load(['employee']);
        }

        return $employeeResignations;
    }
}
