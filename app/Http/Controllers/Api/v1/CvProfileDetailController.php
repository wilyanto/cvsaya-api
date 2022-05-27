<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\CvProfileDetail;
use App\Models\CvDomicile;
use App\Models\CvSosmed;
use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CandidatePosition;
use App\Models\CvEducation;
use App\Models\CvCertification;
use App\Models\CvSpeciality;
use App\Models\CvHobby;
use App\Models\CvExperience;
use App\Models\CvDocument;
use App\Models\CvExpectedJob;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use stdClass;

use function PHPUnit\Framework\isEmpty;

class CvProfileDetailController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function indexDetail($id)
    {
        $array = [];
        $candidate = Candidate::findOrFail($id);
        $userProfileDetail = CvProfileDetail::where('candidate_id', $candidate->id)->first();
        $userAddress = CvDomicile::where('candidate_id', $candidate->id)->first();
        $userSosmed = CvSosmed::where('candidate_id', $candidate->id)->first();

        $array['profile_detail'] = $userProfileDetail ?? new stdClass();
        $array['domicile'] = $userAddress ?? new stdClass();
        $array['sosmed'] = $userSosmed ?? new stdClass();
        $collectionArray = collect($array);
        return $this->showOne($collectionArray);
    }

    public function index()
    {
        $candidate = Candidate::where('user_id', auth()->id())->first();
        $array = [];
        $userProfileDetail = CvProfileDetail::where('candidate_id', $candidate->id)->with('candidate')->first();
        $userAddress = CvDomicile::where('candidate_id', $candidate->id)->first();
        $userSosmed = CvSosmed::where('candidate_id', $candidate->id)->first();

        $array['profile_detail'] = $userProfileDetail ?? new stdClass();
        $array['domicile'] = $userAddress ?? new stdClass();
        $array['sosmed'] = $userSosmed ?? new stdClass();
        $collectionArray = collect($array);
        return $this->showOne($collectionArray);
    }


    public function cvDetailByDefault()
    {
        $candidate = Candidate::where('user_id', auth()->id())->first();
        $id = $candidate->id;
        $education = CvEducation::where('candidate_id', $id)
            ->orderBy('started_at', 'DESC')
            ->orderByRaw("CASE WHEN ended_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('ended_at', 'DESC')
            ->get();
        $data['educations'] = $education;

        $experience = CvExperience::where('candidate_id', $id)
            ->orderBy('started_at', 'DESC')
            ->orderByRaw("CASE WHEN ended_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('ended_at', 'DESC')
            ->get();
        $data['experiences'] = $experience;

        $certifications = CvCertification::where('candidate_id', $id)
            ->orderBy('issued_at', 'DESC')
            ->orderByRaw("CASE WHEN expired_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('expired_at', 'DESC')
            ->get();
        $data['certifications'] = $certifications;

        $specialities = CvSpeciality::where('candidate_id', $id)->get();
        $data['specialities'] = $specialities;

        $hobbies = CvHobby::where('candidate_id', $id)->get();
        $data['hobbies'] = $hobbies;

        $data = (object)$data;

        return $this->showOne(collect($data));
    }

    public function getCandidateCv($id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidateId = $candidate->id;

        $education = CvEducation::where('candidate_id', $candidateId)
            ->orderBy('started_at', 'DESC')
            ->orderByRaw("CASE WHEN ended_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('ended_at', 'DESC')
            ->get();
        $data['educations'] = $education;

        $experience = CvExperience::where('candidate_id', $candidateId)
            ->orderBy('started_at', 'DESC')
            ->orderByRaw("CASE WHEN ended_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('ended_at', 'DESC')
            ->get();
        $data['experiences'] = $experience;

        $certifications = CvCertification::where('candidate_id', $candidateId)
            ->orderBy('issued_at', 'DESC')
            ->orderByRaw("CASE WHEN expired_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('expired_at', 'DESC')
            ->get();
        $data['certifications'] = $certifications;

        $specialities = CvSpeciality::where('candidate_id', $candidateId)->get();
        $data['specialities'] = $specialities;

        $hobbies = CvHobby::where('candidate_id', $candidateId)->get();
        $data['hobbies'] = $hobbies;

        return $this->showOne($data);
    }

    public function status()
    {
        $candidate = Candidate::where('user_id', auth()->id())->first();
        $status = $this->getStatus($candidate->id);

        return $status;
    }

    public function getStatus($id)
    {
        $candidate = Candidate::where('id', $id)->first();
        $userProfileDetail = CvProfileDetail::where('candidate_id', $id)->first();
        $education = CvEducation::where('candidate_id', $id)->first();
        $document = CvDocument::where('candidate_id', $id)->first();
        $expectedSalaries = CvExpectedJob::where('candidate_id', $id)->first();

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

        if (!$document || !$document->identityCard || !$document->frontSelfie || !$document->rightSelfie || !$document->leftSelfie) {
            $data['is_document_completed'] = false;
        }
        $result['basic_profile'] = [
            'first_name' => $candidate->name ?? null,
        ];

        $employee = Employee::where('candidate_id', $id)->first();
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|min:3',
            'last_name' => 'nullable|string',
            'reference' => 'nullable|string',
            'file' => 'file|required',
            'expected_position' => 'required'
        ]);

        $candidate = Candidate::where('user_id', auth()->id())->first();
        if ($candidate) {
            return $this->errorResponse('This user already being a candidate', 409, 40900);
        }

        $fullName = $request->first_name;
        if (!empty($request->last_name)) {
            $fullName = $request->first_name . ' ' . $request->last_name;
        }
        $reference = $request->reference;

        $image = $request->file;
        $img = Image::make($image)->encode($image->extension(), 70);
        $fileName = time() . '.' . $image->extension();

        $user = User::find(auth()->id());
        $phoneNumber = substr($user->telpon, 1);
        $candidate = Candidate::where('phone_number', $phoneNumber)->first();
        DB::transaction(function () use ($request, $candidate, $fileName, $fullName, $reference, $img) {
            if ($candidate) {
                $candidate->update([
                    'user_id' => auth()->id(),
                    'name' => $fullName,
                    'reference' => $reference,
                    'profile_picture' => $fileName,
                ]);
            } else {
                $candidate = Candidate::create([
                    'user_id' => auth()->id(),
                    'name' => $fullName,
                    'country_code' => '62',
                    'phone_number' => substr(auth()->user()->telpon, 1),
                    'registered_at' => now(),
                    'status' => 3,
                    'profile_picture' => $fileName,
                ]);
            }
            $data['candidate_id'] = $candidate->id;
            Storage::disk('public')->put('images/profile_picture/' . $fileName, $img);

            $expectedPosition = json_decode($request->expected_position);
            $candidatePosition = CandidatePosition::where('id', $expectedPosition->id)
                ->orWhere('name', $expectedPosition->name)
                ->first();
            if (!$candidatePosition) {
                $candidatePosition = CandidatePosition::create(['name' => $expectedPosition->name]);
            }

            CvExpectedJob::create([
                'candidate_id' => $candidate->id,
                'expected_position' => $candidatePosition->id,
            ]);
        });

        return $this->showOne($candidate);
    }

    public function createCandidate($user, $request)
    {

        $candidate = Candidate::where('phone_number', substr($user->telpon, 1))->first();
        if (!$candidate) {
            $candidate = new Candidate();
        }
        $candidate->candidate_id = $user->id_kustomer;
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
        $candidate = Candidate::where('user_id', $user->id_kustomer)->firstOrFail();
        $employee = Employee::where('candidate_id', $candidate->id)->firstOrFail();

        $data = [
            'candidate' => $employee->candidate,
            'profile' => $employee->profileDetail,
            'roles' => $employee->getRoleNames(),
            'permissions' => $employee->getAllPermissions()->pluck('name')
        ];
        return $this->showOne(collect($data));
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
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();

        $request->validate([
            #Profile Detail
            'profile_detail.birth_location' => 'required|string',
            'profile_detail.birth_date' => 'required|date',
            'profile_detail.gender' => 'required|string',
            'profile_detail.identity_number' => 'required|min:5', // TODO: should be integer
            'profile_detail.marriage_status_id' => 'required|exists:marriage_statuses,id',
            'profile_detail.religion_id' => 'required|exists:religions,id',

            #Address
            'domicile.province_id' => 'integer|required',
            'domicile.city_id' => 'integer|required',
            'domicile.subdistrict_id' => 'integer|required',
            'domicile.village_id' => 'integer|nullable',
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

        $requestDomicile = $json['domicile'];
        $requestDomicile['candidate_id'] = $candidate->id;
        $requestDomicile['country_id'] = 62;

        $requestSosmed = $json['sosmed'];
        $requestSosmed['candidate_id'] = $candidate->id;
        $array = [];
        try {
            $res = DB::transaction(function () use (
                $candidate,
                $request,
                $requestProfile,
                $requestDomicile,
                $requestSosmed,
            ) {
                // TODO: Debugging
                $userProfileDetail = CvProfileDetail::where('candidate_id', $candidate->id)->first();
                $userProfileDetail->fill($requestProfile);
                if ($userProfileDetail->isDirty()) {
                    $userProfileDetail->update($requestProfile);
                }

                $userDomicile = CvDomicile::where('candidate_id', $candidate->id)->first();
                if ($userDomicile) {
                    $userDomicile->fill($requestDomicile);

                    $validation = self::validateAddress(
                        $requestDomicile['country_id'],
                        $requestDomicile['province_id'],
                        $requestDomicile['city_id'],
                        $requestDomicile['subdistrict_id'],
                        $requestDomicile['village_id'],
                        $request->bearerToken(),
                    );
                    if ($validation['status'] != 200) {
                        return $this->errorResponse(collect($validation['message']), $validation['status'], $validation['message']['code']);
                    }
                    $userDomicile->save();
                } else {
                    $userDomicile = CvDomicile::create($requestDomicile);
                }

                $userSosmed = CvSosmed::where('candidate_id', $candidate->id)->first();
                if ($userSosmed) {
                    $userSosmed->fill($requestSosmed);
                    if ($userSosmed->isDirty()) {
                        $userSosmed->update($requestSosmed);
                    }
                } else {
                    $userSosmed = CvSosmed::create($requestSosmed);
                }
                $array['profile_detail'] = $userProfileDetail;
                $array['domicile'] = $userDomicile;
                $array['sosmed'] = $userSosmed;
            });
            return $this->showOne($array);

            // return $res->getData();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500, 50001);
        }
    }
}
