<?php

namespace App\Http\Common\Filter;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FilterBlastLogSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        // TODO: search by message
        $query->where(function ($query) use ($value) {
            $query = $query->where(DB::raw('CONCAT(`recipient_country_code`, `recipient_phone_number`)'), 'LIKE', '%' . $value . '%');
        });

        return $query;
    }
}
