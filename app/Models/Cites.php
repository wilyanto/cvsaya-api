<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cites extends Model
{
    use HasFactory;

    protected $table = 'cites';

    protected $guard = 'id';

    protected $primaryKey = 'id';

}
