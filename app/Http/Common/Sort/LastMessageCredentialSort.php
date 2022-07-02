<?php

namespace App\Http\Common\Sort;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Sorts\Sort;

class LastMessageCredentialSort implements Sort
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->leftJoin('crm_blast_logs', 'crm_blast_logs.credential_id', '=', 'crm_credentials.id')->whereIn('crm_blast_logs.id', function ($query) {
            $query->from('crm_blast_logs')->selectRaw('MAX(id)')->groupBy('credential_id');
        })->orWhereNull('crm_blast_logs.id')->orderBy('crm_blast_logs.created_at', 'desc')->select(
            'crm_credentials.*',
            'crm_blast_logs.created_at as blast_log_created_at'
        );
    }
}
