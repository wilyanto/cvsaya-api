<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CandidatePosition;
use App\Models\CvExpectedJob;
use App\Models\CvExperience;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DeleteCandidatePositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expectedPositions = CvExpectedJob::all();
        $experiences = CvExperience::all();
        $candidates = CandidatePosition::where('name', ' ')->get();
        foreach ($candidates as $candidate) {
            $users = $expectedPositions->where('expected_position', $candidate->id)->all();
            foreach($users as $user){
                $user->expected_position = null;
                $user->save();
            }

            $users = $experiences->where('position_id', $candidate->id)->all();
            foreach($users as $user){
                $user->position_id = null;
                $user->save();
            }

        }
        foreach ($candidates as $candidate) {
            $candidate->delete();
        }
    }
}
