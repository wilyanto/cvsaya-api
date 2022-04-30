<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeSalaryType extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $guard = 'id';

    public $connection = 'mysql';

    protected $table = 'employees_salary_types';

    protected $primaryKey = 'id';

    public $fillable = [
        'employee_id',
        'amount',
        'salary_type_id',
    ];

    public $timestamps = false;


    public function salaryType(){
        return $this->hasOne(SalaryType::class,'id','salary_type_id');
    }

}
