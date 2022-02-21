<?php

namespace App\Http\Controllers\api\v1;

use App\Models\CandidateEmployees;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeDetails;
use App\Http\Controllers\Controller;

class CandidateEmployeesController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCandidateBlast()
    {
        $user = auth()->user();

        // $posistion = EmployeeDetails::where('user_id',$user->id_kustomer)->first();
        // if(!$posistion){
        //     return $this->errorResponse('user tidak di temukan',404,40401);
        // }
        $candidates = CandidateEmployees::where('status',1)->get();

        return $this->showOne($candidates);
    }

    public function indexCandidateCv()
    {
        $user = auth()->user();

        $posistion = EmployeeDetails::where('user_id',$user->id_kustomer)->first();
        if(!$posistion){
            return $this->errorResponse('user tidak di temukan',404,40401);
        }
        $candidates = CandidateEmployees::whereIn('status',[2,3])->get();

        return $this->showOne($candidates);
    }

    public function indexCandidateOnInterview()
    {
        $user = auth()->user();

        $posistion = EmployeeDetails::where('user_id',$user->id_kustomer)->first();
        if(!$posistion){
            return $this->errorResponse('user tidak di temukan',404,40401);
        }
        $candidates = CandidateEmployees::whereIn('status',[4,5])->get();

        return $this->showOne($candidates);
    }

    public function indexCandidate(){
        $user = auth()->user();

        $posistion = EmployeeDetails::where('user_id',$user->id_kustomer)->first();
        if(!$posistion){
            return $this->errorResponse('user tidak di temukan',404,40401);
        }
        $candidates = CandidateEmployees::whereIn('status','!=',[1,6,7])->get();

        return $this->showOne($candidates);
    }


    public function indexNotCandidate()
    {
        $user = auth()->user();

        $posistion = EmployeeDetails::where('user_id',$user->id_kustomer)->first();
        if(!$posistion){
            return $this->errorResponse('user tidak di temukan',404,40401);
        }
        $candidates = CandidateEmployees::whereIn('status',[6,7])->get();

        return $this->showOne($candidates);
    }


    public function addCandidateToBlast(Request $request){
        $user = auth()->user();
        $request->validate([
            'name' => 'string|required',
            'country_code' => 'string|required',
            'phone_number' => 'integer|required',
        ]);

        $posistion = EmployeeDetails::where('user_id',$user->id_kustomer)->first();
        if(!$posistion){
            return $this->errorResponse('user tidak di temukan',404,40401);
        }

        $data = $request->all();
        $data['status'] = 1;
        $data['suggest_by'] =$posistion->id;

        $candidates = CandidateEmployees::create($data);

        return $this->showOne($candidates);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
