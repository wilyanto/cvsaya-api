<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeeSalaryType extends Pivot
{
    use HasFactory, SoftDeletes;

    protected $table = 'employees_salary_types';

    protected $fillable = [
        'employee_id',
        'company_salary_type_id',
        'amount',
        'amount_type'
    ];

    public function companySalaryType()
    {
        return $this->hasOne(CompanySalaryType::class, 'id', 'company_salary_type_id');
    }
}
