<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Level;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $totalSeed = 100;
        $faker = Faker::create('en_US');
        $positions = [];
        $companies = Company::all();
        $departments = Department::all();
        $levels = Level::all();
        for ($i = 0; $i < $totalSeed; $i++) {
            array_push(
                $positions,
                [
                    'name' => $faker->firstNameMale(),
                    'company_id' => $companies->random()->id,
                    'department_id' => $departments->random()->id,
                    'level_id' => $levels->random()->id,
                    'priority' => rand(1, 20),
                    'remaining_slot' => rand(1, 5),
                    'min_salary' => rand(1000000, 5000000),
                    'max_salary' => rand(3000000, 10000000),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
        Position::insert($positions);
    }
}
