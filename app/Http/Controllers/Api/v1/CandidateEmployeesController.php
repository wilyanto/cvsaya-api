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
use App\Models\CvExpectedSalaries;
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
        // }
        $request->validate([
            'filterBy' => 'string|nullable',
            'position_id' => 'integer|nullable',
            'city_id' => 'integer|nullable',
        ]);

        $position = [];
        $position = CvExpectedSalaries::where('expected_position', 'like', '%' . $request->posistion_id . '%')->pluck('user_id');

        $address = [];
        $address = CvAddress::where('city_id', 'like', '%' . $request->city_id . '%')->pluck('id');

        if ($request->input()) {
            $candidates = CandidateEmployees::where('status', 'like', '%' . $request->filterBy . '%')->whereIn('user_id', $address)->whereIn('user_id', $position)->get();
        } else {
            $candidates = CandidateEmployees::all();
        }
        return $this->showAll($candidates);
    }

    public function indexDetail(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'user_id' => 'string|required',
        ]);

        $candidate = CandidateLogEmpolyees::where('user_id', $request->id)->get();
        return $this->all($candidate);
    }



    public function addCandidateToBlast(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'string|nullable',
            'country_code' => 'string|required',
            'phone_number' => 'integer|required',
        ]);

        $posistion = EmployeeDetails::where('user_id', $user->id_kustomer)->first();
        if (!$posistion) {
            return $this->errorResponse('User tidak di temukan', 404, 40401);
        }
        $candidateHasSuggestOrNot = CandidateEmployees::where('phone_number', $request->phone_number)->first();
        if (!$candidateHasSuggestOrNot) {
            $candidateHasSuggestOrNot->many_request += 1;
            $candidateHasSuggestOrNot->save();
            return $this->errorResponse('Candidate has been suggested', 409, 40901);
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
        $positions = Positions::where('company_id', $user->ID_perusahaan)->orderBy('name', 'ASC')
            // ->get()
            ->paginate(
                $perpage = $request->page_size,
                $columns =  ['*'],
                $pageName = 'page',
                $pageBody = $request->page
            );
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
            return $item->status <= CandidateEmployees::DECLINE;
        })->count();
        $data['standby'] = collect($position->candidate)->filter(function ($item) {
            return $item->status <= CandidateEmployees::STANDBY;
        })->count();
        $data['pass'] = collect($position->candidate)->filter(function ($item) {
            return $item->status <= CandidateEmployees::PASS;
        })->count();
        $data['consider'] = collect($position->candidate)->filter(function ($item) {
            return $item->status <= CandidateEmployees::CONSIDER;
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
