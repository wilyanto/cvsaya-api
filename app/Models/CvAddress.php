<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvAddress extends Model
{
    use HasFactory;

    protected $table = 'cv_addresses';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'user_id',
        'empolyee_candidate_id',
        'date_time',
        'interview_by',
        'country_id',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'result',
        'note',
    ];

    public function result(){
        return $this->hasOne(Result::class,'id','result_id');
    }

    public function profileDetails(){
        return $this->belongsTo(CvProfileDetail::class,'user_id','user_id');
    }
}
