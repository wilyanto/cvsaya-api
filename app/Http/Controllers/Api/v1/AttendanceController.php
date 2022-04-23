<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ShiftEmployee;
use Doctrine\DBAL\Schema\Schema;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $request->validate([
        //     'started_at' => [
        //         'date_format:Y-m-d\TH:i:s.v\Z',
        //         'required'
        //     ],
        //     'updated_at' => [
        //         'date_format:Y-m-d\TH:i:s.v\Z',
        //         'required'
        //     ],
        // ]);

        // $user = auth()->user();

        // $employee = Employee::where('user_id',$user->id_kustomer)->firstOrfail();


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
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
