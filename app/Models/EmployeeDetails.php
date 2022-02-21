<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDetails extends Model
{
    use HasFactory;

    protected $table = 'employee_details';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'position_id',
        'filled_form',
        'salary',
    ];
}
