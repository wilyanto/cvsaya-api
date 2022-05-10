<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Penalty;
use App\Enums\EnumPenaltyType;
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
        $companies = Company::all();
        $penaltySchedules = [
            [
                'name' => 'Telat 1 menit',
                'amount' => 5000,
                'lateness' => 1,
                'attendance_type' => EnumPenaltyType::ClockIn->value,
            ],
            [
                'name' => 'Telat 5 menit',
                'amount' => 10000,
                'lateness' => 5,
                'attendance_type' => EnumPenaltyType::ClockIn->value,
            ],
            [
                'name' => 'Telat 10 menit',
                'amount' => 15000,
                'lateness' => 10,
                'attendance_type' => EnumPenaltyType::ClockIn->value,
            ],
            [
                'name' => 'Telat 20 menit',
                'amount' => 25000,
                'lateness' => 20,
                'attendance_type' => EnumPenaltyType::ClockIn->value,
            ],
            [
                'name' => 'Tidak logout',
                'amount' => 70000,
                'lateness' => null,
                'attendance_type' => EnumPenaltyType::ClockOut->value,
            ],
            [
                'name' => 'Tidak Absensi Makan',
                'amount' => 15000,
                'lateness' => null,
                'attendance_type' => EnumPenaltyType::BreakTime->value,
            ],
            [
                'name' => 'Lewat Jam makan',
                'amount' => 15000,
                'lateness' => 1,
                'attendance_type' => EnumPenaltyType::BreakTime->value,
            ],
        ];

        $penalties = [];
        foreach ($companies as $company) {
            foreach ($penaltySchedules as $penaltySchedule) {
                array_push(
                    $penalties,
                    [
                        'name' => $penaltySchedule['name'],
                        'amount' => $penaltySchedule['amount'],
                        'lateness' => $penaltySchedule['lateness'],
                        'attendance_type' => $penaltySchedule['attendance_type'],
                        'company_id' => $company->id,
                    ]
                );
            }
        }

        Penalty::insert($penalties);
    }
}