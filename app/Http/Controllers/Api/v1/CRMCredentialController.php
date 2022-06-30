<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRMCredentialBlastTypeUpdateResource;
use App\Http\Requests\CRMCredentialStoreRequest;
use App\Http\Requests\CRMCredentialUpdateRequest;
use App\Http\Requests\CRMCredentialUpdateStatusRequest;
use App\Http\Resources\CRMBlastLogResource;
use App\Http\Resources\CRMCredentialBlastTypeResource;
use App\Http\Resources\CRMCredentialQuotaTypeResource;
use App\Http\Resources\CRMCredentialResource;
use App\Services\CRMBlastLogService;
use App\Services\CRMCredentialBlastTypeService;
use App\Services\CRMCredentialQuotaTypeService;
use App\Services\CRMCredentialService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CRMCredentialController extends Controller
{
    use ApiResponser;

    protected $CRMBlastLogService,
        $CRMCredentialService,
        $CRMCredentialBlastTypeService,
        $CRMCredentialQuotaTypeService;

    public function __construct(
        CRMBlastLogService $CRMBlastLogService,
        CRMCredentialService $CRMCredentialService,
        CRMCredentialBlastTypeService $CRMCredentialBlastTypeService,
        CRMCredentialQuotaTypeService $CRMCredentialQuotaTypeService
    ) {
        $this->CRMBlastLogService = $CRMBlastLogService;
        $this->CRMCredentialService = $CRMCredentialService;
        $this->CRMCredentialBlastTypeService = $CRMCredentialBlastTypeService;
        $this->CRMCredentialQuotaTypeService = $CRMCredentialQuotaTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $size = $request->input('page_size', 10);
        $CRMCredentials = $this->CRMCredentialService->getAll($size);

        return $this->showPaginate('credentials', collect(CRMCredentialResource::collection($CRMCredentials)), collect($CRMCredentials));
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

    public function getBlastLogs(Request $request, $credentialId)
    {
        $size = $request->input('page_size', 10);
        $CRMBlastLogs = $this->CRMBlastLogService->getBlastLogByCredentialId($credentialId, $size);

        return $this->showPaginate('blast_logs', collect(CRMBlastLogResource::collection($CRMBlastLogs)), collect($CRMBlastLogs));
    }

    public function getBlastTypes($credentialId)
    {
        $credentialBlastTypes = $this->CRMCredentialBlastTypeService->getAllCRMCredentialBlastTypeByCredentialId($credentialId);

        return $this->showAll(collect(CRMCredentialBlastTypeResource::collection($credentialBlastTypes)));
    }

    public function updateBlastTypes(CRMCredentialBlastTypeUpdateResource $request, $credentialId)
    {
        $credentialBlastTypes = $this->CRMCredentialBlastTypeService->updateByCredentialId($credentialId, $request->validated());

        return $this->showAll(collect(CRMCredentialBlastTypeResource::collection($credentialBlastTypes)));
    }

    public function syncCredentialQuota($credentialId)
    {
        $credential = $this->CRMCredentialService->getById($credentialId);
        $credentialQuotaTypes = $this->CRMCredentialQuotaTypeService->syncCredentialQuotaType($credential->id, $credential->key);

        return $this->showAll(collect(CRMCredentialQuotaTypeResource::collection($credentialQuotaTypes)));
    }
}
