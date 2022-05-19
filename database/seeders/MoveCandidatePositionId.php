<?php

namespace Database\Seeders;

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

        $collection = $candidatePositionDummies->filter(function ($item) use ($attribute, $value) {
            return strtolower($item[$attribute]) == strtolower($value);
        });

        DB::transaction(function () use ($candidatePositions, $candidatePositionDummies) {
            foreach ($candidatePositions as $candidatePosition) {
                Log::info(json_encode($candidatePosition));
                Log::info(strtolower($candidatePosition->name));

                $candidatePositionFound = $candidatePositionDummies
                    ->where('name', strtolower($candidatePosition->name))
                    ->first();

                Log::info(json_encode($candidatePositionFound));

                CvExpectedJob::where('expected_position', $candidatePosition->id)
                    ->update([
                        'expected_position' => $candidatePositionFound->id,
                    ]);

                CvExperience::where('position_id', $candidatePosition->id)
                    ->update([
                        'position_id' => $candidatePositionFound->id,
                    ]);

                Log::info('Pos: ' . $candidatePositions->id);
            }
        });
    }
}
