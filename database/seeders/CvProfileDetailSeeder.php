<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\CvProfileDetail;
use App\Models\User;
use Illuminate\Database\Seeder;

class CvProfileDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CvProfileDetail::create([
            'id' => int::rand(10),
            'user_id' => User::all->random(),


        ]);
    }
}
