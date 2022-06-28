<?php

namespace App\Http\Common\Filter;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FilterCredentialSearch implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        // TODO: not working on laravel but working on local DB
        $query->where(function ($query) use ($value) {
            $query = $query->where('name', 'LIKE', '%' . $value . '%')
                ->orWhere(DB::raw('CONCAT(`country_code`, `phone_number`)'), 'LIKE', '%' . $value . '%');
        });

        return $query;
    }
}
