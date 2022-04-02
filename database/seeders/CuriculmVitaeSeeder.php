<?php

namespace Database\Seeders;

use App\Models\CandidatePosition;
use App\Models\CvCertification;
use App\Models\CvEducation;
use App\Models\CvExperience;
use App\Models\CvHobby;
use App\Models\CvSpeciality;
use App\Models\EmploymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CuriculmVitaeSeeder extends Seeder
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
        $employee = EmploymentType::pluck('id');
        $candidate = CandidatePosition::pluck('id');

        if (count($user) == $totalSeed && count($phoneNumber) == $totalSeed) {
            for ($i = 1; $i < $totalSeed; $i++) {

                CvExperience::create([
                    'user_id' => $user[$i],
                    'company_name' => 'S1',
                    'company_location' => 'Testing',
                    'start_at' => Carbon::now(),
                    'employment_type_id' => array_rand($employee->toArray())+1,
                    'position_id' => array_rand($candidate->toArray())+1,
                ]);

                CvCertification::create([
                    'user_id' => $user[$i],
                    'name' => $faker->name,
                    'organization' => $faker->name,
                    'issued_at' => Carbon::now(),
                ]);

                CvEducation::create([
                    'user_id' => $user[$i],
                    'instance' => 'S1',
                    'field_of_study' => 'Testing',
                    'grade' => '4.0',
                    'start_at' => Carbon::now(),
                ]);


                CvHobby::create([
                    'user_id' => $user[$i],
                    'name' => $faker->name,
                ]);

                CvSpeciality::create([
                    'user_id' => $user[$i],
                    'name' => $faker->name,
                ]);
            }
        }
    }
}
