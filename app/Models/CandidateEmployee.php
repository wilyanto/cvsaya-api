<?php

namespace App\Models;

use App\Models\CvAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Http\Controllers\Api\v1\CvProfileDetailController;

class CandidateEmployee extends Model
{
    use HasFactory;

    public const BLASTING = 1;

    public const REGISTEREDKADA = 2;

    public const INPUTINGKADA = 3;

    public const ReadyToInterview = 4;

    public const INTERVIEW = 5;

    public const STANDBY = 6;

    public const PASS = 7;

    public const CONSIDER = 8;

    public const ACCEPTED = 9;

    public const DECLINE = 10;

    public const RESULT_RECOMMENDED = 1;
    public const RESULT_HOLD = 2;
    public const RESULT_BAD = 3;

    protected $table = 'candidate_employees';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'name',
        'phone_number',
        'user_id',
        'status',
        'suggest_by',
        'register_date',
    ];

    public function address()
    {
        // dd($)
        return $this->hasOne(CvAddress::class, 'user_id', 'user_id');
    }

    public function suggestBy()
    {
        return $this->hasOne(EmployeeDetail::class, 'id', 'suggest_by');
    }

    public function schedules()
    {
        return $this->hasMany(CandidateEmployeeSchedule::class, 'employee_candidate_id', 'id');
    }

    public function educations()
    {
        return $this->hasMany(CvEducation::class, 'user_id', 'user_id')
            ->orderBy('start_at', 'DESC')
            ->orderByRaw("CASE WHEN until_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('until_at', 'DESC');
    }

    public function profile(){
        return $this->hasOne(CvProfileDetail::class,'user_id','user_id');
    }

    public function job()
    {
        return $this->hasOne(CvExpectedJob::class, 'user_id', 'user_id');
    }


    public function results()
    {
        return $this->hasManyThrough(InterviewResult::class, CandidateEmployeeSchedule::class,  'employee_candidate_id', 'id', 'id', 'result_id');
    }

    public function label()
    {
        $result = CandidateEmployeeSchedule::where('employee_candidate_id', $this->id)->orderBy('created_at', 'DESC')->first();
        if ($result) {
            return $result->result;
        }
        return null;
    }


    public function listDefaultCandidate()
    {
        $status = $this->status;
        if($this->status == 3){
            $candidateController = new CvProfileDetailController;

            $profileStatus = $candidateController->getStatus($this->user_id);
            $profileStatus = $status->original;
            $profileStatus = $status['data']['completeness_status'];
            if (
                $profileStatus['is_profile_completed'] == true &&
                $profileStatus['is_job_completed'] == true &&
                $profileStatus['is_document_completed']  == true &&
                $profileStatus['is_cv_completed'] == true
            ){
                $status = 4;
            }
        }
        $value = $this->label();
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'country_code' => $this->country_code,
            'register_at' => $this->register_at,
            'status' => $status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'label' => $this->label(),
            'religion' => $this->profile->religion,
            'educations' => $this->educations->first(),
            'gender' => $this->profile->gender,
            'address' => $this->address != null ? $this->address->province() : null,
        ];
    }


    public function toArrayByNote(){

        Collection::macro('schedule',function(){
            return $this->map(function ($value){
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
        $value = $this->label();
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'country_code' => $this->country_code,
            'register_at' => $this->register_at,
            'status' => $this->status,
            'suggest_by' => $this->SuggestBy,
            'many_requst' => $this->many_request,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'schedule' => $this->schedule,
            'label' => $this->label(),
        ];
    }
}
