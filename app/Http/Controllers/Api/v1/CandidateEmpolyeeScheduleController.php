<?php

namespace App\Http\Controllers;

use App\Models\CandidateEmployees;
use App\Models\CandidateEmpolyeeSchedule;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class CandidateEmpolyeeScheduleController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        // $posistion = EmployeeDetails::where('user_id',$user->id_kustomer)->first();
        // if(!$posistion){
        //     return $this->errorResponse('user tidak di temukan',404,40401);
        // }
        $request->validate([
            'employee_candidate_id' => 'integer|required',
            'date_time' => 'date|nullable',
            'interview_by' => 'integer|required',
            'result_id' => 'integer|nullable',
            'note' => 'longtext|nullable',
        ]);
        $candidate = CandidateEmployees::where('user_id', $user->id_kustomer)->first();
        if (!$candidate) {
            return $this->errorResponse('Candidate not found', 404, 40401);
        }

        // switch ($candidate->status) {
        //     case (CandidateEmployees::ins):
        //         $candidate->status = CandidateEmployees;
        //     case (CandidateEmployees):
        //         $candidate->status = CandidateEmployees;
        //     case (CandidateEmployees):
        //         $candidate->status = CandidateEmployees;
        //     default:
        //         $candidate = 0;
        // }

        $candidateEmpolyeeSchedule = CandidateEmpolyeeSchedule::create($request->all());

        return $this->showOne($candidateEmpolyeeSchedule);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CandidateEmpolyeeSchedule  $candidateEmpolyeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(CandidateEmpolyeeSchedule $candidateEmpolyeeSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CandidateEmpolyeeSchedule  $candidateEmpolyeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(CandidateEmpolyeeSchedule $candidateEmpolyeeSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CandidateEmpolyeeSchedule  $candidateEmpolyeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CandidateEmpolyeeSchedule $candidateEmpolyeeSchedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CandidateEmpolyeeSchedule  $candidateEmpolyeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(CandidateEmpolyeeSchedule $candidateEmpolyeeSchedule)
    {
        //
    }
}
