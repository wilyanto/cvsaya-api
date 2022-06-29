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
        $blastType = BlastType::create([
            'name' => $data->name,
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
}
