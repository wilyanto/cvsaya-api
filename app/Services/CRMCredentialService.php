<?php

namespace App\Services;

use App\Http\Common\Filter\FilterCredentialSearch;
use App\Http\Common\Filter\SortCredential;
use App\Http\Common\Sort\LastMessageCredentialSort;
use App\Http\Common\Sort\MessageCountCredentialSort;
use App\Models\CRMCredential;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class CRMCredentialService
{
    public function getAll()
    {
        $credentials = QueryBuilder::for(CRMCredential::class)
            ->allowedSorts([
                AllowedSort::custom(
                    'last-message',
                    new LastMessageCredentialSort
                ),
                AllowedSort::custom(
                    'message-count',
                    new MessageCountCredentialSort
                )
            ])
            ->allowedFilters(
                [
                    AllowedFilter::exact('is_active'),
                    AllowedFilter::custom('search', new FilterCredentialSearch)
                ]
            )
            ->get();

        return $credentials;
    }

    public function getById($id)
    {
        $query = CRMCredential::where('id', $id);
        $CRMCredential = QueryBuilder::for($query)
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
}
