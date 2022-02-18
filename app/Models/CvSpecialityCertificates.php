<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CvSaya\Specialities;
use App\Models\CvSaya\Certifications;


class CvSpecialityCertificates extends Model
{
    use HasFactory;

    protected $table = 'speciality_certifications';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $timestamps =[
        'id',
        'certificate_id',
    ];

    public function speciality(){
        return $this->belongsTo(Specialities::class,'speciality_id','id');
    }

    public function certifcate(){
        return $this->belongsTo(Certifications::class,'certificate_id','id');
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
