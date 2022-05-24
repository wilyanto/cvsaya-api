<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CandidatePosition;
use App\Models\CvExpectedJob;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewCandidateJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $candidates = Candidate::all();
        $candidatePositions = CandidatePosition::all();
        $expectedJobs = CvExpectedJob::all();

        $newExpectedJobs = [];
        $newExperiences = [];
        $newCandidatePositions = [];

        $dummyCandidates = DB::connection('dummy-cvsaya')->table('candidates')->where('id', '>=', 4008)->get([
            'id', 'user_id', 'phone_number'
        ]);
        $dummyCandidatePositions = DB::connection('dummy-cvsaya')->table('candidate_positions')->get([
            'id', 'name'
        ]);
        $dummyExpectedJobs = DB::connection('dummy-cvsaya')->table('cv_expected_jobs')->get([
            'id', 'user_id', 'expected_position', 'expected_salary', 'position_reason', 'salary_reason', 'created_at', 'updated_at'
        ]);
        $dummyExperiences = DB::connection('dummy-cvsaya')->table('cv_experiences')->get([
            'id', 'user_id', 'position_id'
        ]);

        DB::transaction(function () use (
            $dummyCandidates,
            $dummyExpectedJobs,
            $dummyCandidatePositions,
            $candidates,
            $candidatePositions,
            $expectedJobs,
            $newExpectedJobs,
            $newExperiences,
            $newCandidatePositions,
        ) {
            foreach ($dummyCandidates as $dummyCandidate) {
                Log::info('candidate id: ' . $dummyCandidate->id);
                $candidateFound = $candidates->where('user_id', $dummyCandidate->user_id)->first();

                if ($candidateFound) {
                    $dummyExpectedJobFound = $dummyExpectedJobs->where('user_id', $dummyCandidate->user_id)->first();
                    if ($dummyExpectedJobFound) {
                        $dummyCandidatePositionFound = $dummyCandidatePositions->where('id', $dummyExpectedJobFound->expected_position)->first();
                        if ($dummyCandidatePositionFound) {
                            $candidatePosition = collect($candidatePositions)->filter(function ($item) use ($dummyCandidatePositionFound) {
                                return trim(strtolower($item->name)) == trim(strtolower($dummyCandidatePositionFound->name));
                            })->first();

                            if ($candidatePosition) {
                                $candidatePositionId = $candidatePosition->id;
                            } else {
                                $candidatePositionId = count($candidatePositions) + 1;
                                $newCandidatePosition = [
                                    'id' => $candidatePositionId,
                                    'name' => ucwords($dummyCandidatePositionFound->name),
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                                // array_push($newCandidatePositions, $newCandidatePosition);
                                $candidatePositions->push((object) $newCandidatePosition);
                                CandidatePosition::create($newCandidatePosition);
                            }


                            Log::info('candidate pos: ' . $candidatePositionId);
                            $expectedJobFound = $expectedJobs->where('candidate_id', $candidateFound->id)->first();
                            if ($expectedJobFound) {
                                Log::info('update pos: ' . $candidatePositionId);
                                $expectedJobFound->update([
                                    'expected_position' => $candidatePositionId,
                                ]);
                            } else {
                                Log::info('create job: ' . $candidatePositionId);
                                array_push($newExpectedJobs, [
                                    'candidate_id' => $candidateFound->id,
                                    'expected_position' => $candidatePositionId,
                                    'expected_salary' => $dummyExpectedJobFound->expected_salary,
                                    'position_reason' => $dummyExpectedJobFound->position_reason,
                                    'salary_reason' => $dummyExpectedJobFound->salary_reason,
                                    'created_at' => $dummyExpectedJobFound->created_at,
                                    'updated_at' => $dummyExpectedJobFound->updated_at,
                                ]);
                            }
                        }
                    }
                }
            }

            Log::info('candidate position: ' . count($newCandidatePositions));
            Log::info('expected jobs: ' . count($newExpectedJobs));

            $chunckedCandidatePositions = array_chunk($newCandidatePositions, 1000);
            foreach ($chunckedCandidatePositions as $candidatePositions) {
                CandidatePosition::insert($candidatePositions);
            }
            $chunckedExpectedJobs = array_chunk($newExpectedJobs, 1000);
            foreach ($chunckedExpectedJobs as $expectedJobs) {
                CvExpectedJob::insert($expectedJobs);
            }
            // $chunckedExperiences = array_chunk($newExperiences, 1000);
            // foreach ($chunckedExperiences as $experiences) {
            //     CvExperience::insert($experiences);
            // }
        });
    }
}
