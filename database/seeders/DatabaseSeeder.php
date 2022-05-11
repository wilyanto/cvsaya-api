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
            // CompanySeeder::class,
            // DepartmentSeeder::class,
            // LevelSeeder::class,
            // PositionSeeder::class,
            // ShiftSeeder::class,
            PenaltySeeder::class,
        ]);
    }
}