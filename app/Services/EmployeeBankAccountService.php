<?php

namespace App\Services;

use App\Models\EmployeeBankAccount;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeeBankAccountService
{
    public function getAll()
    {
        $employeeBankAccounts = QueryBuilder::for(EmployeeBankAccount::class)
            ->get();

        return $employeeBankAccounts;
    }

    public function getById($id)
    {
        $query = EmployeeBankAccount::where('id', $id);
        $employeeBankAccount = QueryBuilder::for($query)
            ->firstOrFail();

        return $employeeBankAccount;
    }

    public function createEmployeeBankAccount($data)
    {
        $employeeBankAccount = EmployeeBankAccount::create([
            'account_name' => $data->account_name,
            'account_number' => $data->account_number,
            'taxpayer_identification_number' => $data->taxpayer_identification_number,
            'bank_name' => $data->bank_name,
            'bank_branch_name' => $data->bank_branch_name,
            'employee_id' => $data->employee_id,
        ]);

        return $employeeBankAccount;
    }

    public function updateEmployeeBankAccount($data, $id)
    {
        $employeeBankAccount = $this->getById($id);
        $employeeBankAccount->update([
            'account_name' => $data->account_name,
            'account_number' => $data->account_number,
            'taxpayer_identification_number' => $data->taxpayer_identification_number,
            'bank_name' => $data->bank_name,
            'bank_branch_name' => $data->bank_branch_name,
        ]);

        return $employeeBankAccount;
    }

    public function deleteById($id)
    {
        $employeeBankAccount = EmployeeBankAccount::where('id', $id)->firstOrFail();
        $employeeBankAccount->delete();
        return true;
    }

    public function getByEmployeeId($employeeId)
    {
        $query = EmployeeBankAccount::where('employee_id', $employeeId);
        $employeeBankAccount = QueryBuilder::for($query)
            ->firstOrFail();

        return $employeeBankAccount;
    }
}
