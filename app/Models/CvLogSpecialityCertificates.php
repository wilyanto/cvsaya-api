<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CvLogSpecialityCertificates extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'cv_log_speciality_certifications';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
