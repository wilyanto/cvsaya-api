<?php

namespace App\Services;

use App\Http\Common\Filter\FilterPenaltySearch;
use App\Models\PayrollPeriod;
use App\Models\Penalty;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PenaltyService
{
    public function getAll()
    {
        $penalties = QueryBuilder::for(Penalty::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterPenaltySearch),
            ])
            ->get();

        return $penalties;
    }

    public function getById($id)
    {
        $query = Penalty::where('id', $id);
        $penalty = QueryBuilder::for($query)
            ->allowedIncludes([
                'company'
            ])
            ->firstOrFail();

        return $penalty;
    }

    public function createPenalty($data)
    {
        $penalty = Penalty::create([
            'name' => $data->name,
            'amount' => $data->amount,
            'company_id' => $data->company_id,
            'lateness' => $data->lateness,
            'attendance_type' => $data->attendance_type
        ]);

        return $penalty;
    }

    public function updatePenalty($data, $id)
    {
        $penalty = $this->getById($id);
        $penalty->update([
            'name' => $data->name,
            'amount' => $data->amount,
            'company_id' => $data->company_id,
            'lateness' => $data->lateness,
            'attendance_type' => $data->attendance_type
        ]);

        return $penalty;
    }

    public function deleteById($id)
    {
        $penalty = Penalty::findOrFail($id);
        $penalty->delete();
        return true;
    }
}
