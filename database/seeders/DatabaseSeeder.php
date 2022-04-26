<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            EmploymentTypeSeeder::class,
            CharacterTraitsSeeder::class,
            InterviewResultSeeder::class,
            ReligionSeeder::class,
            MarriageSeeder::class,
            DocumentTypeSeeder::class,
            DegreeSeeder::class,
            CandidatePositions::class,
            CvProfileDetailSeeder::class,
            CvExpectedJobSeeder::class,
            EmployeeSeeder::class,
            CuriculmVitaeSeeder::class,
        ]);
    }
}
