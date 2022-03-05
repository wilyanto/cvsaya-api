<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CvEducation extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'cv_educations';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'user_id',
        'school',
        'degree',
        'field_of_study',
        'grade',
        'start_at',
        'until_at',
        'activity',
        'description'
    ];

    public function Experiences(){
        return $this->hasOne(CvExperience::class,'user_id','user_id');
    }

    public function Certifications(){
        return $this->hasOne(CvCertification::class,'user_id','user_id');
    }

    public function Specialities(){
        return $this->hasOne(CvSpeciality::class,'user_id','user_id');
    }

    public function Hobbies(){
        return $this->hasOne(CvHobby::class,'user_id','user_id');
    }
}
