<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateNote extends Model
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'candidate_notes';
}
