<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateEmployees extends Model
{
    use HasFactory;

    protected $table = 'candidate_employees';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'name',
        'country_code',
        'phone_number',
        'user_id',
        'register_date',
    ];
}
