<?php

namespace App\Models;

use App\Models\CvExpectedPositions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidatePositions extends Model
{
    use HasFactory;

    public function Candidate()
    {
        return $this->hasManyThrough(CandidateEmployees::class, CvExpectedPositions::class, 'expected_position', 'user_id', 'id', 'user_id');
    }
}
