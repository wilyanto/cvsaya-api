<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanySalaryTypeStoreRequest;
use App\Http\Requests\CompanySalaryTypeUpdateRequest;
use App\Http\Resources\CompanySalaryTypeResource;
use App\Services\CompanySalaryTypeService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CompanySalaryTypeController extends Controller
{
    use ApiResponser;

    protected $companySalaryTypeService;

    public function __construct(CompanySalaryTypeService $companySalaryTypeService)
    {
        $this->companySalaryTypeService = $companySalaryTypeService;
    }

    public function index()
    {
        $companySalaryTypes = $this->companySalaryTypeService->getAll();

        return $this->showAll(collect(CompanySalaryTypeResource::collection($companySalaryTypes)));
    }

    public function store(CompanySalaryTypeStoreRequest $request)
    {
        $companySalaryType = $this->companySalaryTypeService->createCompanySalaryType($request);

        return $this->showOne(new CompanySalaryTypeResource($companySalaryType));
    }

    public function show($id)
    {
        $companySalaryType = $this->companySalaryTypeService->getById($id);

        return $this->showOne(new CompanySalaryTypeResource($companySalaryType));
    }

    public function update(CompanySalaryTypeUpdateRequest $request, $id)
    {
        $companySalaryType = $this->companySalaryTypeService->updateCompanySalaryType($request, $id);

        return $this->showOne(new CompanySalaryTypeResource($companySalaryType));
    }

    public function destroy($id)
    {
        $message = $this->companySalaryTypeService->deleteById($id);

        return response()->json(null, 204);
    }
}
