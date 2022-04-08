<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CvDocument;
use App\Models\CvDomicile;
use App\Models\CvProfileDetail;
use App\Models\CvSosmed;
use App\Models\MarriageStatus;
use App\Models\Religion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class CvProfileDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $totalSeed = 50;
        $faker = Faker::create('id_ID');
        for ($i = 1; $i < $totalSeed; $i++) {
            $user = User::all()->random();
            while(CvProfileDetail::where('user_id', $user->id_kustomer)->first()) {
                $user = User::all()->random();
            }
            $name = explode(' ', $faker->name);
            $register_at = date('Y-m-d H:i:s', time());
            $status = [3,5];
            $random = array_rand($status);
            Candidate::create([
                'user_id' => $user->id_kustomer,
                'phone_number' => substr($user->telpon,1),
                'name' => $name[0] . ' ' . $name[1],
                'status' => $status[$random],
                'country_code' => 62,
                'registered_at' => $register_at,
            ]);

            CvProfileDetail::create([
                'user_id' => $user->id_kustomer,
                'first_name' => $name[0],
                'last_name' => $name[1],
                'gender' => array_rand(['male', 'female']),
                'birth_location' => $faker->address,
                'birth_date' =>  $faker->dateTime(),
                'identity_number' => rand(10, 100),
                'reference' => 'seed',
                'marriage_status_id' => MarriageStatus::all()->random()->id,
                'religion_id' => Religion::all()->random()->id
            ]);

            CvDomicile::create([
                'user_id' => $user->id_kustomer,
                'country_id' => 62,
                'province_id' => 12,
                'city_id' => 1275,
                'subdistrict_id' => 1275150,
                'village_id' => 1275150007,
                'address' => $faker->address,
            ]);

            CvSosmed::create([
                'user_id' => $user->id_kustomer,
                'instagram' => $faker->name,
                'tiktok' => $faker->name,
                'youtube' => $faker->name,
                'facebook' => $faker->name,
                'website_url' => $faker->name,
            ]);
        }
    }
}
