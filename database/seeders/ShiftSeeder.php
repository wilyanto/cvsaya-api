<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\DocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shift;
use App\Models\ShiftPositions;
use Carbon\Carbon;
use Faker\Factory as Faker;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('en_US');
        $attendanceDocumentType = DocumentType::where('name', 'attendance')->first();
        if (!$attendanceDocumentType) {
            DocumentType::create([
                'name' => 'attendance',
            ]);
        }

        $shiftArr = [
            [
                'name' => 'Office Hour',
                'clock_in' => date('H:i:s', strtotime('07:00:00')),
                'clock_out' => date('H:i:s', strtotime('11:00:00')),
                'start_break' => date('H:i:s', strtotime('14:00:00')),
                'end_break' => date('H:i:s', strtotime('17:00:00')),
                'break_duration' => 60,
            ],
            [
                'name' => 'Night Shift',
                'clock_in' => date('H:i:s', strtotime('17:00:00')),
                'clock_out' => date('H:i:s', strtotime('03:00:00')),
                'start_break' => null,
                'end_break' => null,
                'break_duration' => null,
            ]
        ];

        $companies = Company::all();
        $shifts = [];

        foreach ($companies as $company) {
            foreach ($shiftArr as $shift) {
                array_push(
                    $shifts,
                    [
                        'name' => $shift['name'],
                        'clock_in' => $shift['clock_in'],
                        'clock_out' => $shift['clock_out'],
                        'start_break' => $shift['start_break'],
                        'end_break' => $shift['end_break'],
                        'break_duration' => $shift['break_duration'],
                        'company_id' => $company->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );
            }
        }
    }
}
