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
