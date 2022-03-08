<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Spatie\Permission\Traits\HasRoles;

class Administrator extends Authenticatable
{
    use CrudTrait; // <----- this
    use HasRoles;
    use HasFactory;

    public $fillable = [
        'name',
        'email',
        'password',
    ];
}
