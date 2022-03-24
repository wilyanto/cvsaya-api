<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function log()
    {
        return $this->hasMany(LogCandidateEmpolyeeSchedule::class, 'employee_candidate_id');
    }

    public function result()
    {
        return $this->hasOne(InterviewResult::class, 'id', 'result_id');
    }


    public function candidate()
    {
        return $this->hasOne(CandidateEmployee::class, 'id', 'employee_candidate_id');
    }

    public function interviewBy()
    {
        return $this->hasOne(EmployeeDetail::class, 'id', 'interview_by');
    }

    public function characterTraits()
    {
        return $this->hasManyThrough(CharacterTrait::class, CandidateEmployeeScheduleCharacterTrait::class, 'id', 'candidate_employee_schedule_id', 'character_trait_id', 'id');
    }

    public function toArrayCandidate()
    {
        $getCandidate = $this->candidate;

        return [
            'id' => $getCandidate->id,
            'user_id' => $getCandidate->user_id,
            'name' => $getCandidate->name,
            'interview_date' => $getCandidate->date_time,
            'phone_number' => $getCandidate->phone_number,
            'register_date' => $getCandidate->register_date,
        ];
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'employee_candidate_id' => $this->toArrayCandidate(),
            'interview_by' => $this->interviewBy->CvProfileDetail->first_name .' '. $this->interviewBy->CvProfileDetail->last_name,
            'note' => $this->note,
            'character_trait' => $this->characterTraits,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'result_id' => $this->result,
        ];
    }
}
