<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;

class Company extends Model implements Auditable
{
    use HasFactory;
    use CrudTrait; // <----- this
    use HasRoles;

    use \OwenIt\Auditing\Auditable;

    protected $primaryKey = 'id';

    protected $table = 'companies';

    public $incrementing = false;

    public $fillable = [
        'id', 'name'
    ];
}
