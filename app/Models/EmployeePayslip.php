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
}
