<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Shift extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'shifts';

    public $fillable = [
        'id',
        'name',
        'started_at',
        'ended_at',
        'break_started_at',
        'break_ended_at',
        'break_duration',
        'created_at',
        'updated_at'
    ];
}
