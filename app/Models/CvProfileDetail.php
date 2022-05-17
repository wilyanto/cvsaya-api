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

    protected $append = ['full_name'];

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

    public function getFullNameAttribute()
    {
        $fullname = '';
        if ($this->first_name) {
            $fullname .= $this->first_name;
        }
        if ($this->last_name) {
            $fullname .= $this->last_name;
        }
        return $fullname;
    }

    public function addresses()
    {
        return $this->hasOne(CvDomicile::class, 'user_id', 'user_id')->withDefault();
    }

    public function sosmeds()
    {
        return $this->hasOne(CvSosmed::class, 'user_id', 'user_id')->withDefault();
    }

    public function employee()
    {
        return $this->belongsToMany(Employee::class, 'user_id', 'user_id')->withDefault();
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
        return $this->hasOne(Candidate::class, 'user_id', 'user_id')->withDefault();
    }

    public function scopeWithName($query, $name)
    {
        $names = explode(" ", $name);

        $query;
        foreach ($names as $name) {
            $query->where('first_name', 'LIKE', '%'.$name.'%')
                ->orWhere('last_name', 'LIKE', '%'.$name.'%');
        }
        return $query;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'full_name' => $this->full_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_number' => $this->candidate->phone_number,
            'birth_location' => $this->birth_location,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'identity_number' => $this->identity_number,
            'marriage_status' => $this->marriageStatus,
            'religion' => $this->religion,
            'reference' => $this->reference,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
