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
        'salary_type_id',
        'amount',
        'amount_type'
    ];

    public function salaryType()
    {
        return $this->hasOne(SalaryType::class, 'id', 'salary_type_id');
    }
}
