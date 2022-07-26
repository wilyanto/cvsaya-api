<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePayslip extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_period_id',
        'status',
        'generated_at',
        'generated_by',
        'paid_at',
        'paid_by'
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'paid_at' => 'datetime'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    public function payslipDetails()
    {
        return $this->hasMany(EmployeePayslipDetail::class);
    }

    public function payslipAdHocs()
    {
        return $this->hasMany(EmployeePayslipAdHoc::class);
    }

    public function paidBy()
    {
        return $this->belongsTo(Employee::class, 'paid_by');
    }

    public function generatedBy()
    {
        return $this->belongsTo(Employee::class, 'generated_by');
    }
}
