<?php

namespace App\Services;

use App\Models\QuotaType;
use Spatie\QueryBuilder\QueryBuilder;

class QuotaTypeService
{
    public function getAll()
    {
        $blastTypes = QueryBuilder::for(QuotaType::class)
            ->get();

        return $blastTypes;
    }

    public function getById($id)
    {
        $query = QuotaType::where('id', $id);
        $blastType = QueryBuilder::for($query)
            ->firstOrFail();

        return $blastType;
    }

    public function createQuotaType($data)
    {
        $blastType = QuotaType::create([
            'name' => $data->name,
            'priority' => $data->priority,
            'start_time' => $data->start_time,
            'end_time' => $data->end_time
        ]);

        return $blastType;
    }

    public function updateQuotaType($data, $id)
    {
        $blastType = $this->getById($id);
        $blastType->update([
            'name' => $data->name,
            'priority' => $data->priority,
            'start_time' => $data->start_time,
            'end_time' => $data->end_time
        ]);

        return $blastType;
    }

    public function deleteById($id)
    {
        $blastType = QuotaType::where('id', $id)->firstOrFail();
        $blastType->delete();
        return true;
    }
}
