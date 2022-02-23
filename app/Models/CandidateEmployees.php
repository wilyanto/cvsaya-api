<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateEmployees extends Model
{
    use HasFactory;

    public const BLASTING = 1;

    public const REGISTEREDKADA = 2;

    public const INPUTINGKADA = 3;

    public const INTERVIEW = 5;

    public const STANDBY = 6;

    public const PASS = 6;

    public const ACCEPTED = 7;

    public const DECLINE = 8;

    protected $table = 'candidate_employees';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'name',
        'country_code',
        'phone_number',
        'user_id',
        'status',
        'suggest_by',
        'filled_form',
        'register_date',
    ];
}
