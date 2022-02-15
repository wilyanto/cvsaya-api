<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSpecialities extends Model
{
    use HasFactory;

    protected $table = 'cvsaya_log_specialities';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
