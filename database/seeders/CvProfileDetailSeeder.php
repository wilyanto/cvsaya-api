<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CvProfileDetail;
use Illuminate\Support\Facades\DB;

class CvProfileDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = \Faker\Factory::create();
        $valueSeed = 10;
        $user  = User::all()->random()->id_kustomer;
        for ($i = 1; $i <= $valueSeed; $i++) {
            CvProfileDetail::create([
                'user_id' => $user,
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'birth_location' => $faker->cityPrefix(),
                'birth_date' => $faker->date($format = 'D-m-y', $max = '2012', $min = '1990'),
                'gender' => array_rand(array('male', 'female]')),
                'identity_number' => random_int(10, 12),
                'religion' => array_rand(array(
                    'Buddha',
                    'Islam',
                    'Hindu',
                    'Kristen',
                    'Konghucu',
                )),
                'married' => array_rand(array(
                    'menikah',
                    'tidak menikah'
                )),
                'reference' => array_rand(array(
                    'WA',
                    'Line',
                    'Instagram',
                    'Facebook',
                    'Twitter',
                    'Linkin'
                )),
            ]);

            CvProfileDetail::create([
                'user_id' => $user,
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'birth_location' => $faker->cityPrefix(),
                'birth_date' => $faker->date($format = 'D-m-y', $max = '2012', $min = '1990'),
                'gender' => array_rand(array('male', 'female]')),
                'identity_number' => random_int(10, 12),
                'religion' => array_rand(array(
                    'Buddha',
                    'Islam',
                    'Hindu',
                    'Kristen',
                    'Konghucu',
                )),
                'married' => array_rand(array(
                    'menikah',
                    'tidak menikah'
                )),
                'reference' => array_rand(array(
                    'WA',
                    'Line',
                    'Instagram',
                    'Facebook',
                    'Twitter',
                    'Linkin'
                )),
            ]);
        }
    }
}
