<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Candidate;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeDetail;
use App\Http\Controllers\Controller;
use App\Models\CandidateLogEmployee;
use App\Http\Controllers\Api\v1\CvProfileDetailController;
use App\Models\CandidatePosition;
use App\Models\CandidateInterviewSchedule;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;


class CandidateController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        // Collection::macro('test',function(){
        //     return 'test';
        // });

        $user = auth()->user();

        // $posistion = EmployeeDetails::where('user_id',$user->id_kustomer)->first();
        // if(!$posistion){
        //     return $this->errorResponse('user tidak di temukan',404,40401);
        // };
        $request->validate([
            'page' => 'required|numeric|gt:0',
            'page_size' => 'required|numeric|gt:0',
            'name' => 'nullable|string',
            'status' => 'nullable|integer',
            'country_id' => 'nullable',
            'province_id' => 'nullable',
            'city_id' => 'nullable',
            'position_id' => 'nullable|exists:App\Models\CandidatePosition,id',
            'order_by' => [
                'nullable',
                Rule::in(['DESC', 'ASC']),
            ],
        ]);
        $name = $request->name;
        $status = $request->status;
        $countryId = $request->country_id;
        $provinceId = $request->province_id;
        $cityId = $request->city_id;
        $position = $request->position_id;
        $orderBy = $request->order_by == null
            ? 'DESC'
            : $request->order_by;

        $candidates = Candidate::where(function ($query) use ($name, $status,  $countryId, $provinceId, $cityId, $position) {
            if ($name != null) {
                $query->where('name', 'LIKE', '%' . $name . '%');
            }

            if (($countryId != null) || ($provinceId != null) || ($cityId != null)) {
                $query->whereHas('domicile', function ($secondQuery) use ($countryId, $provinceId, $cityId) {
                    if ($countryId != null) {
                        $secondQuery->where('country_id', $countryId);
                    }
                    if ($provinceId != null) {
                        $secondQuery->where('province_id', $provinceId);
                    }
                    if ($cityId != null) {
                        $secondQuery->where('city_id', $cityId);
                    }
                    if ($cityId != null) {
                        $secondQuery->where('city_id', $cityId);
                    }
                    if ($cityId != null) {
                        $secondQuery->where('city_id', $cityId);
                    }
                });
            }

            if ($position != null) {
                $query->whereHas('job', function ($secondQuery) use ($position) {
                    $secondQuery->where('expected_position', $position);
                });
            }
            if ($status != null) {
                if ($status == Candidate::ReadyToInterview) {
                    $query->where('status', 3);
                } else {
                    $query->where('status', $status);
                }
            }
        })->orderBy('updated_at', $orderBy)
            ->paginate(
                $perpage = $request->page_size,
                $columns =  ['*'],
                $pageName = 'page',
                $pageBody = $request->page
            );
        $data = [];
        foreach ($candidates as $candidate) {
            if ($status == Candidate::ReadyToInterview) {
                $candidateController = new CvProfileDetailController;

                $status = $candidateController->getStatus($candidate->user_id);
                $status = $status->original;
                $status = $status['data']['completeness_status'];
                if (
                    $status['is_profile_completed'] == true &&
                    $status['is_job_completed'] == true &&
                    $status['is_document_completed']  == true &&
                    $status['is_cv_completed'] == true
                ) {
                    $data[] = $candidate->listDefaultCandidate();
                }
            } else {
                $data[] = $candidate->listDefaultCandidate();
            }
        }
        return $this->showPaginate('candidates', collect($data), collect($candidates));
    }

    public function indexDetail(Request $request, $id)
    {
        $candidate = Candidate::where('id', $id)->firstOrFail();
        return $this->showOne($candidate);
    }

    public function addCandidateToBlast(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'string|nullable',
            'country_code' => 'integer|required',
            'phone_number' => 'integer|required',
        ]);

        $posistion = EmployeeDetail::where('user_id', $user->id_kustomer)->first();
        if (!$posistion) {
            return $this->errorResponse('Tidak bisa melanjutkan karena bukan Empolyee', 409, 40901);
        }

        $candidateHasSuggestOrNot = Candidate::where('phone_number', $request->phone_number)->first();
        if ($candidateHasSuggestOrNot) {
            $candidateHasSuggestOrNot->many_request += 1;
            $candidateHasSuggestOrNot->save();
            return $this->errorResponse('Candidate has been suggested', 409, 40902);
        }

        $data = $request->all();
        $data['status'] = Candidate::BLASTING;
        $data['suggest_by'] = $posistion->id;

        $candidates = Candidate::create($data);

        return $this->showOne($candidates);
    }


    public function getPosition(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            // 'company_id' => 'integer|required',
            'page' => 'required|numeric|gt:0',
            'page_size' => 'required|numeric|gt:0'
        ]);

        // dump($user);
        $result = [];
        $positions = CandidatePosition::orderBy('name', 'desc')
            ->paginate(
                $perpage = $request->page_size,
                $columns =  ['*'],
                $pageName = 'page',
                $pageBody = $request->page
            );
        // dump($positions);
        foreach ($positions as $position) {
            // dump($position);
            $result[] = [
                'id' => $position->id,
                'name' => $position->name,
                'statistics' => $this->getCount($position),
            ];
        }

        // return $this->showAll(collect($result));
        return $this->showPaginate('positions', collect($result), collect($positions));
    }

    public function getCount($position)
    {
        $data['total'] = collect($position->candidates)->count();
        $data['interview'] = collect($position->candidates)->filter(function ($item) {
            return $item->label() == null;
        })->count();
        $data['bad'] = collect($position->candidates)->filter(function ($item) {
            if ($item->label()) {
                return $item->label()->id == Candidate::RESULT_BAD;
            }
        })->count();
        $data['hold'] = collect($position->candidates)->filter(function ($item) {
            if ($item->label()) {
                return $item->label()->id == Candidate::RESULT_HOLD;
            }
        })->count();
        $data['recommended'] = collect($position->candidates)->filter(function ($item) {
            if ($item->label()) {
                return $item->label()->id == Candidate::RESULT_RECOMMENDED;
            }
        })->count();
        $data['accepted'] = collect($position->candidates)->filter(function ($item) {
            return $item->status == Candidate::ACCEPTED;
        })->count();

        return $data;
    }

    public function updateStatus(Request $request, $id)
    {
        $user = auth()->user();
        $request->validate([
            'status' => 'integer|required',
        ]);

        $candidate = Candidate::where('id', $id)->firstOrFail();

        if ($candidate->user_id == $user->id_kustomer) {
            return $this->errorResponse('Candidate cannot update his own status', 422, 42204);
        }

        if ($request->status < Candidate::INTERVIEW) {
            return $this->errorResponse('candidate cannot change with that status', 422, 42202);
        }

        if (!$candidate->label() && count($candidate->schedules)) {
            return $this->errorResponse('Candidate has not finish old schedule yet', 422, 42203);
        }

        if ($request->status == Candidate::INTERVIEW) {
            $request->validate([
                'interviewed_at' => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
                'interviewed_by' => 'integer|exists:employee_details,id',
            ]);

            $candidateController = new CvProfileDetailController;

            $status = $candidateController->getStatus($candidate->user_id);
            $status = $status->original;
            $status = $status['data']['completeness_status'];
            if (
                $candidate->status != Candidate::INTERVIEW &&
                $status['is_profile_completed'] == false &&
                $status['is_job_completed'] == false &&
                $status['is_document_completed']  == false &&
                $status['is_cv_completed'] == false
            ) {
                return $this->errorResponse('this Candidate cannot going interview', 422, 42201);
            }

            $data = $request->all();
            $data['interviewed_at'] = date('Y-m-d H:i:s', strtotime($data['interview_at']));
            $data['candidate_id'] = $id;

            $candidateEmpolyeeSchedule = CandidateInterviewSchedule::create($data);
        }

        $candidate->status = $request->status;
        $candidate->save();
        return $this->showOne($candidate);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function show(Candidate $candidate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function edit(Candidate $candidate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Candidate  $Candidate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Candidate $candidate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Candidate $candidate)
    {
        //
    }
}
