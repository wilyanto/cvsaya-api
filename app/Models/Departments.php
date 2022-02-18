<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departments extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'departments';

    public $fillable = [
        'id',
        'name',
        'company_id',
    ];

    public function positions(){
        return $this->hasMany(Positions::class,'department_id','id');
    }

}
