<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use OwenIt\Auditing\Contracts\Auditable;

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

    public function requestDomicile($url)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
            ->get(
                $url,
            );
        return [
            'status' => $response->status(),
            'message' => $response->json()['data'],
        ];
    }

    public function country()
    {
        $url = env('KADA_URL') . "/v1/domicile/countries/" . $this->country_id;
        $response = $this->requestDomicile($url);
        return $response['message'];
    }
    public function province()
    {
        if ($this->province_id) {
            $url = env('KADA_URL') . "/v1/domicile/provinces/" . $this->province_id;
            $this->requestDomicile($url);
            $response = $this->requestDomicile($url);
            return $response['message'];
        }
        return null;
    }
    public function city()
    {
        if ($this->city_id) {
            $url = env('KADA_URL') . "/v1/domicile/cities/" . $this->city_id;
            $this->requestDomicile($url);
            $response = $this->requestDomicile($url);

            return $response['message'];
        }
        return null;
    }
    public function subDistrict()
    {
        if ($this->subdistrict_id) {
            $url = env('KADA_URL') . "/v1/domicile/sub-districts/" . $this->subdistrict_id;
            $this->requestDomicile($url);
            $response = $this->requestDomicile($url);
            return $response['message'];
        }
        return null;
    }

    public function village()
    {
        if ($this->village_id) {
            $url = env('KADA_URL') . "/v1/domicile/villages/" . $this->village_id;
            $this->requestDomicile($url);
            $response = $this->requestDomicile($url);
            return $response['message'];
        }
        return null;
    }


    public function toArray()
    {
        $url = env('KADA_URL') . "/v1/domicile";
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
            ->get(
                $url,
            );

        $result = [
            'id' => $this->id,
            'candidate_id' => $this->candidate_id,
            'address' => $this->address,
        ];

        if ($response->status() == 200) {
            $decodedDomicile = json_decode($response->body());
            $result['province'] = $decodedDomicile->province;
            $result['city'] = $decodedDomicile->city;
            $result['subdistrict'] = $decodedDomicile->subdistrict;
            $result['village'] = $decodedDomicile->village;
        }

        return $result;

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
