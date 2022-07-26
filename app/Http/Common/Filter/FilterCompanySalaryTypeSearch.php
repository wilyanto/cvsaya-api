<?php

namespace App\Http\Common\Filter;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterCompanySalaryTypeSearch implements Filter
{

    public function __invoke(Builder $query, $value, string $property): Builder
    {
        $query->whereHas('salaryType', function ($query) use ($value) {
            $query->where('name', 'LIKE', '%' . $value . '%');
        });

        return $query;
    }
}
