<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CvSpecialityCertificate;
use Illuminate\Database\Eloquent\SoftDeletes;

class CvSpeciality extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'cv_specialities';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'user_id',
        'name',
        'speciality_certifcate_id',
    ];

    public function certifcates(){
        return $this->hasManyThrough(CvCertification::class,CvSpecialityCertificate::class,'certificate_id','id','id','speciality_id');
    }


    public function toArray()
    {
        // dump($this->certifcates);
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
    // public function toArray()
    // {
    //     // dump($this->certification_id);
    //     $certifcates = CvSayaSpecialiteCertificates::where('id',$this->speciality_certificate_id)->first();
    //     // dd($certifcates->toArray());
    //     $data = [
    //         'id' => $this->id,
    //         'user_id' => $this->user_id,
    //         'name' => $this->name,
    //         'certificates' => $certifcates,
    //     ];
    //     return $data;
    // }
}
