<?php

namespace App\Services;

use App\Http\Common\Filter\FilterCredentialSearch;
use App\Http\Common\Sort\LastMessageCredentialSort;
use App\Http\Common\Sort\MessageCountCredentialSort;
use App\Models\CRMCredential;
use Illuminate\Support\Facades\Http;
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
                'blastTypes',
                'quotas',
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
            'is_active' => $data->is_active,
            'expired_at' => $data->expired_at
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
            'is_active' => $data->is_active,
            'expired_at' => $data->expired_at
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

    public function syncCredential($credential)
    {
        $data = json_decode(json_encode($this->getCredentialDataByKey($credential->key)));
        // update credential
        if (count($data) === 0) {
            return
                $credential->load([
                    'blastTypes',
                    'quotas.quotaType',
                    'blastLogs',
                    'recentMessages'
                ]);
        }

        $credential->update([
            'expired_at' => $data->expired_at,
            'scheduled_message_count' => $data->scheduled_message_count,
            'last_updated_at' => now(),
        ]);

        // update quotas
        $this->CRMCredentialQuotaTypeService->syncCredentialQuotaType($credential->id, $credential->key);

        return $credential->load([
            'blastTypes',
            'quotas',
            'blastLogs',
            'recentMessages'
        ]);
    }

    public function getCredentialDataByKey($key)
    {
        $url = env('ECRM_URL') . "/api/v1/whatsapp-devices/$key/key";
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])
            ->acceptJson()
            ->get($url);

        if ($response->failed()) {
            $data = [];
        } else {
            $data = json_decode($response->body(), true)['data'];
        }
        return $data;
    }
}
