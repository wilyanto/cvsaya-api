<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvLogExpectedSalaries extends Model
{
    use HasFactory;

    protected $table = 'cv_log_expected_salaries';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
