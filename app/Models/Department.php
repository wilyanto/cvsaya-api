<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Department extends Model implements Auditable
{
    use HasFactory,SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'departments';

    public $fillable = [
        'id',
        'name',
        'company_id',
    ];

    public function positions(){
        return $this->hasMany(Position::class,'department_id','id');
    }

}
