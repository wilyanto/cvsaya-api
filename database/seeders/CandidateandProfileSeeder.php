<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CandidatePosition;
use App\Models\CvDomicile;
use App\Models\CvEducation;
use App\Models\CvExpectedJob;
use App\Models\CvExperience;
use App\Models\CvHobby;
use App\Models\CvProfileDetail;
use App\Models\CvSosmed;
use App\Models\CvSpeciality;
use App\Models\Degree;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class CandidateandProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $users = User::all();
        $users = User::whereRaw('LENGTH(telpon) > 7')->take(10)->get();
        Log::info('Kustomer : ' . count($users));
        $administrators = DB::connection('cvsaya')->table('administrator')->get();
        Log::info('Administrator : ' . count($administrators));
        $profileDetails = [];
        $candidates = [];
        $sosmeds = [];
        $employeeDetails = DB::connection('cvsaya')->table('1employeedetail')->get();
        foreach ($users as $index => $user) {
            $administrator = $administrators->where('no_telp', $user->telpon)->first();
            // $administrator = DB::connection('cvsaya')->table('administrator')->where('no_telp', $user->telpon)->first();
            if ($administrator) {
                $employeeDetail = $employeeDetails->where('idlogin', $administrator->idlogin)->first();
                if ($employeeDetail) {
                    $name = explode(" ", $administrator->nama_lengkap);
                    $profileDetail = [
                        'user_id' => $user->id_kustomer,
                        'first_name' => $name[0],
                        'last_name' => substr($administrator->nama_lengkap, strlen($name[0]) + 1),
                        'birth_location' => $employeeDetail->tpl,
                        'birth_date' => date('Y-m-d H:i:s', strtotime($employeeDetail->ttl)),
                        'gender' => $employeeDetail->jk == 'P' ? 'Perempuan' : ($employeeDetail->jk == 'L' ? 'Laki - Laki' : 'M'),
                        'identity_number' => $administrator->id_ktp,
                        'reference' => !empty($employeeDetail->referensi) ? $employeeDetail->referensi : null,
                        'religion_id' => $employeeDetail->IdAgama ? $employeeDetail->IdAgama : null,
                        'marriage_status_id' => 1,
                    ];
                    $candidate = [
                        'user_id' => $user->id_kustomer,
                        'name' => $user->nama_lengkap,
                        'country_code' => 62,
                        'phone_number' => substr($user->telpon, 1),
                        'status' => 2,
                        'registered_at' => date('Y-m-d H:i:s', strtotime($user->tgl_kus)),
                    ];

                    if ($profileDetail) {
                        $profileDetails[] = $profileDetail;
                    }
                    if ($candidate) {
                        $candidates[] = $candidate;
                    }

                }
            }
            Log::info('Index ke: '.$index);
        }
        Log::info($profileDetails);
        Log::info($candidates);
        CvProfileDetail::insert($profileDetails);
        Candidate::insert($candidates);
        CvSosmed::insert($sosmeds);
    }
}
