<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Result;

class CandidateEmpolyeeSchedule extends Model
{
    use HasFactory;

    protected $table = 'candidate_employee_schedules';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'employee_candidate_id',
        'date_time',
        'interview_by',
        'result_id',
        'note',
    ];

    public function log(){
        return $this->hasMany(LogCandidateEmpolyeeSchedule::class,'employee_candidate_id');
    }

    public function result(){
        return $this->hasOne(Result::class,'result_id');
    }
}
