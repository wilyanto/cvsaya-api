<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CvExpectedJob;
use App\Models\CandidatePosition;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class CvExpectedJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $totalSeed = 10;
        $user = [23736, 23735, 23734, 23733, 23732, 23731, 23730, 237329, 237328, 237327];
        $phoneNumber = [123456789, 812314122, 877214012, 899123141, 821231251, 821000000, 831141092, 827141241, 823411400, 823131031];
        $faker = Faker::create('id_ID');
        $position = CandidatePosition::pluck('id');

        if (count($user) == $totalSeed && count($phoneNumber) == $totalSeed) {
            for ($i = 1; $i < $totalSeed; $i++) {

                CvExpectedJob::create([
                    'user_id' => $user[$i],
                    'expected_salary' => rand(1000, 1000000),
                    'expected_position' => array_rand($position->toArray()) + 1,
                    'position_reason' => Str::random(50),
                    'salary_reason' => Str::random(50),
                ]);
            }
        }
    }
}
