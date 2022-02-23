<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\CvProfileDetail;
use App\Models\CvAddress;
use App\Models\CvSosmeds;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;

class CvProfileDetailController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {
        $user = auth()->user();
        // dump($userCollection);
        $array = [];
        $userProfileDetail = CvProfileDetail::where('user_id', $user->id_kustomer)->first();
        $userAddress = CvAddress::where('user_id', $user->id_kustomer)->first();
        $userSosmed = CvSosmeds::where('user_id', $user->id_kustomer)->first();

        if (!$userProfileDetail || !$userAddress || !$userSosmed) {
            return $this->errorResponse('profile or address or sosmed data not found', 404, 40401);
        }

        $array['profile_detail'] = $userProfileDetail;
        $array['address'] = $userAddress;
        $array['sosmed'] = $userSosmed;
        $collectionArray = collect($array);
        return $this->showOne($collectionArray);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'first_name' => 'string|required|min:3',
            'last_name' => 'string|required|min:3',
        ]);

        // $data = $request->all();
        // dd($data);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        // dd($data);
        $userProfileDetail = CvProfileDetail::create($data);
        return $this->showOne($userProfileDetail);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserProfileDetail  $userProfileDetail
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserProfileDetail  $userProfileDetail
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserProfileDetail  $userProfileDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        // return 'test';
        // dd($user);
        $userProfileDetails = CvProfileDetail::where('user_id', $user->id_kustomer)->first();
        if (!$userProfileDetails) {
            return $this->errorResponse('id not found', 404, 40401);
        }
        // dd($userProfileDetails);

        // dd($request->input('json'));
        // dd(json_decode($request->input('json')));

        $request->validate([
            #Profile Detail
            'profile_detail.birth_location' => 'string|required',
            'profile_detail.birth_date' => 'date|required',
            'profile_detail.gender' => 'required|string',
            'profile_detail.identity_number' => 'required|min:5',
            'profile_detail.religion' => 'required|in:Buddha,Islam,Kristen,Hindu,Kong Hu Cu',
            'profile_detail.married' => 'required|string',

            #Address
            'address.province_id' => 'integer|required',
            'address.city_id' => 'integer|required',
            'address.district_id' => 'integer|required',
            'address.village_id' => 'integer|required',
            'address.detail' => 'string|required',

            #Sosmed
            'sosmed.instagram' => 'string',
            'sosmed.tiktok' => 'string',
            'sosmed.youtube' => 'string',
            'sosmed.facebook' => 'string',
            'sosmed.website_url' => 'required', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i',
        ]);
        // dd($request->input());
        $json = $request->input();

        $requestProfile = $json['profile_detail'];

        $requestAddress = $json['address'];
        $requestAddress['user_id'] = $user->id_kustomer;
        $requestAddress['country_id'] = 62;
        // dd($requestAddress);

        $requestSosmeds = $json['sosmed'];
        $requestSosmeds['user_id'] = $user->id_kustomer;

        // dd($requestProfile);
        try {
            DB::beginTransaction();
            $userProfileDetail = CvProfileDetail::where('user_id', $user->id_kustomer)->first();
            $userProfileDetail->fill($requestProfile);
            if ($userProfileDetail->isDirty()) {
                $userProfileDetail->update($requestProfile);
            }

            $userAddress = CvAddress::where('user_id', $user->id_kustomer)->first();
            if ($userAddress) {
                $userAddress->fill($requestAddress);
                if ($userAddress->isDirty()) {
                    $userAddress->update($requestAddress);
                }
            } else {
                $userAddress = CvAddress::create($requestAddress);
            }

            $userSosmed = CvSosmeds::where('user_id', $user->id_kustomer)->first();
            if ($userSosmed) {
                $userSosmed->fill($requestSosmeds);
                if ($userSosmed->isDirty()) {
                    $userSosmed->update($requestSosmeds);
                }
            } else {
                $userSosmed = CvSosmeds::create($requestSosmeds);
            }
            $array['profile_detail'] = $userProfileDetail;
            $array['address'] = $userAddress;
            $array['sosmed'] = $userSosmed;
            // dd($array);
            $object = (object)$array;
            // dd($collection);
            DB::commit();
            return $this->showOne($object);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 500, 50001);
        }




        // $data = $request->all();
        // dd($data);
        return $this->showOne($userProfileDetail);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserProfileDetail  $userProfileDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }
}
