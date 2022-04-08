<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class CvExpectedJob extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'cv_expected_jobs';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'user_id',
        'expected_position',
        'expected_salary',
        'position_reason',
        'salary_reason',
    ];

    protected $casts = [
        'expected_salary' => 'integer',
    ];

    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'user_id', 'user_id');
    }

    public function position()
    {
        return $this->hasOne(CandidatePosition::class, 'id', 'expected_position');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'expected_salary' => $this->expected_salary,
            'expected_position' => $this->position,
            'position_reason' => $this->position_reason,
            'salary_reason' => $this->salary_reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
