<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CvExpectedJob extends Model
{
    use HasFactory;

    protected $table = 'cv_expected_positions';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'user_id',
        'expected_position',
        'expected_amount',
        'reason_position',
        'reason_salary',
    ];

    protected $casts = [
        'expected_amount' => 'integer',
    ];

    public function candidates(){
        return $this->hasMany(CandidateEmployees::class,'user_id','user_id');
    }

    public function position(){
        return $this->hasOne(CandidatePosition::class,'id','expected_position');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'expected_amount' => $this->expected_amount,
            'expected_position' => $this->position,
            'reason_position' => $this->reason_position,
            'reason_salary' => $this->reason_salary,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

