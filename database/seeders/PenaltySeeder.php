<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceType;
use App\Models\Penalty;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenaltySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = AttendanceType::all();

        foreach($types as $type){
            Penalty::create([
                'name' => 'test '.$type->name ,
                'amount' => 5000,
                'attendance_types_id' => $type->id,
                'company_id' => 'KADA',
                'passing_at' => date('H:i:s',strtotime('00:00:01')),
            ]);
        }
    }
}
