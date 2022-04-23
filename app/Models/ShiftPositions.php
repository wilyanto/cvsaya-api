<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ShiftPositions extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'shifts-positions';


    public $fillable = [
        'id',
        'shift_id',
        'position_id',
        'day',
    ];

    public function position()
    {
        return $this->hasOne(position::class, 'id', 'position_id');
    }

    public function shift()
    {
        return $this->hasOne(Shift::class, 'id', 'shift_id');
    }
}
