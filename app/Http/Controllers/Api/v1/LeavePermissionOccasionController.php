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

    private $company;

    public function __construct()
    {
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $employee = Employee::where('candidate_id', $candidate->id)->firstOrFail();
        // handle multiple company
        $this->company = $employee->company;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leavePermissionOccasions = QueryBuilder::for(LeavePermissionOccasion::class)
            ->allowedIncludes(['company'])
            ->where('company_id', $this->company->id)
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
        if ($this->company->id != $request->company_id) {
            return $this->errorResponse('Cannot create Occasion', 422, 42200);
        }
        $leavePermissionOccasion = LeavePermissionOccasion::create($request->all());

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
        $leavePermissionOccasion = QueryBuilder::for(LeavePermissionOccasion::class)
            ->allowedIncludes(['company'])
            ->where('company_id', $this->company->id)
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
    public function update(LeavePermissionOccasionUpdateRequest $request, LeavePermissionOccasion $leavePermissionOccasion)
    {
        if ($this->company->id != $request->company_id) {
            return $this->errorResponse('Cannot update Occasion', 422, 42200);
        }
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
        if ($this->company->id != $leavePermissionOccasion->company_id) {
            return $this->errorResponse('Cannot delete Occasion', 422, 42200);
        }
        $leavePermissionOccasion->delete();

        return $this->showOne(null);
    }
}
