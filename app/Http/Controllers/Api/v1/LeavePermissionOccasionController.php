<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeavePermissionOccasionResource;
use App\Models\LeavePermissionOccasion;
use Illuminate\Http\Request;

class LeavePermissionOccasionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leavePermissionOccasions = LeavePermissionOccasion::get();

        return LeavePermissionOccasionResource::collection($leavePermissionOccasions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $leavePermissionOccasion = LeavePermissionOccasion::create([
            'name' => $request->name,
            'max_day' => $request->max_day,
        ]);

        return new LeavePermissionOccasionResource($leavePermissionOccasion);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leavePermissionOccasion = LeavePermissionOccasion::findOrFail($id);

        return new LeavePermissionOccasionResource($leavePermissionOccasion);
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
        $leavePermissionOccasion = LeavePermissionOccasion::findOrFail($id);

        $leavePermissionOccasion::update([
            'name' => $request->name,
            'max_day' => $request->max_day,
        ]);

        return new LeavePermissionOccasionResource($leavePermissionOccasion);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leavePermissionOccasion = LeavePermissionOccasion::findOrFail($id);
        $leavePermissionOccasion->delete();

        return $this->showOne(null);
    }
}
