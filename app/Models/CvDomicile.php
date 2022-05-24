<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use OwenIt\Auditing\Contracts\Auditable;
use PHPUnit\Framework\Constraint\Count;

class CvDomicile extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'candidate_id',
        'country_id',
        'province_id',
        'city_id',
        'subdistrict_id',
        'village_id',
        'address',
    ];

    public function result()
    {
        return $this->hasOne(Result::class, 'id', 'result_id')->withDefault();
    }

    public function profileDetails()
    {
        return $this->belongsTo(CvProfileDetail::class, 'candidate_id', 'candidate_id')->withDefault();
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'code', 'country_id');
    }

    public function province()
    {
        return $this->hasOne(Province::class, 'code', 'province_id');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'code', 'city_id');
    }

    public function subDistrict()
    {
        return $this->hasOne(SubDistrict::class, 'code', 'subdistrict_id');
    }

    public function village()
    {
        return $this->hasOne(Village::class, 'code', 'village_id');
    }


    public function toArray()
    {
        return [
            'id' => $this->id,
            'candidate_id' => $this->candidate_id,
            'address' => $this->address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'province' => $this->province,
            'city' => $this->city,
            'subdistrict' => $this->subDistrict,
            'village' => $this->village
        ];

        // return [
        //     'id' => $this->id,
        //     'candidate_id' => $this->candidate_id,
        //     'country' => $this->country(),
        //     // 'province' => $this->province(),
        //     // 'city' => $this->city(),
        //     // 'subdistrict' => $this->subDistrict(),
        //     // 'village' => $this->village(),
        //     'address' => $this->address,
        //     'created_at' => date('Y-m-d\TH:i:s.v\Z', strtotime($this->created_at)),
        //     'updated_at' => date('Y-m-d\TH:i:s.v\Z', strtotime($this->updated_at)),
        // ];
    }
}
