<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CvExpectedSalaries extends Model
{
    use HasFactory;

    protected $table = 'cv_expected_salaries';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'user_id',
        'amount_before',
        'expected_position ',
        'expected_amount',
        'reason_position',
        'reasons',
    ];

    public function Positions(){
        return $this->HasMany(Positions::class,'id','expected_position');
    }
}
