<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class InterviewResult extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $database = 'interview_results';

    public const RESULT_RECOMMENDED = 1;

    public const RESULT_HOLD = 2;

    public const RESULT_BAD = 3;
}
