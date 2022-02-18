<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvLogSosmeds extends Model
{
    use HasFactory;

    protected $table = 'log_social_medias';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
