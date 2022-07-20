<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySalaryType extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'salary_type_id'
    ];

    public function salaryType()
    {
        return $this->belongsTo(SalaryType::class);
    }
}
