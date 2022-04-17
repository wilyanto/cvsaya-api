<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\EmploymentType;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CvExperience extends Model implements Auditable
{
    use HasFactory, SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'cv_experiences';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    protected $casts = [
        'previous_salary' => 'int',
    ];

    protected $dates = [
        'started_at',
        'ended_at',
    ];

    public $fillable = [
        'id',
        'user_id',
        'position_id',
        'employment_type_id',
        'company_name',
        'company_location',
        'started_at',
        'ended_at',
        'jobdesc',
        'payslip',
        'previous_salary',
        'reference',
        'resign_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_kustomer', 'user_id')->withDefault();
    }

    public function employeeType()
    {
        return $this->hasOne(EmploymentType::class, 'id', 'employment_type_id');
    }

    public function candidatePositions()
    {
        return $this->hasOne(CandidatePosition::class, 'id', 'position_id')->withDefault();
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'position' => $this->candidatePositions,
            'employment_type' => $this->employeeType,
            'company_name' => $this->company_name,
            'company_location' => $this->company_location,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'jobdesc' => $this->jobdesc,
            'reference' => $this->reference,
            'previous_salary' => (int) $this->previous_salary,
            'payslip' => $this->payslip,
            'resign_reason' => $this->resign_reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
