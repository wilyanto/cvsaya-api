<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Result;

class CandidateEmployeeSchedule extends Model
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

    public function Result(){
        return $this->hasOne(ResultInterview::class,'id','result_id');
    }

    public function candidate(){
        $getCandidate = $this->hasOne(CandidateEmployee::class,'id','employee_candidate_id');

        return [
            'id' => $getCandidate->id,
            'name' => $getCandidate->name,
            'phone_number' => $getCandidate->phone_number,
            'register_date' => $getCandidate->register_date,
        ];
    }

}
