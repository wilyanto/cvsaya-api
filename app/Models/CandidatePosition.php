<?php

namespace App\Models;

use App\Models\Candidate;
use App\Models\CvExpectedJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CandidatePosition extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    public $fillable = [
        'name',
        'validated_at',
    ];

    public $dates = [
        'validated_at',
    ];

    public function candidates()
    {
        return $this->hasManyThrough(Candidate::class, CvExpectedJob::class, 'expected_position', 'id', 'id', 'candidate_id');
    }

    public function toArrayCategories()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'total_candidates' => $this->candidates->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    public function getCandidateStatistic($startDate, $endDate)
    {
        return $this->candidates()->whereBetween('registered_at', [$startDate, $endDate])->get();
    }
}
