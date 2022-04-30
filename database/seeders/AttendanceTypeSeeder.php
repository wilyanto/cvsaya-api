<?php

namespace Database\Seeders;

use App\Models\AttendanceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['clock_in','clock_out','break_started_at','break_ended_at'];

        foreach($types as $type){
            AttendanceType::create([
                'name' => $type,
            ]);
        }
    }
}
