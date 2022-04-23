<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Level;
use App\Models\Position;

class EmployeeController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => 'nullable|numeric|gt:0',
            'page_size' => 'nullable|numeric|gt:0'
        ]);

        $page = $request->page ? $request->page  : 1;
        $pageSize = $request->page_size ? $request->page_size : 10;
        $employees = Employee::paginate(
            $perpage = $pageSize,
            $columns =  ['*'],
            $pageName = 'page',
            $pageBody = $page
        );

        $data = $employees->map(function ($item, $key) {
            return $item;
        });

        return $this->showPaginate('employees', collect($data),collect($employees));
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
    public function show($id)
    {
        $employeeDetail = Employee::where('user_id',$id)->first();

        return $this->showOne($employeeDetail);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeDetails  $employeeDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employees)
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
    public function update(Request $request, Employee $employees)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeDetails  $employeeDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employees)
    {
        //
    }
}
