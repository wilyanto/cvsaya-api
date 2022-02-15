<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sosmeds extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cvsaya_social_medias';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'user_id',
        'name',
        'value'
    ];
}
