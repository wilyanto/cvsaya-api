<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeResignation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'note',
        'resignation_date'
    ];
}
