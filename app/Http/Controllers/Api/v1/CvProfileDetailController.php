<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\CvProfileDetail;
use App\Models\CvAddress;
use App\Models\CvSosmed;
use App\Http\Controllers\Controller;
use App\Models\CandidateEmployee;
use App\Models\CvEducation;
use App\Models\CvCertification;
use App\Models\CvSpeciality;
use App\Models\CvHobby;
use App\Models\CvExperience;
use App\Models\CvDocumentation;
use App\Models\CvExpectedPosition;
use Illuminate\Http\Request;
use App\Models\EmployeeDetail;
use App\Models\Religion;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

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
        $userProfileDetail = CvProfileDetail::where('user_id', $user->id_kustomer)->firstOrFail();
        $userAddress = CvAddress::where('user_id', $user->id_kustomer)->firstOrFail();
        $userSosmed = CvSosmed::where('user_id', $user->id_kustomer)->firstOrFail();

        $array['profile_detail'] = $userProfileDetail;
        $array['address'] = $userAddress;
        $array['sosmed'] = $userSosmed;
        $collectionArray = collect($array);
        return $this->showOne($collectionArray);
    }

    public function cvDetail()
    {
        $user = auth()->user();

        $education = CvEducation::where('user_id', $user->id_kustomer)
            ->orderBy('start_at', 'DESC')
            ->orderByRaw("CASE WHEN until_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('until_at', 'DESC')
            ->get();
        $data['education'] = $education;

        $experience = CvExperience::where('user_id', $user->id_kustomer)
            ->orderBy('start_at', 'DESC')
            ->orderByRaw("CASE WHEN until_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('until_at', 'DESC')
            ->get();
        $data['experience'] = $experience;

        $certifications = CvCertification::where('user_id', $user->id_kustomer)
            ->orderBy('issued_at', 'DESC')
            ->orderByRaw("CASE WHEN expired_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('expired_at', 'DESC')
            ->get();
        $data['certifications'] = $certifications;

        $specialities = CvSpeciality::where('user_id', $user->id_kustomer)->get();
        $data['specialities'] = $specialities;

        $hobbies = CvHobby::where('user_id', $user->id_kustomer)->get();
        $data['hobbies'] = $hobbies;

        $data = (object)$data;

        return $this->showOne(collect($data));
    }

    public function status()
    {
        $user = auth()->user();

        $status = $this->getStatus($user->id_kustomer);

        return $status;
    }


    public function getStatus($id)
    {


        $userProfileDetail = CvProfileDetail::where('user_id', $id)->firstOrFail();
        $education = CvEducation::where('user_id', $id)->first();
        $document = CvDocumentation::where('user_id', $id)->first();
        $expectedSalaries = CvExpectedPosition::where('user_id', $id)->first();

        $data['is_profile_filled'] = true;
        $data['is_works_filled'] = true;
        $data['is_document_filled'] = true;
        $data['is_cv_filled'] = true;
        if (!$userProfileDetail || !$userProfileDetail->addresses || !$userProfileDetail->sosmeds) {
            $data['is_profile_filled'] = false;
        }

        if (!$expectedSalaries) {
            $data['is_works_filled'] = false;
        }

        if (!$education || !$education->experiences || !$education->certifications || !$education->specialities || !$education->hobbies) {
            $data['is_cv_filled'] = false;
        }

        if (!$document) {
            $data['is_document_filled'] = false;
        }
        $result['profile'] = [
            'first_name' => $userProfileDetail->first_name,
            'last_name' => $userProfileDetail->last_name,
        ];

        $employee = EmployeeDetail::where('user_id', $id)->first();
        if ($employee) {
            $result['is_employee'] = true;
            $position = [
                'id' => $employee->position->id,
                'name' => $employee->position->name,
                'company' => $employee->position->company
            ];
            $result['profile'] = $employee;
            $result['position'] = $position;
        } else {
            $result['position'] = null;
        }

        $array = count(array_filter($data));;
        $result['completeness_status'] = $data;

        return $this->showOne($result);
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
            'reference' => 'string|nullable',
        ]);
        if (CvProfileDetail::where('user_id', $user->id_kustomer)->first()) {
            return $this->errorResponse('User already created', 409, 40901);
        }

        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $this->createCandidate($user, $request);
        // dd($data);
        $userProfileDetail = CvProfileDetail::create($data);
        return $this->showOne($userProfileDetail);
    }

    public function createCandidate($user, $request)
    {

        $candidate = CandidateEmployee::where('phone_number', substr($user->telpon, 1))->first();
        if (!$candidate) {
            $candidate = new CandidateEmployee();
        }
        $candidate->user_id = $user->id_kustomer;
        $candidate->name = $request->first_name . " " . $request->last_name;
        $candidate->phone_number = (int) substr($user->telpon, 1);
        $candidate->register_date = date('Y-m-d H:i:s', time());
        $candidate->status = 3;
        $candidate->save();
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

    public function validateAddress($country, $province, $city, $subDistrict, $village, $token)
    {
        $url = env('KADA_URL') . "/v1/domicile/villages/validation";
        $data = [
            'country_code' => $country,
            'province_code' => $province,
            'city_code' => $city,
            'sub_district_code' => $subDistrict,
            'village_code' => $village,
        ];
        $response = Http::withtoken($token)->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
            ->get(
                $url,
                $data
            );
        return [
            'status' => $response->status(),
            'message' => $response->json()['meta'],
        ];

        // return $response;
    }

    public function update(Request $request)
    {
        // dump($request);
        $user = auth()->user();

        CvProfileDetail::where('user_id', $user->id_kustomer)->firstOrFail();

        $request->validate([
            #Profile Detail
            'profile_detail.birth_location' => 'string|required',
            'profile_detail.birth_date' => 'date|required',
            'profile_detail.gender' => 'required|string',
            'profile_detail.identity_number' => 'required|integer|min:5',
            'profile_detail.marriage_status_id' => 'exists:marriage_statuses,id|required',
            'profile_detail.religion_id' => 'exists:Religions,id|required',

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
            'sosmed.website_url' => 'required', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]i',
        ]);

        $json = $request->input();

        $requestProfile = $json['profile_detail'];

        $requestAddress = $json['address'];
        $requestAddress['user_id'] = $user->id_kustomer;
        $requestAddress['country_id'] = 62;

        $requestSosmeds = $json['sosmed'];
        $requestSosmeds['user_id'] = $user->id_kustomer;

        try {
            DB::beginTransaction();
            $userProfileDetail = CvProfileDetail::where('user_id', $user->id_kustomer)->first();
            $userProfileDetail->fill($requestProfile);
            $userProfileDetail->religion_id = $requestProfile['religion_id'];
            $userProfileDetail->marriage_status_id = $requestProfile['marriage_status_id'];
            if ($userProfileDetail->isDirty()) {
                $userProfileDetail->update($requestProfile);
            }

            $userAddress = CvAddress::where('user_id', $user->id_kustomer)->first();
            if ($userAddress) {
                $userAddress->fill($requestAddress);
                $validation = self::validateAddress(
                    $requestAddress['country_id'],
                    $requestAddress['province_id'],
                    $requestAddress['city_id'],
                    $requestAddress['district_id'],
                    $requestAddress['village_id'],
                    explode(' ', $request->header('Authorization'))[1]
                );
                if ($validation['status'] != 200) {
                    return $this->errorResponse(collect($validation['message']), $validation['status'], $validation['code']);
                }
            } else {
                $userAddress = CvAddress::create($requestAddress);
            }

            $userSosmed = CvSosmed::where('user_id', $user->id_kustomer)->first();
            if ($userSosmed) {
                $userSosmed->fill($requestSosmeds);
                if ($userSosmed->isDirty()) {
                    $userSosmed->update($requestSosmeds);
                }
            } else {
                $userSosmed = CvSosmed::create($requestSosmeds);
            }
            $array['profile_detail'] = $userProfileDetail;
            $array['address'] = $userAddress;
            $array['sosmed'] = $userSosmed;
            $object = (object)$array;
            DB::commit();
            return $this->showOne($object);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 500, 50001);
        }
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
