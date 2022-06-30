<?php

namespace App\Services;

use App\Http\Common\Filter\FilterCredentialSearch;
use App\Http\Common\Sort\LastMessageCredentialSort;
use App\Http\Common\Sort\MessageCountCredentialSort;
use App\Models\CRMCredential;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class CRMCredentialService
{
    protected $CRMCredentialQuotaTypeService;

    public function __construct(
        CRMCredentialQuotaTypeService $CRMCredentialQuotaTypeService
    ) {
        $this->CRMCredentialQuotaTypeService = $CRMCredentialQuotaTypeService;
    }

    public function getAll($size)
    {
        $credentials = QueryBuilder::for(CRMCredential::class)
            ->allowedSorts([
                AllowedSort::custom(
                    'last-message',
                    new LastMessageCredentialSort()
                ),
                AllowedSort::custom(
                    'message-count',
                    new MessageCountCredentialSort()
                )
            ])
            ->allowedFilters(
                [
                    AllowedFilter::custom('search', new FilterCredentialSearch),
                    AllowedFilter::exact('is_active'),
                ]
            )
            ->paginate($size);

        return $credentials;
    }

    public function getById($id)
    {
        $query = CRMCredential::where('id', $id);
        $CRMCredential = QueryBuilder::for($query)
            ->allowedIncludes([
                'blastLogs',
                'recentMessages'
            ])
            ->firstOrFail();

        return $CRMCredential;
    }

    public function createCredential($data)
    {
        $CRMCredential = CRMCredential::create([
            'name' => $data->name,
            'key' => $data->key,
            'country_code' => $data->country_code,
            'phone_number' => $data->phone_number,
            'is_active' => $data->is_active
        ]);

        // assign quotas
        $this->CRMCredentialQuotaTypeService->syncCredentialQuotaType($CRMCredential->id, $data->key);

        return $CRMCredential;
    }

    public function updateCredential($data, $id)
    {
        $CRMCredential = $this->getById($id);
        $CRMCredential->update([
            'name' => $data->name,
            'key' => $data->key,
            'is_active' => $data->is_active
        ]);

        return $CRMCredential;
    }

    public function updateCredentialStatus($isActive, $id)
    {
        $CRMCredential = $this->getById($id);
        $CRMCredential->update([
            'is_active' => $isActive
        ]);

        return $CRMCredential;
    }
}
