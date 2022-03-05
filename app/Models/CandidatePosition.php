<?php

namespace App\Models;

use App\Models\CvExpectedPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidatePosition extends Model
{
    use HasFactory;

    protected $table = 'candidate_positions';

    protected $primaryKey = 'id';

    public $fillable = [
        'validated_at',
        'name',
        'inserted_by'
    ];

    public function Candidate()
    {
        return $this->hasManyThrough(CandidateEmployee::class, CvExpectedPosition::class, 'expected_position', 'user_id', 'id', 'user_id');
    }
}
