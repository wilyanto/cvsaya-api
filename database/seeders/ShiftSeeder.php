<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shift;
use App\Models\ShiftPositions;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['Office'];
        foreach ($names as $name) {
            $shift = Shift::create([
                'name' => $name,
                'clock_in' => date('H:i:s', strtotime('07:00:00')),
                'clock_out' => date('H:i:s', strtotime('12:00:00')),
                'break_started_at' => date('H:i:s', strtotime('12:55:00')),
                'break_ended_at' => date('H:i:s', strtotime('17:00:00')),
                'break_duration' => 1,
                'company_id' => 'Kada',
            ]);
        }

        for ($i = 1; $i <= 6; $i++) {
            ShiftPositions::create([
                'shift_id' => $shift->id,
                'position_id' => 1,
                'day' => $i
            ]);
        }
    }
}
