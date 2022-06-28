<?php

namespace App\Http\Common\Sort;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Sorts\Sort;

class MessageCountCredentialSort implements Sort
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->withCount(['blastLogs', function ($query) {
            $query->whereDate('created_at', today());
        }])->orderBy('blastLogs_count', 'desc');
    }
}
