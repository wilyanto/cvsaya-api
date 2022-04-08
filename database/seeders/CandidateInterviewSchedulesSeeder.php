<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Candidate;
use App\Models\CandidateInterviewSchedule;
use App\Models\CharacterTrait;
use App\Models\EmployeeDetail;
use App\Models\InterviewResult;
use App\Models\CandidateInterviewSchedulesCharacterTrait;

class CandidateInterviewSchedulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $candidates = Candidate::where('status', 5)->get();
        foreach ($candidates as $candidate) {
            $random = rand(1, 5);
            for ($i = 1; $i <= $random; $i++) {
                if ($i > 1) {
                    $isWithSchedule = rand(0, 1);
                    $isWithResult = rand(0, 1);

                    $schedule = CandidateInterviewSchedule::create([
                        'candidate_id' => $candidate->id,
                        'interviewed_at' => $isWithResult ? ($isWithSchedule ? $faker->dateTime() : null) : null,
                        'interviewed_by' => EmployeeDetail::all()->random()->id,
                        'result_id' => $isWithResult ? InterviewResult::all()->random()->id : null,
                        'rejected_at' => $isWithResult ? null : $faker->dateTime(),
                        'note' =>  $isWithResult ? $faker->text() : null,
                    ]);
                    if (!$isWithResult) {
                        CandidateInterviewSchedulesCharacterTrait::create([
                            'candidate_interview_schedule_id' => $schedule->id,
                            'character_trait_id' => CharacterTrait::all()->random()->id,
                        ]);
                    }
                }
                if (!rand(0, 1)) {
                    $schedule = CandidateInterviewSchedule::create([
                        'candidate_id' => $candidate->id,
                        'interviewed_by' => EmployeeDetail::all()->random()->id,
                    ]);
                }
            }
        }
    }
}
