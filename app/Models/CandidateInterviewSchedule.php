<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateInterviewSchedule extends Model
{
    use HasFactory;

    protected $table = 'candidate_interview_schedules';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'candidate_id',
        'interview_at',
        'interview_by',
        'result_id',
        'note',
    ];

    public function log()
    {
        return $this->hasMany(LogCandidateEmpolyeeSchedule::class, 'candidate_id');
    }

    public function result()
    {
        return $this->hasOne(InterviewResult::class, 'id', 'result_id');
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class, 'id', 'candidate_id');
    }

    public function interviewBy()
    {
        return $this->hasOne(EmployeeDetail::class, 'id', 'interview_by');
    }

    public function characterTraits()
    {
        return $this->hasManyThrough(CharacterTrait::class, CandidateInterviewSchedulesCharacterTrait::class, 'candidate_interview_schedule_id', 'id',  'id','character_trait_id');
    }

    public function toArrayCandidate()
    {
        $getCandidate = $this->candidate;
        $education = $this->candidate->educations;
        return [
            'id' => $getCandidate->id,
            'user_id' => $getCandidate->user_id,
            'name' => $getCandidate->name,
            'interview_at' => $this->interview_at,
            'phone_number' => $getCandidate->phone_number,
            'register_date' => $getCandidate->register_date,
            'education' => $education->first(),
            'job' => $getCandidate->job->expected_position,
        ];
    }

    public function toArraySchedule(){
        return [
            'id' => $this->id,
            'user_id' => $this->candidate->user_id,
            'candidate' => $this->toArrayCandidate(),
            'interviewer' => $this->interviewer(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function interviewer(){
        return [
            'id' => $this->interviewBy->id,
            'first_name' => $this->interviewBy->profileDetail->first_name,
            'last_name' => $this->interviewBy->profileDetail->last_name,
        ];

    }

    public function toArray()
    {

        return [
            'id' => $this->id,
            'user_id' => $this->candidate->user_id,
            'candidate' => $this->toArrayCandidate(),
            'interviewer' => $this->interviewer(),
            'note' => $this->note,
            'character_traits' => $this->characterTraits,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'result' => $this->result,
        ];
    }

    public function interviewResult(){
        return [
            'interviewer' => $this->interviewer(),
            'note' => $this->note,
            'character_traits' => $this->characterTraits,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'result' => $this->result,
        ];
    }
}
