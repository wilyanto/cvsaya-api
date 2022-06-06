<?php

namespace App\Http\Common\Filter;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterLeavePermissionEmployeeNameSearch implements Filter
{

    public function __invoke(Builder $query, $value, string $property): Builder
    {
        $query->whereHas('candidate', function ($query) use ($value) {
            $query->where('name', 'LIKE', '%' . $value . '%');
        });

        return $query;
    }
}
