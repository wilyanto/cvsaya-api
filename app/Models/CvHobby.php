<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CvHobby extends Model implements Auditable
{
    use HasFactory,SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'cv_hobbies';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'candidate_id',
        'name',
    ];
}
