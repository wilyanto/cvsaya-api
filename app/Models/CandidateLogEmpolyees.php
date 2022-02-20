<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateLogEmpolyees extends Model
{
    use HasFactory;

    protected $table = 'candidate_log_employees';

    protected $guard = 'id';

    protected $primaryKey = 'id';
}
