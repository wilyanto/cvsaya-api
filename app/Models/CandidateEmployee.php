<?php

namespace App\Models;

use App\Models\CvAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateEmployee extends Model
{
    use HasFactory;

    public const BLASTING = 1;

    public const REGISTEREDKADA = 2;

    public const INPUTINGKADA = 3;

    public const ReadyToInterview = 4;

    public const INTERVIEW = 5;

    public const STANDBY = 6;

    public const PASS = 7;

    public const CONSIDER = 8;

    public const ACCEPTED = 9;

    public const DECLINE = 10;

    protected $table = 'candidate_employees';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'name',
        'phone_number',
        'user_id',
        'status',
        'suggest_by',
        'register_date',
    ];

    public function address(){
        // dd($)
        return $this->hasOne(CvAddress::class,'user_id','user_id');
    }
}
