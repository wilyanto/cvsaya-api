<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CandidateInterviewSchedule extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'candidate_interview_schedules';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    protected $dateFormat = 'Y-m-d\TH:i:s.v\Z';

    public $fillable = [
        'id',
        'candidate_id',
        'interviewed_at',
        'interviewed_by',
        'result_id',
        'note',
    ];
    public function log()
    {
        return $this->hasMany(LogCandidateEmpolyeeSchedule::class, 'candidate_id');
    }

    public function setInterviewedAtAttribute($value)
    {
        return $this->attributes['interviewed_at'] = date('Y-m-d\TH:i:s.v\Z', strtotime($value));
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
        return $this->hasOne(EmployeeDetail::class, 'id', 'interviewed_by');
    }

    public function characterTraits()
    {
        return $this->hasManyThrough(CharacterTrait::class, CandidateInterviewSchedulesCharacterTrait::class, 'candidate_interview_schedule_id', 'id',  'id', 'character_trait_id');
    }

    // public function getInterviewedAtAttribute($value)
    // {
    //     return ucfirst($value);
    // }

    public function toArrayCandidate()
    {
        $getCandidate = $this->candidate;
        $education = $this->candidate ? $this->candidate->educations : null;
        return [
            'id' => $getCandidate ? $getCandidate->id : null,
            'user_id' => $getCandidate ? $getCandidate->user_id : null,
            'name' => $getCandidate ? $getCandidate->name : null,
            'interviewed_at' => $this->interviewed_at ? date('Y-m-d\TH:i:s.v\Z', strtotime($this->interviewed_at)) : null,
            'phone_number' => $getCandidate ? $getCandidate->phone_number : null,
            'register_date' => $getCandidate ? $getCandidate->register_date : null,
            'education' => $education->first(),
            'job' => $getCandidate ? ($getCandidate->job ? $getCandidate->job->position : null) : null,
        ];
    }

    public function toArraySchedule()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->candidate ? $this->candidate->user_id : null,
            'candidate' => $this->toArrayCandidate(),
            'interviewer' => $this->interviewer(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'rejected_at' => $this->rejected_at ?  date('Y-m-d\TH:i:s.v\Z', strtotime($this->rejected_at)) : null,
        ];
    }

    public function interviewer()
    {
        if ($this->interviewBy) {
            return [
                'id' => $this->interviewBy->id,
                'first_name' => $this->interviewBy->profileDetail->first_name,
                'last_name' => $this->interviewBy->profileDetail->last_name,
            ];
        } else {
            return null;
        }
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
            // 'rejected_at' => $this->rejected_at ?  date('Y-m-d\TH:i:s.v\Z', strtotime($this->rejected_at)) : null,
            'rejected_at' => $this->rejected_at,
        ];
    }

    public function interviewResult()
    {
        return [
            'interviewer' => $this->interviewer(),
            'note' => $this->note,
            'character_traits' => $this->characterTraits,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'result' => $this->result,
            'rejected_at' => $this->rejected_at ?  date('Y-m-d\TH:i:s.v\Z', strtotime($this->rejected_at)) : null,
        ];
    }
}
