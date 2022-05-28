<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeavePermissionOccasionStoreRequest;
use App\Http\Requests\LeavePermissionOccasionUpdateRequest;
use App\Http\Requests\LeavePermissionStoreRequest;
use App\Http\Resources\LeavePermissionOccasionResource;
use App\Models\LeavePermissionOccasion;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class LeavePermissionOccasionController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leavePermissionOccasions = LeavePermissionOccasion::get();

        return $this->showAll(collect(LeavePermissionOccasionResource::collection($leavePermissionOccasions)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeavePermissionOccasionStoreRequest $request)
    {
        $leavePermissionOccasion = LeavePermissionOccasion::create([
            'name' => $request->name,
            'max_day' => $request->max_day,
        ]);

        return $this->showOne(new LeavePermissionOccasionResource($leavePermissionOccasion));
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

        return $this->showOne(new LeavePermissionOccasionResource($leavePermissionOccasion));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LeavePermissionOccasionUpdateRequest $request, LeavePermissionOccasion $leavePermissionOccasion)
    {
        $leavePermissionOccasion->update($request->all());

        return $this->showOne(new LeavePermissionOccasionResource($leavePermissionOccasion));
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
