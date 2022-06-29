<?php

namespace App\Http\Common\Filter;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FilterBlastLogDateRange implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->whereBetween('created_at', $value);
    }
}
