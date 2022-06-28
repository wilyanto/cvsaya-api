<?php

namespace App\Http\Common\Sort;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Sorts\Sort;

class LastMessageCredentialSort implements Sort
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        $query->whereHas('blastLogs', function ($query) use ($value) {
            $query->orderBy('created_at', 'desc');
        });

        return $query;
    }
}
