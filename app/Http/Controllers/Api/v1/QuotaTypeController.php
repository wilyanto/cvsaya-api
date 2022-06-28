<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuotaTypeReorderPriorityRequest;
use App\Http\Requests\QuotaTypeStoreRequest;
use App\Http\Requests\QuotaTypeUpdateRequest;
use App\Http\Resources\QuotaTypeResource;
use App\Services\QuotaTypeService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class QuotaTypeController extends Controller
{
    use ApiResponser;

    protected $quotaTypeService;

    public function __construct(QuotaTypeService $quotaTypeService)
    {
        $this->quotaTypeService = $quotaTypeService;
    }

    public function index()
    {
        $quotaTypes = $this->quotaTypeService->getAll();

        return $this->showAll(collect(QuotaTypeResource::collection($quotaTypes)));
    }

    public function store(QuotaTypeStoreRequest $request)
    {
        $quotaType = $this->quotaTypeService->createQuotaType($request);

        return $this->showOne(new QuotaTypeResource($quotaType));
    }

    public function show($id)
    {
        $quotaType = $this->quotaTypeService->getById($id);

        return $this->showOne(new QuotaTypeResource($quotaType));
    }

    public function update(QuotaTypeUpdateRequest $request, $id)
    {
        $quotaType = $this->quotaTypeService->updateQuotaType($request, $id);

        return $this->showOne(new QuotaTypeResource($quotaType));
    }

    public function destroy($id)
    {
        $message = $this->quotaTypeService->deleteById($id);

        return response()->json(null, 204);
    }

    public function reorderPriority(QuotaTypeReorderPriorityRequest $request)
    {
        $quotaTypes = $this->quotaTypeService->reorderPriority($request->validated());

        return $this->showAll(collect(QuotaTypeResource::collection($quotaTypes)));
    }
}
