<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateInterviewSchedulesCharacterTrait extends Model
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    public $fillable = [
        'candidate_interview_schedule_id',
        'character_trait_id'
    ];
}
