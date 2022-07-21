<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePayslipAdhoc extends Model
{
    use HasFactory;

    protected $fillable = [
        'payslip_id',
        'employee_ad_hoc_id',
    ];
}
