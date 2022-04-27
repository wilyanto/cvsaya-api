<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Shift extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'shifts';

    public $fillable = [
        'id',
        'name',
        'clock_in',
        'clock_out',
        'break_started_at',
        'break_ended_at',
        'break_duration',
        'created_at',
        'updated_at'
    ];

    public function breakEndedAt($breakStartedAt){
        return date('H:i:s',strtotime($breakStartedAt,' +'.$this->break_duration.'hours'));
    }
}
