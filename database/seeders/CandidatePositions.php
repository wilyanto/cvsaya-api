<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CandidatePosition;

class CandidatePositions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions = ['desainer','IT Backend','IT Front-End','IT Lead','HRD','General Manager'];
        foreach($positions as $position){
            CandidatePosition::create([
                'name' => $position,
                'validated_at' => date('Y-m-d H:i:s',time()),
            ]);
        }
    }
}
