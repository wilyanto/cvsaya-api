<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
