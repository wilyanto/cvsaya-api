<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Level;
use App\Models\EmployeeDetail;
use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions = ['HRD'];
        $departments = ['HRD', 'CEO'];
        $levels = ['Staff', 'HRD', 'C-Level'];

        Company::create([
            'id' => 'KADA',
            'name' => 'Kada.id'
        ]);
        foreach ($levels as $level) {
            Level::create([
                'name' => $level,
                'company_id' => 'KADA',
            ]);
        }

        foreach ($departments as $department) {
            Department::create([
                'name' => $department,
                'company_id' => 'KADA',
            ]);
        }

        foreach ($positions as $position) {
            Position::create([
                'name' => $position,
                'department_id' => rand(1, count($departments)),
                'level_id' => rand(1, count($levels)),
                'company_id' => 'KADA',
            ]);
        }

        EmployeeDetail::create([
            'user_id' => 28031,
            'position_id' => rand(1, count($positions)),
            'salary' => 10000000,
        ]);
    }
}
