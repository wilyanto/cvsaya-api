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

class DatabaseOldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CandidatePositionOldSeeder::class,
            DegreeOldSeeder::class,
            OldEmployeeSeeder::class,
            OldDatabaseSeeder::class,
        ]);
    }
}
