<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeavePermissionStoreRequest;
use App\Http\Resources\LeavePermissionResource;
use App\Models\LeavePermission;
use App\Models\LeavePermissionOccasion;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class LeavePermissionController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leavePermissions = LeavePermission::get();

        return $this->showAll(collect(LeavePermissionResource::collection($leavePermissions)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeavePermissionStoreRequest $request)
    {
        $leavePermissionOccasion = LeavePermissionOccasion::findOrFail($request->occasion_id);

        $leavePermission = LeavePermission::create([
            'started_at' => $request->started_at,
            'ended_at' => $request->ended_at,
            'employee_id' => $request->employee_id,
            'occasion_id' => $request->occasion_id,
            'reason' => $request->reason,
        ]);

        return new LeavePermissionResource($leavePermission);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leavePermission = LeavePermission::findOrFail($id);

        return new LeavePermissionResource($leavePermission);
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
        $leavePermission = LeavePermission::findOrFail($id);

        $leavePermission::update([
            'started_at' => $request->started_at,
            'ended_at' => $request->ended_at,
            'employee_id' => $request->employee_id,
            'occasion_id' => $request->occasion_id,
            'reason' => $request->reason,
            'status' => $request->status,
            'answered_at' => $request->answered_at
        ]);

        return new LeavePermissionResource($leavePermission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leavePermission = LeavePermission::findOrFail($id);
        $leavePermission->delete();

        return $this->showOne(null);
    }
}
