<?php

namespace Database\Seeders;

use App\Models\EmploymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employeeTypes = ['Full-Time', 'Part-time', 'Self-employed', 'Freelance', 'Contract', 'Intership', 'Apprenticeship', 'Seasonal'];

        foreach ($employeeTypes as $employeeType) {
            EmploymentType::create([
                'name' => $employeeType,
            ]);
        }
    }
}
