<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePayslipAdHoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_salary_type_id',
        'employee_payslip_id',
        'name',
        'date',
        'amount',
        'note'
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    public function payslip()
    {
        return $this->belongsTo(Payslip::class);
    }

    public function companySalaryType()
    {
        return $this->belongsTo(CompanySalaryType::class);
    }
}
