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
        'instance',
        'degree_id',
        'field_of_study',
        'grade',
        'start_at',
        'until_at',
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

    public function degree(){
        return $this->hasOne(Degree::class,'id','degree_id');
    }

    public function toArray()
    {
        return [
            'id'=> $this->id,
            'user_id' => $this->user_id,
            'instance'=> $this->instance,
            'degree' => $this->degree,
            'field_of_study' => $this->field_of_study,
            'grade' => $this->grade,
            'start_at' => $this->start_at,
            'until_at' => $this->until_at,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at'=> $this->updated_at,
        ];
    }
}
