<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class AttendanceType extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'attendance_types';


    protected $cast = [
        'name' => 'string'
    ];

    public $fillable = [
        'id',
        'name'
    ];

    public const CLOCK_IN = 'clock_in';

    public const CLOCK_OUT = 'clock_out';

    public const BREAK_STARTED_AT = 'break_started_at';

    public const BREAK_ENDED_AT = 'break_ended_at';

    public const CLOCK_IN_ID = 1;

    public const CLOCK_OUT_ID = 2;

    public const BREAK_STARTED_AT_ID = 3;

    public const BREAK_ENDED_AT_ID = 4;

}
