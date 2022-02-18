<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\UserProfileDetail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class UserProfileDetailController extends Controller
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
        $userProfileDetails = UserProfileDetail::where('id', $user->id)->first();
        if ($userProfileDetails) {

            $array = [
                'name' => $user->nama_lengkap,
                // 'grander' => $user->jeniskelamin,
                'position' => 'Unkonwon',
                'company_id' => $user->ID_perusahaan,
                'address' => $user->alamat,
                'about' => $userProfileDetails->about,
                'phone_num' => $user->telpon,
                'webiste_url' => $userProfileDetails->website_url,
                'religion' => $userProfileDetails->religion,
                'webiste_url' => $userProfileDetails->website_url,
            ];
        } else {
            $array = [
                'name' => $user->nama_lengkap,
                'grander' => $user->jeniskelamin,
                'position' => 'Unknown',
                'company_id' => $user->ID_perusahaan,
                'address' => $user->alamat,
                'about' => null,
                'phone_num' => $user->telpon,
                'webiste_url' => null,
                'religion' => null,
                'webiste_url' => null,
            ];
        }
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
        // dd($user);
        $userProfileDetails = UserProfileDetail::where('user_id', $user->id_kustomer)->first();
        if (!$userProfileDetails) {
            // dd($userProfileDetails);
            $request->validate([
                'about' => 'nullable|min:10|string',
                'website_url' => 'nullable|url',
                'selfie_picture' => 'json',
                'position_id' => 'nullable|integer',
                'religion' => 'nullable|in:Buddha,Islam,Kristen,Hindu,Kong Hu Cu',
                'reference' => 'nullable|string',
                'expected_position' => 'nullable|integer',
                'reason_position' => 'nullable|string|min:10',
                'about_position' => 'nullable|string|min:10',
                'identity_number' => 'nullable|string',
                'birth_date' => 'nullable|date'
            ]);

            $data = $request->all();
            $data['user_id'] = $user->id_kustomer;
            // dd($data);
            $userProfileDetail = UserProfileDetail::create($data);
            return $this->showOne($userProfileDetail);
        }
        return $this->errorResponse("User Profile has created", 409, 40901);
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
        // dd($user);
        $userProfileDetails = UserProfileDetail::where('user_id', $user->id_kustomer)->first();
        // dd($userProfileDetails);
        $request->validate([
            'about' => 'nullable|min:10',
            'website_url' => 'nullable|url',
            'selfie_picture' => 'json',
            'position_id' => 'nullable|integer',
            'religion' => 'nullable|in:Buddha,Islam,Kristen,Hindu,Kong Hu Cu',
            'reference' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;

        if (!$userProfileDetails) {
            $userProfileDetails->create($data);
        } else {
            $userProfileDetails->update($data);
        }

        return $this->showOne($userProfileDetails);
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
