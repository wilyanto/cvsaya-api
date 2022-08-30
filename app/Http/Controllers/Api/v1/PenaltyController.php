<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PenaltyStoreRequest;
use App\Http\Requests\PenaltyUpdateRequest;
use App\Http\Resources\PenaltyResource;
use App\Services\PenaltyService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class PenaltyController extends Controller
{
    use ApiResponser;

    protected $penaltyService;

    public function __construct(PenaltyService $penaltyService)
    {
        $this->penaltyService = $penaltyService;
    }

    public function index()
    {
        $payrollPeriods = $this->penaltyService->getAll();

        return $this->showAll(collect(PenaltyResource::collection($payrollPeriods)));
    }

    public function store(PenaltyStoreRequest $request)
    {
        $payrollPeriod = $this->penaltyService->createPenalty($request);

        return $this->showOne(new PenaltyResource($payrollPeriod));
    }

    public function show($companyId, $id)
    {
        $payrollPeriod = $this->penaltyService->getById($id);

        return $this->showOne(new PenaltyResource($payrollPeriod));
    }

    public function update(PenaltyUpdateRequest $request, $companyId, $id)
    {
        $payrollPeriod = $this->penaltyService->updatePenalty($request, $id);

        return $this->showOne(new PenaltyResource($payrollPeriod));
    }

    public function destroy($companyId, $id)
    {
        $message = $this->penaltyService->deleteById($id);

        return response()->json(null, 204);
    }
}
