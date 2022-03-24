<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class CvAddress extends Model
{
    use HasFactory;

    protected $table = 'cv_addresses';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'user_id',
        'empolyee_candidate_id',
        'date_time',
        'interview_by',
        'country_id',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'result',
        'note',
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
        $url = env('KADA_URL') . "/v1/domicile/sub-districts/" . $this->district_id;
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
            'sub_district' => $this->subDistrict(),
            'village' => $this->village(),
            'detail' => $this->detail,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
