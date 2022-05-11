<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeOneTimeShift;
use App\Http\Requests\UpdateEmployeeOneTimeShift;
use App\Models\EmployeeOneTimeShift;
use App\Traits\ApiResponser;

class EmployeeOneTimeShiftController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employeeOneTimeShifts = EmployeeOneTimeShift::all();

        return $this->showAll($employeeOneTimeShifts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeOneTimeShift $request)
    {
        $employeeOneTimeShift = EmployeeOneTimeShift::create($request->all());

        return $this->showOne($employeeOneTimeShift);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeOneTimeShift  $employeeOneTimeShift
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeOneTimeShift $employeeOneTimeShift)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeOneTimeShift  $employeeOneTimeShift
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeOneTimeShift $request, $id)
    {
        $updatedOneTimeShift = EmployeeOneTimeShift::findOrFail($id)
            ->update($request->all());

        return $this->showOne($updatedOneTimeShift);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeOneTimeShift  $employeeOneTimeShift
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        EmployeeOneTimeShift::findOrFail($id)->delete();

        return $this->showOne(null, 204);
    }
}