<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\CvProfileDetail;
use App\Models\Department;
use App\Models\Level;
use App\Models\Employee;
use App\Models\User;
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

        $phoneNumbers = ['081228859658', '081260355756', '081360016097', '0895347272593', '082166236702', '081342110089'];
        foreach ($phoneNumbers as $phoneNumber) {
            $user = User::where('telpon', $phoneNumber)->first();
            if ($user) {
                Employee::create([
                    'user_id' => $user->id_kustomer,
                    'position_id' => rand(1, count($positions)),
                ]);

                $candidate = Candidate::where('phone_number',substr($phoneNumber,1))->first();
                if(!$candidate){
                    Candidate::create([
                        'user_id' => $user->id_kustomer,
                        'name' => $user->nama_lengkap,
                        'status' => 9,
                        'phone_number' => substr($phoneNumber,1),
                        'country_code' => 62,
                    ]);
                }else{
                    Candidate::where('phone_number',substr($phoneNumber,1))->update([
                        'user_id' => $user->id_kustomer,
                        'name' => $user->nama_lengkap,
                        'status' => 9,
                        'phone_number' => substr($phoneNumber,1),
                        'country_code' => 62,
                    ]);
                }

                $profileDetail = CvProfileDetail::where('user_id',$user->id_kustomer)->first();
                if(!$profileDetail){
                    CvProfileDetail::create([
                        'first_name' => $user->nama_lengkap,
                        'last_name' => 'employee',
                        'user_id' => $user->id_kustomer,
                    ]);
                }
            }


        }
    }
}
