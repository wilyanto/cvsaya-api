<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CvSpecialityCertificate;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CvSpeciality extends Model implements Auditable
{
    use HasFactory,SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'cv_specialities';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'candidate_id',
        'name',
        'speciality_certifcate_id',
    ];

    public function certifcates(){
        return $this->hasManyThrough(CvCertification::class,CvSpecialityCertificate::class,'speciality_id','id','id','certificate_id');
    }

    // public function certifcates(){
    //     // return $this->hasMany(CvSpecialityCertificate::class,'speciality_id','id');
    // }

    public function toArray()
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'certificates' => $this->certifcates,
            'created_at'=> $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
        return $data;
    }
}
