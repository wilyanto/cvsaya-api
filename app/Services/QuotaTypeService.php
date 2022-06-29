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
        $quotaType = QuotaType::orderBy('priority', 'desc')->first();
        if ($quotaType) {
            $lastPriorityNumber = $quotaType->priority + 1;
        } else {
            $lastPriorityNumber = 1;
        }

        $blastType = QuotaType::create([
            'name' => $data->name,
            'priority' => $lastPriorityNumber,
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

    public function reorderPriority($data)
    {
        $quotaTypes = [];
        foreach ($data as $datum) {
            $quotaType = $this->getById($datum['id']);

            $quotaType->update(['priority' => $datum['priority']]);

            array_push($quotaTypes, $quotaType);
        }

        return $quotaTypes;
    }
}
