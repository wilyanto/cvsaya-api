<?php

namespace App\Http\Common\Filter;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterShiftEmployeeCompany implements Filter
{

  public function __invoke(Builder $query, $value, string $property): Builder
  {
    $query->whereHas('shift', function ($query) use ($value) {
      $query->where('company_id', $value);
    });

    return $query;
  }
}
