<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeavePermissionOccasionStoreRequest;
use App\Http\Requests\LeavePermissionOccasionUpdateRequest;
use App\Http\Resources\LeavePermissionOccasionResource;
use App\Models\Candidate;
use App\Models\Employee;
use App\Models\LeavePermissionOccasion;
use App\Traits\ApiResponser;
use Spatie\QueryBuilder\QueryBuilder;

class LeavePermissionOccasionController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($companyId)
    {
        $leavePermissionOccasions = QueryBuilder::for(LeavePermissionOccasion::class)
            ->allowedIncludes(['company'])
            ->where('company_id', $companyId)
            ->get();

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
        $leavePermissionOccasion = LeavePermissionOccasion::create($request->all());

        return $this->showOne(new LeavePermissionOccasionResource($leavePermissionOccasion));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($companyId, $id)
    {
        $leavePermissionOccasion = QueryBuilder::for(LeavePermissionOccasion::class)
            ->allowedIncludes(['company'])
            ->findOrFail($id);

        return $this->showOne(new LeavePermissionOccasionResource($leavePermissionOccasion));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LeavePermissionOccasionUpdateRequest $request, $companyId, LeavePermissionOccasion $leavePermissionOccasion)
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
    public function destroy($companyId, $id)
    {
        $leavePermissionOccasion = LeavePermissionOccasion::findOrFail($id);
        $leavePermissionOccasion->delete();

        return $this->showOne(null);
    }
}
