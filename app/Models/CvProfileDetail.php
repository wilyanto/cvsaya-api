<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use OwenIt\Auditing\Contracts\Auditable;


class CvProfileDetail extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $casts = [
        'selfie_picture' => 'array',
    ];

    protected $guard = 'id';


    public $fillable = [
        'candidate_id',
        'birth_location',
        'birth_date',
        'gender',
        'identity_number',
        'religion_id',
        'marriage_status_id',
    ];

    public function addresses()
    {
        return $this->hasOne(CvDomicile::class, 'candidate_id', 'candidate_id')->withDefault();
    }

    public function sosmeds()
    {
        return $this->hasOne(CvSosmed::class, 'candidate_id', 'candidate_id')->withDefault();
    }

    public function religion()
    {
        return $this->hasOne(Religion::class, 'id', 'religion_id');
    }

    public function marriageStatus()
    {
        return $this->hasOne(MarriageStatus::class, 'id', 'marriage_status_id');
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class, 'id', 'candidate_id')->withDefault();
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->candidate->name,
            'candidate_id' => $this->candidate_id,
            'phone_number' => $this->candidate->phone_number,
            'birth_location' => $this->birth_location,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'identity_number' => $this->identity_number,
            'marriage_status' => $this->marriageStatus,
            'religion' => $this->religion,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
