<?php

namespace App\Services;

use App\Models\BlastTypeRule;
use Spatie\QueryBuilder\QueryBuilder;

class BlastTypeRuleService
{
    public function getAll()
    {
        $blastTypeRules = QueryBuilder::for(BlastTypeRule::class)
            ->get();

        return $blastTypeRules;
    }

    public function getById($id)
    {
        $query = BlastTypeRule::where('id', $id);
        $blastTypeRule = QueryBuilder::for($query)
            ->firstOrFail();

        return $blastTypeRule;
    }

    public function createBlastTypeRule($data)
    {
        $blastTypeRule = BlastTypeRule::create([
            'count' => $data->count,
            'duration' => $data->duration,
            'blast_type_id' => $data->blast_type_id,
        ]);

        return $blastTypeRule;
    }

    public function updateBlastTypeRule($data, $id)
    {
        $blastTypeRule = $this->getById($id);
        $blastTypeRule->update([
            'count' => $data->count,
            'duration' => $data->duration,
            'blast_type_id' => $data->blast_type_id,
        ]);

        return $blastTypeRule;
    }

    public function deleteById($id)
    {
        $blastTypeRule = BlastTypeRule::where('id', $id)->firstOrFail();
        $blastTypeRule->delete();
        return true;
    }
}
