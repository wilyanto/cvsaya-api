<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Penalty extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'penalties';

    public $fillable = [
        'id',
        'name',
        'amount',
        'company_id',
        'passing_at',
        'attendance_types_id'
    ];

    public function company()
    {
        return  $this->hasOne(Company::class, 'company_id', 'id');
    }
}
