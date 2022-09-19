<?php

namespace App\Models;

use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model implements Auditable
{
    use HasFactory, SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'shifts';

    public $fillable = [
        'name',
        'clock_in',
        'clock_out',
        'start_break',
        'end_break',
        'break_duration',
        'company_id',
        'created_at',
        'updated_at'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function breakEndedAt($breakStartedAt)
    {
        return date('H:i:s', strtotime($breakStartedAt, ' +' . $this->break_duration . 'hours'));
    }
}
