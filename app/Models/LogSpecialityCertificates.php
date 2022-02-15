<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogSpecialityCertificates extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'cvsaya_log_speciality_certifications';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
