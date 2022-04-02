<?php

namespace Database\Seeders;

use App\Http\Controllers\api\v1\MarriageStatusController;
use App\Models\Candidate;
use App\Models\CvProfileDetail;
use App\Models\MarriageStatus;
use App\Models\Religion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use Faker\Factory as Faker;

class ProfileDetailSeeder extends Seeder
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
        $phoneNumber = [123456789,812314122,877214012,899123141,821231251,821000000,831141092,827141241,823411400,823131031];
        $faker = Faker::create('id_ID');
        $marriages = MarriageStatus::pluck('name');
        $religions = Religion::pluck('name');
        if (count($user) == $totalSeed && count($phoneNumber) == $totalSeed) {
            for ($i = 1; $i < $totalSeed; $i++) {
                $name = explode(' ',$faker->name);
                $register_at = date('Y-m-d H:i:s', time());

                Candidate::create([
                    'user_id' => $user[$i],
                    'phone_number' => $user[$i],
                    'name' => $name[0] . ' ' . $name[1],
                    'status' => 3,
                    'country_code' => 62,
                    'register_at' => $register_at,
                ]);

                CvProfileDetail::create([
                    'user_id' => $user[$i],
                    'first_name' => $name[0],
                    'last_name' => $name[1],
                    'gender' => array_rand(['male','female']),
                    'birth_location' => $faker->address,
                    'birth_date' =>  date('Y-m-d',strtotime(time())),
                    'identity_number' => rand(10,100),
                    'reference' => 'seed',
                    'marriage_status_id' => array_rand($marriages->toArray())+1,
                    'religion_id' => array_rand($religions->toArray())+1
                ]);
            }
        }
    }
}
