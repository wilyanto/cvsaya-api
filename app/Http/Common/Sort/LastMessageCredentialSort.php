<?php

namespace App\Http\Common\Sort;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Sorts\Sort;

class LastMessageCredentialSort implements Sort
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        // TODO: need to fix join logic
        // source
        // https://stackoverflow.com/questions/58255702/how-to-sort-by-a-custom-appended-relation-to-model
        return $query->join('crm_blast_logs', 'crm_blast_logs.credential_id', '=', 'crm_credentials.id')
            ->orderBy('crm_blast_logs.created_at', 'desc')
            ->select('crm_credentials.*', 'crm_blast_logs.created_at');
    }
}
