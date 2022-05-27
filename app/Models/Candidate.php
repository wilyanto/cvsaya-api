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
        'profile_picture'
    ];

    public function domicile()
    {
        return $this->hasOne(CvDomicile::class)->withDefault();
    }

    public function suggestBy()
    {
        return $this->hasOne(Employee::class, 'id', 'suggested_by')->withDefault();
    }

    public function education()
    {
        return $this->hasOne(CvEducation::class, 'candidate_id', 'id');
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

    public function hobbies()
    {
        return $this->hasMany(CvHobby::class, 'candidate_id', 'id');
    }

    public function experiences()
    {
        return $this->hasMany(CvExperience::class, 'candidate_id', 'id');
    }

    public function certifications()
    {
        return $this->hasMany(CvCertification::class, 'candidate_id', 'id');
    }

    public function specialities()
    {
        return $this->hasMany(CvSpeciality::class, 'candidate_id', 'id');
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

    public function document()
    {
        return $this->hasOne(CvDocument::class, 'candidate_id', 'id');
    }

    public function candidateNotes()
    {
        return $this->hasMany(CandidateNote::class, 'candidate_id', 'id');
    }

    public function getCompletenessStatus()
    {
        $completenessCount = 0;
        if ($this->document) {
            $completenessCount += $this->document->front_selfie != null ? 0 : 1;
            $completenessCount += $this->document->identity_card != null ? 0 : 1;
        }

        if ($this->profile->id != null) {
            $completenessCount += 1;
        }

        if ($this->job->id != null) {
            $completenessCount += 1;
        }

        if ($this->educations()->count() != 0) {
            $completenessCount += 0.2;
        }

        if ($this->hobbies()->count() != 0) {
            $completenessCount += 0.2;
        }

        if ($this->experiences()->count() != 0) {
            $completenessCount += 0.2;
        }

        if ($this->certifications()->count() != 0) {
            $completenessCount += 0.2;
        }

        if ($this->specialities()->count() != 0) {
            $completenessCount += 0.2;
        }

        return $completenessCount / 5 * 100;
    }

    public function getProfilePictureUrl()
    {
        // https: laracasts.com/discuss/channels/laravel/show-images-from-storage-folder
        if (!$this->profile_picture) {
            return null;
        }
        return url('/storage/images/profile_picture/' . $this->profile_picture);
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
            'domicile' => $this->domicile,
            'front_selfie_document_id' => $this->document == null ? null : $this->document->front_selfie,
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
            'domicile' => $this->domicile,
            'job' => $this->job,
            'profile_picture_url' => $this->getProfilePictureUrl(),
            'front_selfie_document_id' => $this->document == null ? null : $this->document->front_selfie,
            'is_reviewed' => $this->candidateNotes()->count() != 0,
            'completeness_percentage' => $this->getCompletenessStatus(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function nameOnly()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
