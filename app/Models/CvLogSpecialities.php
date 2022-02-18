<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvLogSpecialities extends Model
{
    use HasFactory;

    protected $table = 'log_specialities';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
