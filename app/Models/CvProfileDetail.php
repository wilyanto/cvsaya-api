<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvProfileDetail extends Model
{
    use HasFactory;

    protected $casts = [
        'selfie_picture' => 'array',
    ];

    public $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'birth_location',
        'birth_date',
        'gender',
        'identity_number',
        'religion',
        'reference',
    ];

    protected $table = 'cv_profile_details';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public function Addresses(){
        return $this->hasOne(CvAddress::class,'user_id','user_id');
    }

    public function Sosmeds(){
        return $this->hasOne(CvSosmed::class,'user_id','user_id');
    }

    public function EmployeeDetails(){
        return $this->belongsToMany(EmployeeDetails::class,'user_id','user_id');
    }
}
