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
            // CleanerSeeder::class,
            // OldDatabaseSeeder2::class,
            // NewDatabaseSeeder::class,
            // ReviseEducationSeeder::class,
            // MoveCandidatePositionId::class,
            // CandidatePositionOldSeeder::class,
            // OldEmployeeSeeder::class,
            // UniqueCandidatePositionSeeder::class,
            // RevisePositionIdSeeder::class,
            // CertificationSeeder::class,
            NewCandidateJobSeeder::class,
        ]);
    }
}