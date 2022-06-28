<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRMCredentialStoreRequest;
use App\Http\Requests\CRMCredentialUpdateRequest;
use App\Http\Requests\CRMCredentialUpdateStatusRequest;
use App\Http\Resources\CRMCredentialResource;
use App\Services\CRMCredentialService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CRMCredentialController extends Controller
{
    use ApiResponser;

    protected $CRMCredentialService;

    public function __construct(CRMCredentialService $CRMCredentialService)
    {
        $this->CRMCredentialService = $CRMCredentialService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $CRMCredentials = $this->CRMCredentialService->getAll();

        return $this->showAll(collect(CRMCredentialResource::collection($CRMCredentials)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CRMCredentialStoreRequest $request)
    {
        $CRMCredential = $this->CRMCredentialService->createCredential($request);

        return $this->showOne(new CRMCredentialResource($CRMCredential));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $CRMCredential = $this->CRMCredentialService->getById($id);

        return $this->showOne(new CRMCredentialResource($CRMCredential));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CRMCredentialUpdateRequest $request, $id)
    {
        $CRMCredential = $this->CRMCredentialService->updateCredential($request, $id);

        return $this->showOne(new CRMCredentialResource($CRMCredential));
    }

    public function updateStatus(CRMCredentialUpdateStatusRequest $request, $id)
    {
        $CRMCredential = $this->CRMCredentialService->updateCredentialStatus($request->is_active, $id);

        return $this->showOne(new CRMCredentialResource($CRMCredential));
    }
}
