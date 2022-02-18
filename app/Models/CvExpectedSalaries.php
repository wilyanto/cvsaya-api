<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'amount_now',
        'expected_amount',
        'reasons',
    ];
}
