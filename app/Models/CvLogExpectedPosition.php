<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvLogExpectedPosition extends Model
{
    use HasFactory;

    protected $table = 'cv_log_expected_positions';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
