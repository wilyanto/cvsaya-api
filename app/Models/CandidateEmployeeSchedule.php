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
        $education = $this->candidate->educations;
        return [
            'id' => $getCandidate->id,
            'user_id' => $getCandidate->user_id,
            'name' => $getCandidate->name,
            'interview_date' => $this->date_time,
            'phone_number' => $getCandidate->phone_number,
            'register_date' => $getCandidate->register_date,
            'education' => $education->first(),
            'job' => $getCandidate->job->expected_position,
        ];
    }

    public function toArraySchedule(){
        return [
            'id' => $this->id,
            'candidate' => $this->toArrayCandidate(),
            'interviewer' => $this->interviewBy->interviewerDetail(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'candidate' => $this->toArrayCandidate(),
            'interviewer' => $this->interviewBy->interviewerDetail(),
            'note' => $this->note,
            'character_traits' => $this->characterTraits,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'result_id' => $this->result,
        ];
    }
}
