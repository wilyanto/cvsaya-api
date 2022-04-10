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


class MigrationOtherDBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $users = User::all();
        $users = User::whereRaw('LENGTH(telpon) > 7')->get();
        Log::info('Kustomer : ' . count($users));
        $administrators = DB::connection('cvsaya')->table('administrator')->get();
        Log::info('Administrator : ' . count($administrators));
        // $users = User::whereNotNull('telpon')->get();
        $profileDetails = [];
        $cvSpecialities = [];
        $cvExperiences = [];
        $cvEducation = [];
        $candidates = [];
        $sosmeds = [];
        $domiciles = [];
        $expectedJobs = [];
        foreach ($users as $index => $user) {
            // $administrator = $administrators->where('no_telp', $user->telpon)->first();
            $administrator = DB::connection('cvsaya')->table('administrator')->where('no_telp', $user->telpon)->first();
            if ($administrator) {
                $employeeDetail = DB::connection('cvsaya')->table('1employeedetail')->where('idlogin', $administrator->idlogin)->first();
                if ($employeeDetail) {
                    $employee = DB::connection('cvsaya')->table('1employee')->where('idlogin', $administrator->idlogin)->first();
                    $keinginanGaji = DB::connection('cvsaya')->table('1keinginangaji')->where('idlogin', $administrator->idlogin)->first();
                    $name = explode(" ", $administrator->nama_lengkap);
                    $profileDetail = [
                        'user_id' => $user->id_kustomer,
                        'first_name' => $name[0],
                        'last_name' => substr($administrator->nama_lengkap, strlen($name[0]) + 1),
                        'birth_location' => $employeeDetail->tpl,
                        'birth_date' => date('Y-m-d H:i:s', strtotime($employeeDetail->ttl)),
                        'gender' => $employeeDetail->jk == 'P' ? 'Perempuan' : ($employeeDetail->jk == 'L' ? 'Laki - Laki' : 'M'),
                        'identity_number' => $administrator->id_ktp,
                        'reference' => $employeeDetail->referensi,
                        'religion_id' => $employeeDetail->IdAgama,
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

                    $sosmed = [
                        'user_id' => $user->id_kustomer,
                    ];

                    if($employee){
                        $domicile = [
                            'user_id' => $user->id_kustomer,
                            'country_id' => 62,
                            'province_id' => $administrator->IDprovinces,
                            'city_id' => 0,
                            'sub_district_id' => 0,
                            'village_id' => 0,
                            'address' => $employee->alamat
                        ];
                        if($keinginanGaji){
                            $expectedJob = [
                                'user_id' => $user->id_kustomer,
                                'expected_salary' => $keinginanGaji->Desired,
                                'expected_job' => $employee->job,
                                'position_reason' => $employee->inginposisi,
                                'salary_reason' => $keinginanGaji->Ulasan,
                            ];
                        }
                    }
                    $pengalamans = DB::connection('cvsaya')->table('1pengalaman')->where('idlogin', $administrator->idlogin)->get();
                    foreach ($pengalamans as $pengalaman) {
                        $candidatePosition = CandidatePosition::where('name', $pengalaman->sebagai)->first();
                        if (!$candidatePosition) {
                            $candidatePosition = new CandidatePosition();
                            $candidatePosition->name = $pengalaman->sebagai;
                            $candidatePosition->save();
                        }
                        $cvExperiences[] = [
                            'user_id' => $user->id_kustomer,
                            'employment_type_id ' => null,
                            'position_id ' => $candidatePosition->id,
                            'company_name' => $pengalaman->perusahaan,
                            'company_location' => null,
                            'jobdesc' => $pengalaman->sebagai,
                            'resign_reason' => $pengalaman->resign,
                            'reference' => null,
                            'previous_salary' => 0,
                            'started_at' => date('Y-m-d H:i:s', strtotime($pengalaman->tahun)),
                            'ended_at' => date('Y-m-d H:i:s', strtotime($pengalaman->sampai)),
                        ];
                    }

                    $pendidikans = DB::connection('cvsaya')->table('1pendidikan')->where('idlogin', $administrator->idlogin)->get();
                    foreach ($pendidikans as $pendidikan) {
                        $degreeId = Degree::where('name', 'like', '%' . $pendidikan->pendidikan . '%')->first();
                        if (!$degreeId) {
                            $degreeId = new Degree();
                            $degreeId->name = $pendidikan->pendidikan;
                            $degreeId->save();
                        }
                        $cvEducation[] = [
                            'user_id' => $user->id_kustomer,
                            'instance' => $pendidikan->asal,
                            'field_of_study' => '-',
                            'degree_id' => $degreeId,
                            'grade' => '-',
                            'started_at' => $pendidikan->Tahun,
                            'ended_at' => $pendidikan->sampai,
                            'description' => '-',
                        ];
                    }

                    $kualifikasis = DB::connection('cvsaya')->table('1kualifikasi')->where('idlogin', $administrator->idlogin)->get();
                    foreach ($kualifikasis as $kualifikasi) {
                        $cvSpecialities[] = [
                            'user_id' => $user->id_kustomer,
                            'name' => $kualifikasi->kualifikasi,
                        ];
                    }

                    $hobies = DB::connection('cvsaya')->table('1hoby')->where('idlogin', $administrator->idlogin)->get();
                    foreach ($hobies as $hoby) {
                        $cvHobbies[] = [
                            'user_id' => $user->id_kustomer,
                            'name' => $hoby->hoby,
                        ];
                    }
                    if ($profileDetail) {
                        $profileDetails[] = $profileDetail;
                    }
                    if ($candidate) {
                        $candidates[] = $candidate;
                    }
                    if ($sosmed) {
                        $sosmeds[] = $sosmed;
                    }
                    if ($domicile) {
                        $domiciles[] = $domicile;
                    }
                    if ($expectedJob) {
                        $expectedJobs[] = $expectedJob;
                    }
                }
            }
            Log::info('Index ke: '.$index);
        }
        CvProfileDetail::create([$profileDetails]);
        Candidate::create([$candidates]);
        CvSosmed::create([$sosmeds]);
        CvDomicile::create([$domiciles]);
        CvExpectedJob::create([$expectedJobs]);
        CvHobby::create([$cvHobbies]);
        CvSpeciality::create([$cvSpecialities]);
        CvExperience::create([$cvExperiences]);
        CvEducation::create([$pendidikans]);
    }
}
