<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvLogEducations extends Model
{
    use HasFactory;

    protected $table = 'cv_log_educations';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
