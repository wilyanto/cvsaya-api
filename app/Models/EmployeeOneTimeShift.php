<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeOneTimeShift extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $dates = [
        'date'
    ];

    public $fillable = [
        'employee_id',
        'shift_id',
        'date',
    ];

    public function employee()
    {
        return $this->hasOne(employee::class, 'id', 'employee_id');
    }

    public function shift()
    {
        return $this->hasOne(Shift::class, 'id', 'shift_id')->withDefault();
    }
}
