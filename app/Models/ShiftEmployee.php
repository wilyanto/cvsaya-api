<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ShiftEmployee extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'shifts-employees';

    protected $dates = [
        'date'
    ];

    public $fillable = [
        'id',
        'shift_id',
        'employee_id',
        'date',
    ];

    public function employee()
    {
        return $this->hasOne(employee::class, 'id', 'employee_id');
    }

    public function shift(){
        return $this->hasOne(Shift::class,'id','shift_id');
    }
}
