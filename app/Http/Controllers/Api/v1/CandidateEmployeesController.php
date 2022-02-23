<?php

namespace App\Http\Controllers\api\v1;

use App\Models\CandidateEmployees;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeDetails;
use App\Http\Controllers\Controller;
use App\Models\User;

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
        ]);

        switch ($request->filterBy) {
            case ('blast'):
                $candidates = CandidateEmployees::where('status', 1)->get();
                break;
            default:
                $candidates = CandidateEmployees::all();
        }
        // $candidates = CandidateEmployees::where('status', 1)->get();
        return $this->showOne($candidates);
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

    public function updateStatus(Request $request)
    {
        $request->validate([
            'name' => 'string|required',
            'country_code' => 'string|required',
            'phone_number' => 'integer|required',
        ]);

        $candidateEmployees = CandidateEmployees::where('phone_number', $request->phone_number)->where('country_code', $request->country_code)->first();
        if (!$candidateEmployees) {
            return $this->errorResponse('Candidate Not Found', 404, 40401);
        }

        $candidateEmployees->phone_number = $request->phone_number;
        $candidateEmployees->country_code = $request->country_code;
        if (!$candidateEmployees->name) {
            $candidateEmployees->name = $request->name;
        }
        $candidateEmployees->status = CandidateEmployees::REGISTEREDKADA;
        $candidateEmployees->save();
    }

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
