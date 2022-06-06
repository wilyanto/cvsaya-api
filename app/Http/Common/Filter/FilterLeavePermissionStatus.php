<?php

namespace App\Http\Common\Filter;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterLeavePermissionStatus implements Filter
{

    public function __invoke(Builder $query, $value, string $property): Builder
    {
        $query->where('status', $value);

        return $query;
    }
}
