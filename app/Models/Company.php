<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait; // <------------------------------- this one
use Spatie\Permission\Traits\HasRoles;// <---------------------- and this one

class Company extends Model
{
    use HasFactory;
    use CrudTrait; // <----- this
    use HasRoles;

    protected $primaryKey = 'id';

    protected $table = 'companies';

    public $incrementing = false;

    public $fillable = [
        'id', 'name'
    ];
}
