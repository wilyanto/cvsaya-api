<?php

namespace App\Models;

use App\Models\CvDomicile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Http\Controllers\Api\v1\CvProfileDetailController;
use OwenIt\Auditing\Contracts\Auditable;

class Candidate extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    public const BLASTING = 1;

    public const REGISTERED_KADA = 2;

    public const INPUTING_KADA = 3;

    public const READY_TO_INTERVIEW = 4;

    public const INTERVIEW = 5;

    public const STANDBY = 6;

    public const CONSIDER = 8;

    public const ACCEPTED = 9;

    public const DECLINE = 10;

    protected $table = 'candidates';

    protected $dates = [
        'registered_at',
    ];

    protected $fillable = [
        'user_id',
        'name',
        'country_code',
        'phone_number',
        'status',
        'suggested_by',
        'registered_at',
    ];

    public function domicile()
    {
        return $this->hasOne(CvDomicile::class)->withDefault();
    }

    public function suggestBy()
    {
        return $this->hasOne(Employee::class, 'id', 'suggested_by')->withDefault();
    }

    public function schedules()
    {
        return $this->hasMany(CandidateInterviewSchedule::class, 'candidate_id', 'id');
    }

    public function educations()
    {
        return $this->hasMany(CvEducation::class, 'candidate_id', 'id')
            ->orderBy('started_at', 'DESC')
            ->orderByRaw("CASE WHEN ended_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('ended_at', 'DESC');
    }

    public function profile()
    {
        return $this->hasOne(CvProfileDetail::class)->withDefault();
    }

    public function job()
    {
        return $this->hasOne(CvExpectedJob::class, 'candidate_id', 'id')->withDefault();
    }


    public function results()
    {
        return $this->hasManyThrough(
            InterviewResult::class,
            CandidateInterviewSchedule::class,
            'candidate_id',
            'id',
            'id',
            'result_id'
        )->withDefault();
    }

    public function label()
    {
        $result = CandidateInterviewSchedule::where('candidate_id', $this->id)->whereNull('rejected_at')->orderBy('created_at', 'DESC')->first();
        if ($result) {
            return $result->result;
        }
        return null;
    }


    public function listDefaultCandidate()
    {
        $status = $this->status;
        if ($this->status == 3) {
            $candidateController = new CvProfileDetailController;

            $profileStatus = $candidateController->getStatus($this->id);
            $profileStatus = $profileStatus->original;
            $profileStatus = $profileStatus['data']['completeness_status'];
            if (
                $profileStatus['is_profile_completed'] == true &&
                $profileStatus['is_job_completed'] == true &&
                $profileStatus['is_document_completed']  == true &&
                $profileStatus['is_cv_completed'] == true
            ) {
                $status = 4;
            }
        }
        return [
            'id' => $this->id,
            'candidate_id' => $this->candidate_id,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'country_code' => $this->country_code,
            'registered_at' => $this->registered_at,
            'status' => $status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'last_assessment' => $this->label(),
            'religion' => $this->profile->religion,
            'profile' => $this->profile,
            'education' => $this->educations->first(),
            'gender' =>  $this->profile->gender,
            'position' => $this->job->position,
            'domicile' => $this->domicile->province(),
        ];
    }


    public function toArrayByNote()
    {

        Collection::macro('schedule', function () {
            return $this->map(function ($value) {
                return [
                    'interviewer' => $value->interviewer(),
                    'note' => $value->note,
                    'character_traits' => $value->characterTraits,
                    'created_at' => $value->created_at,
                    'updated_at' => $value->updated_at,
                    'result' => $value->result,
                ];
            });
        });

        $schedules = collect($this->schedules)->schedule();
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'schedules' => $schedules,
        ];
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'status' => $this->status,
            'country_code' => $this->country_code,
            'phone_number' => $this->phone_number,
            // 'interviews' => $this->schedules,
            'profile' => $this->profile,
            'latest_result' => $this->label(),
            'registered_at' => $this->registered_at,
            'domicile' => $this->domicile->province(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function nameOnly()
    {
        return [
            'id' => $this->id,
            'first_name' => $this->profile->first_name,
            'last_name' => $this->profile->last_name,
        ];
    }
}
