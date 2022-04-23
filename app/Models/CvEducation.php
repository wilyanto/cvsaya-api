<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CvEducation extends Model implements Auditable
{
    use HasFactory, SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'cv_educations';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    protected $dates = [
        'started_at',
        'ended_at',
    ];

    public $fillable = [
        'id',
        'user_id',
        'instance',
        'degree_id',
        'field_of_study',
        'grade',
        'started_at',
        'ended_at',
        'description'
    ];

    public function experiences()
    {
        return $this->hasOne(CvExperience::class, 'user_id', 'user_id');
    }

    public function certifications()
    {
        return $this->hasOne(CvCertification::class, 'user_id', 'user_id');
    }

    public function specialities()
    {
        return $this->hasOne(CvSpeciality::class, 'user_id', 'user_id');
    }

    public function hobbies()
    {
        return $this->hasOne(CvHobby::class, 'user_id', 'user_id');
    }

    public function degree()
    {
        return $this->hasOne(Degree::class, 'id', 'degree_id');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'instance' => $this->instance,
            'degree' => $this->degree,
            'field_of_study' => $this->field_of_study,
            'grade' => $this->grade,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
