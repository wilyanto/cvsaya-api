<?php

namespace App\Services;

use App\Models\BlastType;
use Spatie\QueryBuilder\QueryBuilder;

class BlastTypeService
{
    public function getAll()
    {
        $blastTypes = QueryBuilder::for(BlastType::class)
            ->get();

        return $blastTypes;
    }

    public function getById($id)
    {
        $query = BlastType::where('id', $id);
        $blastType = QueryBuilder::for($query)
            ->firstOrFail();

        return $blastType;
    }

    public function createBlastType($data)
    {
        $blastType = BlastType::orderBy('priority', 'desc')->first();
        if ($blastType) {
            $lastPriorityNumber = $blastType->priority + 1;
        } else {
            $lastPriorityNumber = 1;
        }

        $blastType = BlastType::create([
            'name' => $data->name,
            'priority' => $lastPriorityNumber
        ]);

        return $blastType;
    }

    public function updateBlastType($data, $id)
    {
        $blastType = $this->getById($id);
        $blastType->update([
            'name' => $data->name,
        ]);

        return $blastType;
    }

    public function deleteById($id)
    {
        $blastType = BlastType::where('id', $id)->firstOrFail();
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
