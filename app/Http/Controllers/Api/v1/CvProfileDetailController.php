<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\CvProfileDetail;
use App\Models\CvDomicile;
use App\Models\CvSosmed;
use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CvEducation;
use App\Models\CvCertification;
use App\Models\CvSpeciality;
use App\Models\CvHobby;
use App\Models\CvExperience;
use App\Models\CvDocument;
use App\Models\CvExpectedJob;
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

    public function getDetailByDefault(Request $request)
    {
        return $this->getDetailByID($request, null);
    }

    public function getDetailByID(Request $request, $id)
    {
        $user = auth()->user();
        if (!$id) {
            $id = $user->id_kustomer;
        }
        $array = [];
        $userProfileDetail = CvProfileDetail::where('user_id', $id)->firstOrFail();
        $userAddress = CvDomicile::where('user_id', $id)->firstOrFail();
        $userSosmed = CvSosmed::where('user_id', $id)->firstOrFail();

        $array['profile_detail'] = $userProfileDetail;
        $array['domicile'] = $userAddress;
        $array['sosmed'] = $userSosmed;
        $collectionArray = collect($array);
        return $this->showOne($collectionArray);
    }


    public function cvDetailByDefault()
    {
        return $this->cvDetailByID(null);
    }

    public function cvDetailByID($id)
    {
        $user = auth()->user();
        if (!$id) {
            $id = $user->id_kustomer;
        }
        $education = CvEducation::where('user_id', $id)
            ->orderBy('started_at', 'DESC')
            ->orderByRaw("CASE WHEN ended_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('ended_at', 'DESC')
            ->get();
        $data['educations'] = $education;

        $experience = CvExperience::where('user_id', $id)
            ->orderBy('started_at', 'DESC')
            ->orderByRaw("CASE WHEN ended_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('ended_at', 'DESC')
            ->get();
        $data['experiences'] = $experience;

        $certifications = CvCertification::where('user_id', $id)
            ->orderBy('issued_at', 'DESC')
            ->orderByRaw("CASE WHEN expired_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('expired_at', 'DESC')
            ->get();
        $data['certifications'] = $certifications;

        $specialities = CvSpeciality::where('user_id', $id)->get();
        $data['specialities'] = $specialities;

        $hobbies = CvHobby::where('user_id', $id)->get();
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
        $document = CvDocument::where('user_id', $id)->first();
        $expectedSalaries = CvExpectedJob::where('user_id', $id)->first();

        $data['is_profile_completed'] = true;
        $data['is_job_completed'] = true;
        $data['is_document_completed'] = true;
        $data['is_cv_completed'] = true;
        if (!$userProfileDetail || !$userProfileDetail->addresses || !$userProfileDetail->sosmeds) {
            $data['is_profile_completed'] = false;
        }

        if (!$expectedSalaries) {
            $data['is_job_completed'] = false;
        }

        if (!$education || !$education->experiences || !$education->certifications || !$education->specialities || !$education->hobbies) {
            $data['is_cv_completed'] = false;
        }

        if (!$document || !$document->identityCard || !$document->frontSelfie ||!$document->rightSelfie ||!$document->leftSelfie ) {
            $data['is_document_completed'] = false;
        }
        $result['basic_profile'] = [
            'first_name' => $userProfileDetail->first_name,
            'last_name' => $userProfileDetail->last_name,
        ];

        $employee = EmployeeDetail::where('user_id', $id)->first();
        if ($employee) {
            $result['is_employee'] = true;
            $position = [
                'id' => $employee->position ? $employee->position->id : null,
                'name' => $employee->position ? $employee->position->name : null,
                'company' => $employee->position ? $employee->position->company : null
            ];
            $result['position'] = $position;
        } else {
            $result['position'] = null;
        }

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
            'last_name' => 'string|nullable|min:3',
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

        $candidate = Candidate::where('phone_number', substr($user->telpon, 1))->first();
        if (!$candidate) {
            $candidate = new Candidate();
        }
        $candidate->user_id = $user->id_kustomer;
        $candidate->name = $request->first_name . " " . $request->last_name;
        $candidate->phone_number = (int) substr($user->telpon, 1);
        $candidate->country_code = 62;
        $candidate->registered_at = date('Y-m-d H:i:s', time());
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
        $user = auth()->user();

        $employee = EmployeeDetail::where('user_id',$user->id_kustomer)->firstOrFail();

        $data = [
            'profile' => $employee->profileDetail,
            'roles' => $employee->getRoleNames(),
            'permissions' => $employee->getAllPermissions()->pluck('name')
        ];
        return $this->showOne(collect($data));
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
        $url = env('KADA_URL') . "/v1/domicile/validation";
        $data = [
            'country_code' => $country,
            'province_code' => $province,
            'city_code' => $city,
            'sub_district_code' => $subDistrict,
            'village_code' => $village,
        ];
        $response = Http::withHeaders([
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
            'profile_detail.religion_id' => 'exists:religions,id|required',

            #Address
            'domicile.province_id' => 'integer|required',
            'domicile.city_id' => 'integer|required',
            'domicile.subdistrict_id' => 'integer|required',
            'domicile.village_id' => 'integer|required',
            'domicile.address' => 'string|required',

            #Sosmed
            'sosmed.instagram' => 'string|nullable',
            'sosmed.tiktok' => 'string|nullable',
            'sosmed.youtube' => 'string|nullable',
            'sosmed.facebook' => 'string|nullable',
            'sosmed.website_url' => 'nullable', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]i',
        ]);

        $json = $request->input();

        $requestProfile = $json['profile_detail'];

        $requestAddress = $json['domicile'];
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

            $userAddress = CvDomicile::where('user_id', $user->id_kustomer)->first();
            if ($userAddress) {
                $userAddress->fill($requestAddress);

                $validation = self::validateAddress(
                    $requestAddress['country_id'],
                    $requestAddress['province_id'],
                    $requestAddress['city_id'],
                    $requestAddress['subdistrict_id'],
                    $requestAddress['village_id'],
                    explode(' ', $request->header('Authorization'))[1]
                );
                if ($validation['status'] != 200) {
                    return $this->errorResponse(collect($validation['message']), $validation['status'], $validation['message']['code']);
                }
                $userAddress->save();
            } else {
                $userAddress = CvDomicile::create($requestAddress);
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
            $array['domicile'] = $userAddress;
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
