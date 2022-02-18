<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateLogEmpolyeeSchedule extends Model
{
    use HasFactory;

    protected $table = 'candidate_empolyee_schedules';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public function log(){
        return $this->belongsTo(CandidateEmpolyeeSchedule::class,'empolyee_candidate_id');
    }

}
