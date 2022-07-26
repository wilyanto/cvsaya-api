<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAdHoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'company_salary_type_id',
        'name',
        'date',
        'amount',
        'note'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function companySalaryType()
    {
        return $this->belongsTo(CompanySalaryType::class);
    }
}
