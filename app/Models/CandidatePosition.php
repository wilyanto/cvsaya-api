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
        'inserted_by'
    ];

    public function candidates()
    {
        return $this->hasManyThrough(Candidate::class, CvExpectedJob::class, 'expected_position', 'user_id', 'id', 'user_id');
    }

    public function toArrayCategories(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'inserted_by' => $this->inserted_by,
            'total_candidates' => $this->candidates->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
