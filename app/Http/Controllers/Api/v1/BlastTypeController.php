<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlastTypeReorderPriorityRequest;
use App\Http\Requests\BlastTypeStoreRequest;
use App\Http\Requests\BlastTypeUpdateRequest;
use App\Http\Resources\BlastTypeResource;
use App\Services\BlastTypeService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class BlastTypeController extends Controller
{
    use ApiResponser;

    protected $blastTypeService;

    public function __construct(BlastTypeService $blastTypeService)
    {
        $this->blastTypeService = $blastTypeService;
    }

    public function index()
    {
        $blastTypes = $this->blastTypeService->getAll();

        return $this->showAll(collect(BlastTypeResource::collection($blastTypes)));
    }

    public function store(BlastTypeStoreRequest $request)
    {
        $blastType = $this->blastTypeService->createBlastType($request);

        return $this->showOne(new BlastTypeResource($blastType));
    }

    public function show($id)
    {
        $blastType = $this->blastTypeService->getById($id);

        return $this->showOne(new BlastTypeResource($blastType));
    }

    public function update(BlastTypeUpdateRequest $request, $id)
    {
        $blastType = $this->blastTypeService->updateBlastType($request, $id);

        return $this->showOne(new BlastTypeResource($blastType));
    }

    public function destroy($id)
    {
        $message = $this->blastTypeService->deleteById($id);

        return response()->json(null, 204);
    }

    public function reorderPriority(BlastTypeReorderPriorityRequest $request)
    {
        $blastTypes = $this->blastTypeService->reorderPriority($request->validated());

        return $this->showAll(collect(BlastTypeResource::collection($blastTypes)));
    }
}
