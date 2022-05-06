<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DepartmentSeeder extends Seeder
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
        $departments = [];
        for ($i = 1; $i < $totalSeed; $i++) {
            array_push(
                $departments,
                [
                    'name' => $faker->firstNameMale(),
                    'company_id' => Company::all()->random()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
        Department::insert($departments);
    }
}
