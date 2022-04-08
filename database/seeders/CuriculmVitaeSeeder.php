<?php

namespace Database\Seeders;

use App\Models\CandidatePosition;
use App\Models\CvCertification;
use App\Models\CvEducation;
use App\Models\CvExperience;
use App\Models\CvHobby;
use App\Models\CvSpeciality;
use App\Models\Degree;
use App\Models\EmploymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Models\CvProfileDetail;
use App\Models\CvSpecialityCertificate;

class CuriculmVitaeSeeder extends Seeder
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
            while (
                CvExperience::where('user_id', $user->user_id)->first()
                || CvCertification::where('user_id', $user->user_id)->first()
                || CvEducation::where('user_id', $user->user_id)->first()
                || CvSpeciality::where('user_id', $user->user_id)->first()
                || CvHobby::where('user_id', $user->user_id)->first()
            ) {
                $user = CvProfileDetail::all()->random();
            }
            $random = rand(1, 5);

            for ($j = 1; $j <= rand(1, 5); $j++) {
                $startAt = $faker->dateTime();
                $untilAt = $faker->dateTime();
                while($untilAt < $startAt){
                    $untilAt = $faker->dateTime();
                }
                CvExperience::create([
                    'user_id' => $user->user_id,
                    'company_name' => 'S1',
                    'company_location' => 'Testing',
                    'start_at' => $startAt,
                    'until_at' => $untilAt,
                    'jobdesc' => $faker->text(),
                    'resign_reason' => $faker->text(),
                    'reference' => 'seed',
                    'employment_type_id' => EmploymentType::all()->random()->id,
                    'position_id' => CandidatePosition::all()->random()->id,
                ]);
            }

            for ($j = 1; $j <= rand(1, 5); $j++) {
                CvCertification::create([
                    'user_id' => $user->user_id,
                    'name' => $faker->name,
                    'organization' => $faker->name,
                    'issued_at' => Carbon::now(),
                ]);
            }


            for ($j = 1; $j <= rand(1, 5); $j++) {
                CvEducation::create([
                    'user_id' => $user->user_id,
                    'instance' => 'S1',
                    'field_of_study' => 'Testing',
                    'grade' => '4.0',
                    'start_at' => Carbon::now(),
                    'degree_id' => Degree::all()->random()->id,
                ]);
            }

            for ($j = 1; $j <= rand(1, 5); $j++) {
                CvHobby::create([
                    'user_id' => $user->user_id,
                    'name' => $faker->name,
                ]);
            }

            for ($j = 1; $j <= rand(1, 5); $j++) {
                CvSpeciality::create([
                    'user_id' => $user->user_id,
                    'name' => $faker->name,
                ]);
            }
            for ($j = 1; $j <= rand(1, 5); $j++) {
                $certificate = CvCertification::where('user_id',$user->user_id)->get()->random();
                $speciality = CvSpeciality::where('user_id',$user->user_id)->get()->random();
                if(CvSpecialityCertificate::where('certificate_id',$certificate->id)->where('speciality_id',$speciality->id)->first()){
                    continue;
                }else{
                    CvSpecialityCertificate::create([
                        'certificate_id' => $certificate->id,
                        'speciality_id' => $speciality->id,
                    ]);
                }

            }
        }
    }
}
