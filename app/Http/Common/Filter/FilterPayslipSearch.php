<?php

namespace App\Http\Common\Filter;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FilterPayslipSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        $query->whereHas('employee', function ($query) use ($value) {
            $query->where('name', 'LIKE', '%' . $value . '%');
        });

        return $query;
    }
}
