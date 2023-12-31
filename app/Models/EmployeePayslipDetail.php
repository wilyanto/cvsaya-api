<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePayslipDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_payslip_id',
        'company_salary_type_id',
        'name',
        'amount',
        'note'
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    public function companySalaryType()
    {
        return $this->belongsTo(CompanySalaryType::class);
    }
}
