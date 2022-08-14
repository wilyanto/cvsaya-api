<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeOneTimeShift;
use App\Http\Requests\UpdateEmployeeOneTimeShift;
use App\Http\Resources\OneTimeShiftResource;
use App\Models\Employee;
use App\Models\EmployeeOneTimeShift;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class EmployeeOneTimeShiftController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employeeId = $request->employee_id;
        $date = $request->date;
        $employeeOneTimeShifts = EmployeeOneTimeShift::with('shift', 'employee')
            ->when($date, function ($query, $date) {
                $query->whereDate('date', $date);
            })
            ->when($employeeId, function ($query, $employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->get();

        return $this->showAll(collect(OneTimeShiftResource::collection($employeeOneTimeShifts)));
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
        return $this->showOne(new OneTimeShiftResource($employeeOneTimeShift));
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
        return $this->showOne(new OneTimeShiftResource($updatedOneTimeShift));
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
