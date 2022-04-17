<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;

class Administrator extends Authenticatable implements Auditable
{
    use CrudTrait; // <----- this
    use HasRoles;
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    public $fillable = [
        'name',
        'email',
        'password',
    ];
}
