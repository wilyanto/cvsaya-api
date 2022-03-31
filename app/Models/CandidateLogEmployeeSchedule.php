<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateLogEmployeeSchedule extends Model
{
    use HasFactory;

    protected $table = 'candidate_log_interview_schedules';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'candidate_id',
        'interview_at',
        'interview_by',
        'note',
        'rejected_at',
        'result_id',
    ];

    public function log()
    {
        return $this->belongsTo(CandidateEmpolyeeSchedule::class, 'empolyee_candidate_id');
    }
}
