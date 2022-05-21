<?php

namespace Database\Seeders;

use App\Models\CandidatePosition;
use App\Models\CvExpectedJob;
use App\Models\CvExperience;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MoveCandidatePositionId extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $candidatePositions = DB::table('candidate_positions')->get();
        $candidatePositionDummies = DB::table('candidate_positions_dummy')->get();

        // Log::info(json_encode($candidatePositionDummies->where('name', 'Accounting')->first()));

        DB::transaction(function () use ($candidatePositions, $candidatePositionDummies) {
            foreach ($candidatePositions as $candidatePosition) {
                Log::info(json_encode($candidatePosition));
                Log::info(strtolower($candidatePosition->name));

                // $candidatePositionFound = $candidatePositionDummies
                //     ->where('name', strtolower($candidatePosition->name))
                //     ->first();

                $candidatePositionFound = $candidatePositionDummies->filter(function ($item) use ($candidatePosition) {
                    return trim(strtolower($item->name)) == trim(strtolower($candidatePosition->name));
                })->first();

                Log::info(json_encode($candidatePositionFound));

                if (!$candidatePositionFound) {
                    $candidatePositionId = DB::table('candidate_positions_dummy')->insertGetId([
                        'name' => ucwords($candidatePosition->name),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $newCandidatePositionId = $candidatePositionId;
                } else {
                    $newCandidatePositionId = $candidatePositionFound->id;
                }

                CvExpectedJob::where('expected_position', $candidatePosition->id)
                    ->update([
                        'expected_position' => $newCandidatePositionId,
                    ]);

                CvExperience::where('position_id', $candidatePosition->id)
                    ->update([
                        'position_id' => $newCandidatePositionId,
                    ]);

                Log::info('Pos: ' . $newCandidatePositionId);
            }
        });
    }
}
