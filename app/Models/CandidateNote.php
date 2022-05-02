<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CandidateNote extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'candidate_notes';

    public $fillable = [
        'note',
        'employee_id',
        'candidate_id',

    ];

    public function candidate()
    {
        return $this->hasOne(Candidate::class, 'id', 'candidate_id');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'note' => $this->note,
            'candidate' => $this->candidate->nameOnly(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
