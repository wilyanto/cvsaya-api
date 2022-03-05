<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\EmployeeDetail;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Level;
use App\Models\Position;

class EmployeeDetailsController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $user = auth()->user();
        // // var_dump($user);
        // // $employeeDetails = EmployeeDetails::where('user_id',$user->id_kustomer)->first();
        // // if(!$employeeDetails){
        // //     return $this->errorResponse('id not found',404,40401);
        // // }
        // // $request->validate([

        // // ]);
        // $request->validate([
        //     'company_id' => 'integer|nullable',
        //     'position_id' => 'integer|nullable',
        //     'department_id' => 'integer|nullable',
        //     'level_id' => 'integer|nullable',
        //     'name' => 'integer|nullable',
        // ]);
        // $request->company_id = 1;
        // // $request->position_id = 2;
        // $request->department_id = 1;
        // $request->level_id = 1;
        // // dump($request->company_id);
        // $value = null;
        // if ($request->company_id) {
        //     $department = Departments::where('id','like' ,'%'.$request->department_id.'%')->where('company_id','like' ,'%'.$request->company_id.'%')->first();
        //     if ($department) {
        //         $department = $department->id;
        //     }
        //     $level = Level::where('id','like' ,'%'.$request->level_id.'%')->where('company_id','like' ,'%'.$request->company_id.'%')->first();
        //     if ($level) {
        //         $level = $level->id;
        //     }
        // } else {
        //     $department = Departments::where('id','like' ,'%'.$request->department_id.'%')->first();
        //     if ($department) {
        //         $department = $department->id;
        //     }

        //     $level = Level::where('id','like' ,'%'.$request->level_id.'%')->first();
        //     if ($level) {
        //         $level = $level->id;
        //     }
        // }
        // $position = Positions::where('id', 'like', '%' . $request->position_id . '%')->Where('department_id', 'like', '%' . $department . '%')->Where('level_id', 'like', '%' . $level . '%')->pluck('id');

        // // dump($department);
        // // dump($level);
        // // dd($position);
        // $employee = EmployeeDetails::whereIn('position_id', $position)->Where('name', 'like', '%' . $request->name . '%')->get();
        // return $this->showAll($employee);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeDetails  $employeeDetails
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeDetail $employeeDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeDetails  $employeeDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeDetail $employeeDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeDetails  $employeeDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeDetail $employeeDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeDetails  $employeeDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeDetail $employeeDetails)
    {
        //
    }
}
