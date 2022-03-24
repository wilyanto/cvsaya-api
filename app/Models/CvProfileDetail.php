<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    public function Addresses()
    {
        return $this->hasOne(CvAddress::class, 'user_id', 'user_id');
    }

    public function Sosmeds()
    {
        return $this->hasOne(CvSosmed::class, 'user_id', 'user_id');
    }

    public function EmployeeDetails()
    {
        return $this->belongsToMany(EmployeeDetails::class, 'user_id', 'user_id');
    }

    public function Religion()
    {
        return $this->hasOne(Religion::class, 'id', 'religion_id');
    }

    public function MarriageStatus()
    {
        return $this->hasOne(MarriageStatus::class, 'id', 'marriage_status_id');
    }

    public function candidate(){
        return $this->hasOne(CandidateEmployee::class,'user_id','user_id');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_number' => $this->candidate->phone_number,
            'birth_location' => $this->birth_location,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'identity_number' => $this->identity_number,
            'marriage_status' => $this->MarriageStatus,
            'reference' => $this->reference,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'religion' => $this->Religion,
        ];
    }

   
}
