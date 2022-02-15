<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Educations extends Model
{
    use HasFactory;

    protected $table = 'cvsaya_educations';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'user_id',
        'school',
        'degree',
        'field_of_study',
        'grade',
        'start_at',
        'until_at',
        'activity',
        'description'
    ];
}
