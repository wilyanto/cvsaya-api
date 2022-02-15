<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'cvsaya_levels';

    public $fillable = [
        'id',
        'name',
        'company_id',
    ];

    public function positions(){
        return $this->hasMany(Positions::class,'level_id','id');
    }
}
