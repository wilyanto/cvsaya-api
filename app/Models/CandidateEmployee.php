<?php

namespace App\Models;

use App\Models\CvAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function results()
    {
        return $this->hasManyThrough(InterviewResult::class, CandidateEmployeeSchedule::class,  'employee_candidate_id', 'id', 'id', 'result_id');
    }

    public function label()
    {
        $result = CandidateEmployeeSchedule::where('employee_candidate_id',$this->id)->orderBy('created_at','DESC')->first();
        if($result){
            return $result->result;
        }
        return null;
    }

    public function toArray()
    {
        $value = $this->label();
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'register_date' => $this->register_date,
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
