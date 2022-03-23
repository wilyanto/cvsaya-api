<?php

namespace App\Http\Controllers\api\v1;

use App\Models\CandidateEmployee;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeDetail;
use App\Http\Controllers\Controller;
use App\Models\CandidateLogEmployee;
use App\Http\Controllers\Api\v1\CvProfileDetailController;
use App\Models\CandidatePosition;
use App\Models\CandidateEmployeeSchedule;

class CandidateEmployeeController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $user = auth()->user();

        // $posistion = EmployeeDetails::where('user_id',$user->id_kustomer)->first();
        // if(!$posistion){
        //     return $this->errorResponse('user tidak di temukan',404,40401);
        // };
        if ($request->input()) {
            $request->validate([
                'page' => 'required|numeric|gt:0',
                'page_size' => 'required|numeric|gt:0',
                'name' => 'nullable|string',
                'status' => 'nullable|integer',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'country_id' => 'nullable',
                'province_id' => 'nullable|string',
                'city_id' => 'nullable|string',
                'district_id' => 'nullable|string',
                'village_id' => 'nullable|string',
            ]);
            $name = $request->name;
            $status = $request->status;
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $country_id = $request->country_id;
            $province_id = $request->province_id;
            $city_id = $request->city_id;
            $district_id = $request->district_id;
            $village_id = $request->village_id;

            $candidates = CandidateEmployee::where(function ($query) use ($name, $status, $start_date, $end_date, $country_id, $province_id, $city_id, $district_id, $village_id) {
                if ($name != null) {
                    $query->where('name', $name);
                }

                if ($start_date != null) {
                    $query->where('start_date', $start_date);
                }

                if ($end_date != null) {
                    $query->where('end_date', $end_date);
                }
                if (($country_id != null) || ($province_id != null) || ($city_id != null) || ($district_id != null) || ($village_id != null)) {
                    $query->whereHas('address', function ($secondQuery) use ($country_id, $province_id, $city_id, $district_id, $village_id) {
                        if ($country_id != null) {
                            $secondQuery->where('country_id', $country_id);
                        }
                        if ($province_id != null) {
                            $secondQuery->where('province_id', $province_id);
                        }
                        if ($city_id != null) {
                            $secondQuery->where('city_id', $city_id);
                        }
                        if ($district_id != null) {
                            $secondQuery->where('district_id', $district_id);
                        }
                        if ($village_id != null) {
                            $secondQuery->where('village_id', $village_id);
                        }
                    });
                }
                if ($status != null) {
                    if ($status == CandidateEmployee::ReadyToInterview) {
                        $query->where('status', 3);
                    } else {
                        $query->where('status', $status);
                    }
                }
            })->paginate(
                $perpage = $request->page_size,
                $columns =  ['*'],
                $pageName = 'page',
                $pageBody = $request->page
            );
            if ($status == CandidateEmployee::ReadyToInterview) {
                $data = [];
                foreach ($candidates as $candidate) {
                    $candidateController = new CvProfileDetailController;

                    $status = $candidateController->getStatus($candidate->user_id);
                    $status = $status->original;
                    $status = $status['data']['completeness_status'];
                    if (
                        $status['is_profile_completed'] = true &&
                        $status['is_job_completed'] = true &&
                        $status['is_document_completed']  = true &&
                        $status['is_cv_completed'] = true
                    ) {
                        $data[] = $candidate;
                    }
                }

                return $this->showPaginate('Candidate', collect($data), collect($candidates));
            }
        } else {
            $candidates = CandidateEmployee::all()->paginate(
                $perpage = $request->page_size,
                $columns =  ['*'],
                $pageName = 'page',
                $pageBody = $request->page
            );;
        }
        return $this->showPaginate('Candidate', collect($candidates->values()), collect($candidates));
    }

    public function indexDetail(Request $request, $id)
    {
        $candidate = CandidateEmployee::where('id', $id)->firstOrFail();
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

        $candidateHasSuggestOrNot = CandidateEmployee::where('phone_number', $request->phone_number)->first();
        if ($candidateHasSuggestOrNot) {
            $candidateHasSuggestOrNot->many_request += 1;
            $candidateHasSuggestOrNot->save();
            return $this->errorResponse('Candidate has been suggested', 409, 40902);
        }

        $data = $request->all();
        $data['status'] = CandidateEmployee::BLASTING;
        $data['suggest_by'] = $posistion->id;

        $candidates = CandidateEmployee::create($data);

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
                return $item->label()->id == CandidateEmployee::RESULT_BAD;
            }
        })->count();
        $data['hold'] = collect($position->candidates)->filter(function ($item) {
            if ($item->label()) {
                return $item->label()->id == CandidateEmployee::RESULT_HOLD;
            }
        })->count();
        $data['recommended'] = collect($position->candidates)->filter(function ($item) {
            if ($item->label()) {
                return $item->label()->id == CandidateEmployee::RESULT_RECOMMENDED;
            }
        })->count();
        $data['accepted'] = collect($position->candidates)->filter(function ($item) {
            return $item->status == CandidateEmployee::ACCEPTED;
        })->count();

        return $data;
    }


    public function updateStatus(Request $request, $id)
    {
        $user = auth()->user();
        $request->validate([
            'status' => 'integer|required',
        ]);

        $candidateEmployee = CandidateEmployee::where('id', $id)->firstOrFail();

        if ($candidateEmployee->user_id == $user->id_kustomer) {
            return $this->errorResponse('Candidate cannot update his own status', 422, 42204);
        }

        if ($request->status < CandidateEmployee::INTERVIEW) {
            return $this->errorResponse('candidate cannot change with that status', 422, 42202);
        }

        if (!$candidateEmployee->label() && count($candidateEmployee->schedules)) {
            return $this->errorResponse('Candidate has not finish old schedule yet', 422, 42203);
        }

        if ($request->status == CandidateEmployee::INTERVIEW) {
            $request->validate([
                'date_time' => 'date|nullable',
                'interview_by' => 'integer|exists:employee_details,id',
            ]);

            $candidateController = new CvProfileDetailController;

            $status = $candidateController->getStatus($candidateEmployee->user_id);
            $status = $status->original;
            $status = $status['data']['completeness_status'];
            if (
                $candidateEmployee->status != CandidateEmployee::INTERVIEW &&
                $status['is_profile_completed'] == false &&
                $status['is_job_completed'] == false &&
                $status['is_document_completed']  == false &&
                $status['is_cv_completed'] == false
            ) {
                return $this->errorResponse('this Candidate cannot going interview', 422, 42201);
            }

            $data = $request->all();
            $data['employee_candidate_id'] = $id;

            $candidateEmpolyeeSchedule = CandidateEmployeeSchedule::create($data);
        }

        $candidateEmployee->status = $request->status;
        $candidateEmployee->save();
        return $this->showOne($candidateEmployee);
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
     * @param  \App\Models\CandidateEmployees  $candidateEmployees
     * @return \Illuminate\Http\Response
     */
    public function show(CandidateEmployee $candidateEmployees)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CandidateEmployees  $candidateEmployees
     * @return \Illuminate\Http\Response
     */
    public function edit(CandidateEmployee $candidateEmployees)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CandidateEmployees  $candidateEmployees
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CandidateEmployee $candidateEmployees)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CandidateEmployees  $candidateEmployees
     * @return \Illuminate\Http\Response
     */
    public function destroy(CandidateEmployee $candidateEmployees)
    {
        //
    }
}
