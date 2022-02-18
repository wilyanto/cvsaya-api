<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvLogHobbies extends Model
{
    use HasFactory;

    protected $table = 'log_hobbies';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
