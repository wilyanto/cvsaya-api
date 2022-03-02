<?php

namespace App\Http\Controllers\api\v1;

use App\Models\CandidateEmployees;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeDetails;
use App\Http\Controllers\Controller;
use App\Models\Positions;
use App\Models\CandidateLogEmpolyees;
use App\Models\User;
use App\Models\CandidatePositions;
use App\Models\CandidateResult;
use App\Models\CvExpectedPositions;
use App\Models\CvAddress;

class CandidateEmployeesController extends Controller
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

            // $request->validate([
            //     'filter_by' => 'string|required',
            //     'filter_result' => 'required',
            // ]);
            $candidates = CandidateEmployees::where(function ($query) use ($name, $status, $start_date, $end_date, $country_id, $province_id, $city_id, $district_id, $village_id) {
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
                            $secondQuery->where('country_id', $province_id);
                        }
                        if ($city_id != null) {
                            $secondQuery->where('country_id', $city_id);
                        }
                        if ($district_id != null) {
                            $secondQuery->where('country_id', $district_id);
                        }
                        if ($village_id != null) {
                            $secondQuery->where('country_id', $village_id);
                        }
                    });
                }
                if ($status != null) {
                    if ($status == CandidateEmployees::ReadyToInterview) {
                        $query->where('status', 3);
                    }else{
                        $query->where('status', $status);
                    }
                }
            })->get();
            // dump($candidates);
            if ($status == CandidateEmployees::ReadyToInterview) {
                $data = [];
                foreach($candidates as $candidate){
                    $candidateController = new CvProfileDetailController;

                    $status = $candidateController->getStatus($candidate->user_id);
                    $status = $status->original;
                    $status = $status['data']['is_all_form_filled'];
                    if($status != false){
                        $data[] = $candidate;
                    }
                }
                return $this->showAll(collect($data));
            }
        } else {
            $candidates = CandidateEmployees::all();
        }
        return $this->showAll($candidates);
    }

    public function indexDetail(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'candidate_id' => 'required',
        ]);

        $candidate = CandidateLogEmpolyees::where('candidate_id', $request->candidate_id)->get();
        return $this->showAll($candidate);
    }



    public function addCandidateToBlast(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'string|nullable',
            'country_code' => 'integer|required',
            'phone_number' => 'integer|required',
        ]);

        $posistion = EmployeeDetails::where('user_id', $user->id_kustomer)->first();
        if (!$posistion) {
            return $this->errorResponse('Tidak bisa melanjutkan karena bukan Empolyee', 409, 40901);
        }
        $candidateHasSuggestOrNot = CandidateEmployees::where('phone_number', $request->phone_number)->first();
        if ($candidateHasSuggestOrNot) {
            $candidateHasSuggestOrNot->many_request += 1;
            $candidateHasSuggestOrNot->save();
            return $this->errorResponse('Candidate has been suggested', 409, 40902);
        }

        $data = $request->all();
        $data['status'] = CandidateEmployees::BLASTING;
        $data['suggest_by'] = $posistion->id;

        $candidates = CandidateEmployees::create($data);

        return $this->showOne($candidates);
    }

    public function setDecline(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'user_id' => 'integer|required',
        ]);

        $candidate = CandidateEmployees::where('user_id', $request->user_id)->first();
        if (!$candidate) {
            return $this->errorResponse('Candidate Not Found', 404, 40401);
        }

        $candidate->delete();

        return $this->showOne($candidate);
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
        $positions = CandidatePositions::orderBy('name', 'ASC')
            // ->get()
            ->paginate(
                $perpage = $request->page_size,
                $columns =  ['*'],
                $pageName = 'page',
                $pageBody = $request->page
            );
        // dump($positions);
        foreach ($positions as $position) {
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
        // dump($position);
        $data['before_interview'] = collect($position->candidate)->filter(function ($item) {
            return $item->status <= CandidateEmployees::INTERVIEW;
        })->count();
        $data['interview'] = collect($position->candidate)->filter(function ($item) {
            return $item->status == CandidateEmployees::INTERVIEW;
        })->count();
        $data['decline'] = collect($position->candidate)->filter(function ($item) {
            return $item->status == CandidateEmployees::DECLINE;
        })->count();
        $data['standby'] = collect($position->candidate)->filter(function ($item) {
            return $item->status == CandidateEmployees::STANDBY;
        })->count();
        $data['pass'] = collect($position->candidate)->filter(function ($item) {
            return $item->status == CandidateEmployees::PASS;
        })->count();
        $data['consider'] = collect($position->candidate)->filter(function ($item) {
            return $item->status == CandidateEmployees::CONSIDER;
        })->count();

        return $data;
    }

    // public function updateStatus(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'string|required',
    //         'country_code' => 'string|required',
    //         'phone_number' => 'integer|required',
    //     ]);

    //     $candidateEmployees = CandidateEmployees::where('phone_number', $request->phone_number)->where('country_code', $request->country_code)->first();
    //     if (!$candidateEmployees) {
    //         return $this->errorResponse('Candidate Not Found', 404, 40401);
    //     }

    //     $candidateEmployees->phone_number = $request->phone_number;
    //     $candidateEmployees->country_code = $request->country_code;
    //     if (!$candidateEmployees->name) {
    //         $candidateEmployees->name = $request->name;
    //     }
    //     $candidateEmployees->status = CandidateEmployees::REGISTEREDKADA;
    //     $candidateEmployees->save();
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createBy()
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CandidateEmployees  $candidateEmployees
     * @return \Illuminate\Http\Response
     */
    public function show(CandidateEmployees $candidateEmployees)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CandidateEmployees  $candidateEmployees
     * @return \Illuminate\Http\Response
     */
    public function edit(CandidateEmployees $candidateEmployees)
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
    public function update(Request $request, CandidateEmployees $candidateEmployees)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CandidateEmployees  $candidateEmployees
     * @return \Illuminate\Http\Response
     */
    public function destroy(CandidateEmployees $candidateEmployees)
    {
        //
    }
}
