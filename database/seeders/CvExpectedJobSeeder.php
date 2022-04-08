<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CvExpectedJob;
use App\Models\CandidatePosition;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\CvProfileDetail;

class CvExpectedJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $totalSeed = 45;
        $faker = Faker::create('id_ID');
        for ($i = 1; $i < $totalSeed; $i++) {
            $user = CvProfileDetail::all()->random();
            while(CvExpectedJob::where('user_id', $user->user_id)->first()) {
                $user = CvProfileDetail::all()->random();
            }
            CvExpectedJob::create([
                'user_id' => $user->user_id,
                'expected_salary' => rand(1000, 1000000),
                'expected_position' => CandidatePosition::all()->random()->id,
                'position_reason' => $faker->text(),
                'salary_reason' => $faker->text(),
            ]);
        }
    }
}
