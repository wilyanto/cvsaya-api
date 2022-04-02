<?php

namespace Database\Seeders;

use App\Models\CvDocument;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CvDocumentsSeeder extends Seeder
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

        if (count($user) == $totalSeed && count($phoneNumber) == $totalSeed) {
            for ($i = 1; $i < $totalSeed; $i++) {
                CvDocument::create([
                    'user_id' => $user[$i],
                    'identity_card' => 22,
                    'front_selfie' => 11,
                    'left_selfie' => 14,
                    'right_selfie' => 21,
                ]);
            }
        }
    }
}
