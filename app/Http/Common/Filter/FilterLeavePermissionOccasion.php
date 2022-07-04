<?php

namespace App\Http\Common\Filter;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterLeavePermissionOccasion implements Filter
{

    public function __invoke(Builder $query, $value, string $property): Builder
    {
        $query->whereHas('occasion', function ($query) use ($value) {
            $query->where('name', $value);
        });

        return $query;
    }
}
