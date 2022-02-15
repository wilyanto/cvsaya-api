<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogHobbies extends Model
{
    use HasFactory;

    protected $table = 'cvsaya_log_hobbies';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
