<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeOneTimeShift;
use App\Models\EmployeeRecurringShift;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class EmployeeShiftController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = $request->name;
        $date = $request->date;
        $companyId = $request->company_id;
        $dateTimestamp = strtotime($date);
        $day = date('w', $dateTimestamp);

        $employeeOneTimeShifts = EmployeeOneTimeShift::whereDate('date', $date)
            ->whereHas('employee', function ($employeeQuery) use ($name, $companyId) {
                $employeeQuery->whereHas('profileDetail', function ($profileDetailQuery) use ($name) {
                    $profileDetailQuery->where('first_name', 'LIKE', '%' . $name . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $name . '%');
                });
                // $employeeQuery->whereHas('position', function ($positionQuery) use ($companyId) {
                //     $positionQuery->when($companyId, function ($filteredPositionQuery, $companyId) {
                //         $filteredPositionQuery->where('company_id', $companyId)->dd();
                //     });
                // });
            })
            ->with([
                'shift',
                'employee.position',
                'employee.profileDetail',
            ])
            ->get();

        $employeeRecurringShifts = EmployeeRecurringShift::whereDate('day', $day)
            ->whereHas('employee', function ($employeeQuery) use ($name) {
                $employeeQuery->whereHas('profileDetail', function ($profileDetailQuery) use ($name) {
                    $profileDetailQuery->where('first_name', 'LIKE', '%' . $name . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $name . '%');
                });
            })
            ->with([
                'shift',
                'employee.position',
                'employee.profileDetail',
            ])
            ->get();

        return $this->showOne([
            'one_time_shifts' => $employeeOneTimeShifts,
            'recurring_shifts' => $employeeRecurringShifts,
        ]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
