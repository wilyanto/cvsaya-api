<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Level extends Model implements Auditable
{
    use HasFactory,SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'levels';

    public $fillable = [
        'id',
        'name',
        'company_id',
    ];

    public function positions(){
        return $this->hasMany(Positions::class,'level_id','id');
    }
}
