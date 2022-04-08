<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CvSpeciality;
use App\Models\CvCertification;
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\SoftDeletes;

class CvSpecialityCertificate extends Model implements Auditable
{
    use HasFactory,SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'cv_specialities_certifications';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $timestamps =[
        'id',
        'certificate_id',
    ];

    public function speciality(){
        return $this->belongsTo(CvSpeciality::class,'speciality_id','id');
    }

    public function certifcate(){
        return $this->hasOne(CvCertification::class,'certificate_id','id');
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
