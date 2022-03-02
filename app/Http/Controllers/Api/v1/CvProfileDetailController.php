<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\CvProfileDetail;
use App\Models\CvAddress;
use App\Models\CvSosmeds;
use App\Http\Controllers\Controller;
use App\Models\CandidateEmployees;
use App\Models\CvEducations;
use App\Models\CvCertifications;
use App\Models\CvSpecialities;
use App\Models\CvHobbies;
use App\Models\CvExperiences;
use App\Models\CvDocumentations;
use App\Models\CvExpectedPositions;
use Illuminate\Http\Request;
use App\Models\EmployeeDetails;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

    public function cvDetail()
    {
        $user = auth()->user();

        $education = CvEducations::where('user_id', $user->id_kustomer)->get();
        $data['education'] = $education;

        $experience = CvExperiences::where('user_id', $user->id_kustomer)->get();
        $data['experience'] = $experience;

        $certifications = CvCertifications::where('user_id', $user->id_kustomer)->get();
        $data['certifications'] = $certifications;

        $specialities = CvSpecialities::where('user_id', $user->id_kustomer)->get();
        $data['specialities'] = $specialities;

        $hobbies = CvHobbies::where('user_id', $user->id_kustomer)->get();
        $data['hobbies'] = $hobbies;

        $data = (object)$data;

        return $this->showOne(collect($data));
    }

    public function status(){
        $user = auth()->user();

        $status = $this->getStatus($user->id_kustomer);

        return $status;
    }


    public function getStatus($id)
    {


        $userProfileDetail = CvProfileDetail::where('user_id', $id)->first();
        $education = CvEducations::where('user_id', $id)->first();
        $document = CvDocumentations::where('user_id', $id)->first();
        $expectedSalaries = CvExpectedPositions::where('user_id', $id)->first();

        $data['is_profile_filled'] = true;
        $data['is_works_filled'] = true;
        $data['is_document_filled'] = true;
        $data['is_cv_filled'] = true;
        // dump($userProfileDetail);
        if (!$userProfileDetail) {
            return $this->errorResponse('User not registerd yet', 418, 40901);
        }
        // dump($experience);
        if (!$userProfileDetail || !$userProfileDetail->addresses || !$userProfileDetail->sosmeds) {
            $data['is_profile_filled'] = false;
        }
        // dump($userProfileDetail);

        if (!$expectedSalaries) {
            $data['is_works_filled'] = false;
        }

        if (!$education || !$education->experiences || !$education->certifications || !$education->specialities || !$education->hobbies) {
            $data['is_cv_filled'] = false;
        }

        if (!$document) {
            $data['is_document_filled'] = false;
        }

        $employee = EmployeeDetails::where('user_id', $id)->first();
        // dump($employee);
        if ($employee) {
            $result['is_employee'] = true;
            $position = [
                'id' => $employee->position->id,
                'name' => $employee->position->name,
            ];
            $result['position'] = $position;
        } else {
            $result['is_employee'] = false;
            $result['position'] = null;
        }

        $result['first_name'] = $userProfileDetail->first_name;
        $result['last_name'] = $userProfileDetail->last_name;

        $array = count(array_filter($data));;

        switch ($array) {
            case (4):
                $result['is_all_form_filled'] = true;
                break;
            default:
                $result['is_all_form_filled'] = false;
        }
        $result['forms'] = $data;

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

        // $data = $request->all();
        // dd($data);

        // dd(CvProfileDetail::where('user_id', $user->id_kustomer)->first());
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

        $candidate = CandidateEmployees::where('phone_number', substr($user->telpon, 1))->first();
        if (!$candidate) {
            $candidate = new CandidateEmployees();
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
            'profile_detail.identity_number' => 'required|integer|min:5',
            'profile_detail.religion' => 'in:Buddha,Islam,Kristen,Hindu,Kong Hu Cu|required',
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
