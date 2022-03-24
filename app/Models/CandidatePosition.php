<?php

namespace App\Models;

use App\Models\CandidateEmployee;
use App\Models\CvExpectedJob;
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

    public function candidates()
    {
        return $this->hasManyThrough(CandidateEmployee::class, CvExpectedJob::class, 'expected_position', 'user_id', 'id', 'user_id');
    }
}
