<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Positions;

class EmployeeDetails extends Model
{
    use HasFactory;

    protected $table = 'employee_details';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'position_id',
        'salary',
    ];

    public function position(){
        return $this->belongsTo(Positions::class,'id','position_id');
    }
}
