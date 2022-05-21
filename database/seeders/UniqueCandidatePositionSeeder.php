<?php

namespace Database\Seeders;

use App\Models\CandidatePosition;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UniqueCandidatePositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dummyCandidatePositions = DB::table('candidate_positions_dummy')->get();

        $candidatePositions = [];
        foreach ($dummyCandidatePositions as $dummyCandidatePosition) {
            $candidatePositionFound = collect($candidatePositions)->filter(function ($item) use ($dummyCandidatePosition) {
                return trim(strtolower($item['name'])) == trim(strtolower($dummyCandidatePosition->name));
            })->first();

            Log::info(!$candidatePositionFound);

            if (!$candidatePositionFound)
                array_push($candidatePositions, [
                    'name' => $dummyCandidatePosition->name,
                    'created_at' => $dummyCandidatePosition->created_at,
                    'updated_at' => $dummyCandidatePosition->updated_at,
                ]);
        }

        try {
            DB::transaction(function () use (
                $candidatePositions,
            ) {
                $chunckedCandidates = array_chunk($candidatePositions, 1000);
                foreach ($chunckedCandidates as $candidates) {
                    CandidatePosition::insert($candidates);
                }
            });
        } catch (Exception $e) {
            Log::info($e);
        }
    }
}
