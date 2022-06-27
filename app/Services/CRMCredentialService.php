<?php

namespace App\Services;

use App\Models\CRMCredential;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

class CRMCredentialService
{
    public function getAll()
    {
        $credentials = QueryBuilder::for(CRMCredential::class)
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
            'quantity' => $data->quantity
        ]);

        return $CRMCredential;
    }

    public function updateCredential($data, $id)
    {
        $CRMCredential = $this->getById($id);
        $CRMCredential->update([
            'name' => $data->name,
            'key' => $data->key,
            'quantity' => $data->quantity
        ]);

        return $CRMCredential;
    }
}
