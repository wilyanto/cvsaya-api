<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\EmploymentType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;

class EmploymentTypeController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->input()) {
            $request->validate([
                'id' => 'nullable|integer',
                'name' => 'nullable|string'
            ]);
        }
        $id = $request->id;
        $name = $request->name;
        $employee = EmploymentType::where(function ($query) use ($id, $name) {
            if ($id != null) {
                $query->where('id', $id);
            }
            if ($name != null) {
                $query->where('name', 'like', '%' . $name . '%');
            }
        })->get();
        return $this->showAll($employee);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmploymentType  $employmentType
     * @return \Illuminate\Http\Response
     */
    public function show(EmploymentType $employmentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmploymentType  $employmentType
     * @return \Illuminate\Http\Response
     */
    public function edit(EmploymentType $employmentType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmploymentType  $employmentType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmploymentType $employmentType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmploymentType  $employmentType
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmploymentType $employmentType)
    {
        //
    }
}
