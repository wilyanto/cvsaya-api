<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlastTypeRuleStoreRequest;
use App\Http\Requests\BlastTypeRuleUpdateRequest;
use App\Http\Resources\BlastTypeRuleResource;
use App\Services\BlastTypeRuleService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class BlastTypeRuleController extends Controller
{
    use ApiResponser;

    protected $blastTypeRuleService;

    public function __construct(BlastTypeRuleService $blastTypeRuleService)
    {
        $this->blastTypeRuleService = $blastTypeRuleService;
    }

    public function index()
    {
        $blastTypes = $this->blastTypeRuleService->getAll();

        return $this->showAll(collect(BlastTypeRuleResource::collection($blastTypes)));
    }

    public function store(BlastTypeRuleStoreRequest $request)
    {
        $blastType = $this->blastTypeRuleService->createBlastTypeRule($request);

        return $this->showOne(new BlastTypeRuleResource($blastType));
    }

    public function show($id)
    {
        $blastType = $this->blastTypeRuleService->getById($id);

        return $this->showOne(new BlastTypeRuleResource($blastType));
    }

    public function update(BlastTypeRuleUpdateRequest $request, $id)
    {
        $blastType = $this->blastTypeRuleService->updateBlastTypeRule($request, $id);

        return $this->showOne(new BlastTypeRuleResource($blastType));
    }

    public function destroy($id)
    {
        $message = $this->blastTypeRuleService->deleteById($id);

        return response()->json(null, 204);
    }
}
