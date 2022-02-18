<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvAddress extends Model
{
    use HasFactory;

    protected $table = 'cv_address';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'empolyee_candidate_id',
        'date_time',
        'interview_by',
        'result',
        'note',
    ];

    public function Result(){
        return $this->hasOne(Result::class,'id','result_id');
    }
}
