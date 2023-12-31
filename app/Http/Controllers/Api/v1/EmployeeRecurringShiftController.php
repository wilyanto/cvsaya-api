<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Common\Filter\FilterShiftEmployeeCompany;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRecurringShift;
use App\Http\Requests\UpdateEmployeeRecurringShift;
use App\Http\Resources\RecurringShiftResource;
use App\Models\EmployeeRecurringShift;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EmployeeRecurringShiftController extends Controller
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
        $day = $request->day;
        $employeeRecurringShifts = EmployeeRecurringShift::with(
            'shift',
            'employee.position',
            'employee.profileDetail',
        )
            ->where(function ($query) use ($day) {
                if ($day !== null) {
                    $query->where('day', $day);
                }
            })
            ->when($employeeId, function ($query, $employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->get();

        return $this->showOne(collect(RecurringShiftResource::collection($employeeRecurringShifts)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRecurringShift $request)
    {
        $employeeRecurringShift = EmployeeRecurringShift::create($request->all());
        return $this->showOne(new RecurringShiftResource($employeeRecurringShift));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeRecurringShift  $employeeRecurringShift
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeRecurringShift $employeeRecurringShift)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeRecurringShift  $employeeRecurringShift
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRecurringShift $request, $id)
    {
        $updatedRecurringShift = EmployeeRecurringShift::findOrFail($id)
            ->update($request->all());
        return $this->showOne(new RecurringShiftResource($updatedRecurringShift));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeRecurringShift  $employeeRecurringShift
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        EmployeeRecurringShift::findOrFail($id)->delete();
        return $this->showOne(null, 204);
    }

    public function getEmployeeRecurringShifts($employeeId)
    {
        $employeeRecurringShifts = QueryBuilder::for(EmployeeRecurringShift::class)
            ->allowedIncludes(['shift'])
            ->allowedFilters([
                AllowedFilter::custom('company', new FilterShiftEmployeeCompany),
            ])
            ->where('employee_id', $employeeId)
            ->orderBy('day')
            ->get();
        return $this->showOne(collect(RecurringShiftResource::collection($employeeRecurringShifts)));
    }
}
