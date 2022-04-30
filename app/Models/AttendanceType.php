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

    public const CLOCKIN = 'clock_in';

    public const CLOCKOUT = 'clock_out';

    public const BREAKSTARTEDAT = 'break_started_at';

    public const BREAKENDEDAT = 'break_ended_at';
}
