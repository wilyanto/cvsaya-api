<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Level;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class LevelSeeder extends Seeder
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
        $levels = [];
        for ($i = 1; $i < $totalSeed; $i++) {
            array_push(
                $levels,
                [
                    'name' => $faker->firstNameMale(),
                    'company_id' => Company::all()->random()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
        Level::insert($levels);
    }
}
