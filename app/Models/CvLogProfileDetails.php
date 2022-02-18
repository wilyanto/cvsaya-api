<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvLogProfileDetails extends Model
{
    use HasFactory;

    protected $table = 'log_profile_details';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $timestamps = false;

}
