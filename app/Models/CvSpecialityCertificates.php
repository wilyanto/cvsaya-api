<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CvSpecialities;
use App\Models\CvCertifications;

use Illuminate\Database\Eloquent\SoftDeletes;

class CvSpecialityCertificates extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'cv_speciality_certifications';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $timestamps =[
        'id',
        'certificate_id',
    ];

    public function speciality(){
        return $this->belongsTo(CvSpecialities::class,'speciality_id','id');
    }

    public function certifcate(){
        return $this->belongsTo(CvCertifications::class,'certificate_id','id');
    }

    // public function toArray()
    // {
    //     $certificates = CvSayaCertifications::where('id',$this->certificate_id)->get();
    //     $data = [];
    //     foreach($certificates as $certificate){
    //         $data[] = [
    //             'id' => $certificate->id,
    //             'name' => $certificate->name,
    //             'issued_at' => $certificate->issued_at,
    //             'expired_at' => $certificate->expired_at,
    //             'credential_id' => $certificate->credential_id,
    //             'credential_url' => $certificate->credential_url,
    //         ];
    //     }
    //     return $data;
    // }

}
