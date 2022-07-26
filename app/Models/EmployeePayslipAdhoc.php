<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePayslipAdHoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_payslip_id',
        'employee_ad_hoc_id',
    ];

    public function payslip()
    {
        return $this->belongsTo(Payslip::class);
    }

    public function employeeAdHoc()
    {
        return $this->belongsTo(EmployeeAdHoc::class);
    }
}
