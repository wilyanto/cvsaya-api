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

    protected $table = 'cv_domiciles';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'user_id',
        'country_id',
        'province_id',
        'city_id',
        'subdistrict_id',
        'village_id',
        'address',
    ];

    public function result()
    {
        return $this->hasOne(Result::class, 'id', 'result_id');
    }

    public function profileDetails()
    {
        return $this->belongsTo(CvProfileDetail::class, 'user_id', 'user_id');
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
        $url = env('KADA_URL') . "/v1/domicile/provinces/" . $this->province_id;
        $this->requestDomicile($url);
        $response = $this->requestDomicile($url);
        return $response['message'];
    }
    public function city()
    {
        $url = env('KADA_URL') . "/v1/domicile/cities/" . $this->city_id;
        $this->requestDomicile($url);
        $response = $this->requestDomicile($url);

        return $response['message'];
    }
    public function subDistrict()
    {
        $url = env('KADA_URL') . "/v1/domicile/sub-districts/" . $this->subdistrict_id;
        $this->requestDomicile($url);
        $response = $this->requestDomicile($url);
        return $response['message'];
    }

    public function village()
    {
        $url = env('KADA_URL') . "/v1/domicile/villages/" . $this->village_id;
        $this->requestDomicile($url);
        $response = $this->requestDomicile($url);
        return $response['message'];
    }


    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'country' => $this->country(),
            'province' => $this->province(),
            'city' => $this->city(),
            'subdistrict' => $this->subDistrict(),
            'village' => $this->village(),
            'address' => $this->address,
            'created_at' => date('Y-m-d\TH:i:s.v\Z',strtotime($this->created_at)),
            'updated_at' => date('Y-m-d\TH:i:s.v\Z',strtotime($this->updated_at)),
        ];
    }
}
