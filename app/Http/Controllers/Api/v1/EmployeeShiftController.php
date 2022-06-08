<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
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
        $positionId = $request->position_id;
        $dateTimestamp = strtotime($date);
        $day = date('w', $dateTimestamp);
        // TODO: can be improved using scopeWith
        $withRelationships = [
            'shift' => function ($query) {
                $query->select('id', 'name', 'clock_in', 'clock_out', 'break_started_at', 'break_ended_at', 'break_duration');
            },
            'employee' => function ($query) {
                $query->select('id', 'candidate_id', 'position_id');
            },
            'employee.position' => function ($query) {
                $query->select('id', 'name');
            },
            'employee.candidate' => function ($query) {
                $query->select('id', 'name');
            },
        ];

        $employeeOneTimeShifts = EmployeeOneTimeShift::whereDate('date', $date)
            ->whereHas('employee', function ($employeeQuery) use ($name, $companyId, $positionId) {
                $employeeQuery->whereHas('candidate', function ($candidateQuery) use ($name) {
                    $candidateQuery->where('name', 'like', '%' . $name . '%');
                })->when($positionId, function ($positionQuery, $positionId) {
                    $positionQuery->where('position_id', $positionId);
                })
                    ->whereHas('position', function ($positionQuery) use ($companyId, $positionId) {
                        $positionQuery->when($companyId, function ($filteredPositionQuery, $companyId) {
                            $filteredPositionQuery->where('company_id', $companyId);
                        });
                    });
            })
            ->with($withRelationships)
            ->select('id', 'employee_id', 'shift_id', 'date')
            ->get();

        $employeeRecurringShifts = EmployeeRecurringShift::where('day', $day)
            ->whereHas('employee', function ($employeeQuery) use ($name, $companyId, $positionId) {
                $employeeQuery->whereHas('candidate', function ($candidateQuery) use ($name) {
                    $candidateQuery->where('name', 'like', '%' . $name . '%');
                })->when($positionId, function ($positionQuery, $positionId) {
                    $positionQuery->where('position_id', $positionId);
                })
                    ->whereHas('position', function ($positionQuery) use ($companyId, $positionId) {
                        $positionQuery->when($companyId, function ($filteredPositionQuery, $companyId) {
                            $filteredPositionQuery->where('company_id', $companyId);
                        });
                    });
            })
            ->with($withRelationships)
            ->select('id', 'employee_id', 'shift_id', 'day')
            ->get();

        return $this->showOne([
            'one_time_shifts' => $employeeOneTimeShifts,
            'recurring_shifts' => $employeeRecurringShifts,
        ]);
    }


    public function getShift(Request $request)
    {
        $date = $request->date;
        $companyId = $request->company_id;
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $employee = Employee::where('candidate_id', $candidate->id)->whereHas('company', function ($query) use ($companyId) {
            $query->where('companies.id', $companyId);
        })->first();
        $employeeOneTimeShifts = $employee->getOneTimeShifts($date);
        $employeeRecurringShifts = $employee->getRecurringShifts($date);
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
