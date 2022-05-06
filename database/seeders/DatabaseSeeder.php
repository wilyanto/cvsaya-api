<?php

namespace Database\Seeders;

use App\Models\AttendanceType;
use App\Models\Shift;
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
            ShiftSeeder::class,
            AttendanceTypeSeeder::class,
            PenaltySeeder::class,
        ]);
    }
}
