<?php

namespace App\Http\Common\Filter;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterAnnouncementEmployeeSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        $query->whereHas('employee.candidate', function ($query) use ($value) {
            $query->where('name', 'LIKE', '%' . $value . '%');
        });

        return $query;
    }
}
