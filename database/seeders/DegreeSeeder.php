<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Degree;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $degrees = ['S1','S2','S3','SD','SMP','SMA'];
        foreach($degrees as $degree){
            Degree::create([
                'name' => $degree,
            ]);
        }
    }
}
