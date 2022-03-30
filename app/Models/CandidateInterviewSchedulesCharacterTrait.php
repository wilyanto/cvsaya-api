<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateInterviewSchedulesCharacterTrait extends Model
{
    use HasFactory;

    public $fillable = [
        'candidate_employee_schedule_id',
        'character_trait_id'
    ];
}
