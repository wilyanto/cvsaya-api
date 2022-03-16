<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CvExpectedPosition extends Model
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
        'reasons',
    ];

    public function candidates(){
        return $this->hasMany(CandidateEmployees::class,'user_id','user_id');
    }
}

