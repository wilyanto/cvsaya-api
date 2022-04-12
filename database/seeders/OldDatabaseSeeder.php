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
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class OldDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {

            // $users = User::all();
            $users = User::whereRaw('LENGTH(telpon) > 7')->limit(10)->get();
            Log::info('Kustomer : ' . count($users));
            $administrators = DB::connection('cvsaya')->table('administrator')->get();
            Log::info('Administrator : ' . count($administrators));
            $employeeDetails = DB::connection('cvsaya')->table('1employeedetail')->get();
            Log::info('1employeedetail : ' . count($employeeDetails));
            $employees = DB::connection('cvsaya')->table('1employee')->get();
            Log::info('1employee : ' . count($employees));
            $keinginanGajis = DB::connection('cvsaya')->table('1keinginangaji')->get();
            Log::info('1keinginangaji : ' . count($keinginanGajis));
            $pengalamansAll = DB::connection('cvsaya')->table('1pengalaman')->get();
            Log::info('1pengalaman : ' . count($pengalamansAll));
            $candidatePositions = collect(CandidatePosition::all());
            Log::info('Candidate : ' . count($candidatePositions));
            $pendidikansAll = DB::connection('cvsaya')->table('1pendidikan')->get();
            Log::info('1pendidikan : ' . count($pendidikansAll));
            $kualifikasisAll = DB::connection('cvsaya')->table('1kualifikasi')->get();
            Log::info('1kualifikasi : ' . count($kualifikasisAll));
            $hobiesAll = DB::connection('cvsaya')->table('1hoby')->get();
            Log::info('1hoby : ' . count($hobiesAll));
            $degrees = collect(Degree::all());
            Log::info('degree : ' . count($degrees));
            $recentCometsAll = DB::connection('cvsaya')->table('9Recentcomet')->get();
            Log::info('9Recentcomet : ' . count($recentCometsAll));
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
                $administrator = $administrators->where('no_telp', $user->telpon)->first();
                if ($administrator) {
                    $employeeDetail = $employeeDetails->where('idlogin', $administrator->idlogin)->first();
                    if ($employeeDetail) {
                        $employee = $employees->where('idlogin', $administrator->idlogin)->first();
                        $keinginanGaji = $keinginanGajis->where('idlogin', $administrator->idlogin)->first();
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
                            'religion_id' => !empty($employeeDetail->IdAgama) ? $employeeDetail->IdAgama : null,
                            'marriage_status_id' => null,
                        ];
                        $candidate = Candidate::create([
                            'user_id' => $user->id_kustomer,
                            'name' => $user->nama_lengkap,
                            'country_code' => 62,
                            'phone_number' => substr($user->telpon, 1),
                            'status' => 2,
                            'registered_at' => date('Y-m-d H:i:s', strtotime($user->tgl_kus)),
                        ]);
                        $recentComets = $recentCometsAll->where('idlogin', $administrator->idlogin)->all();
                        foreach ($recentComets as $recentComet) {
                            $AdminInterivewer = $administrators->where('idlogin', $recentComet->Usercreate)->first();
                            if ($AdminInterivewer) {
                                $interivewer = $users->where('telpon', $AdminInterivewer->no_telp)->first();
                                if ($interivewer) {
                                    $note[] = [
                                        'candidate_id' => $candidate->id,
                                        'employee_id' => $interivewer->id_kustomer,
                                        'note' => $recentComet->ulasan,
                                    ];
                                }
                            }
                        }

                        $sosmed = [
                            'user_id' => $user->id_kustomer,
                        ];

                        if ($employee) {
                            $domicile = [
                                'user_id' => $user->id_kustomer,
                                'country_id' => 62,
                                'province_id' => $administrator->IDprovinces,
                                'city_id' => 0,
                                'subdistrict_id' => 0,
                                'village_id' => 0,
                                'address' => $employee->alamat
                            ];
                            if ($keinginanGaji) {
                                $candidatePosition = $candidatePositions->where('name', $employee->job)->first();
                                if (!$candidatePosition) {
                                    $candidatePosition = new CandidatePosition();
                                    $candidatePosition->name = $employee->job;
                                    $candidatePosition->save();
                                }
                                $expectedJob = [
                                    'user_id' => $user->id_kustomer,
                                    'expected_salary' => $keinginanGaji->Desired,
                                    'expected_position' => $candidatePosition->id,
                                    'position_reason' => $employee->inginposisi,
                                    'salary_reason' => $keinginanGaji->Ulasan,
                                ];
                            }
                        }
                        $pengalamans = $pengalamansAll->where('idlogin', $administrator->idlogin)->all();
                        foreach ($pengalamans as $pengalaman) {
                            $candidatePosition = $candidatePositions->where('name', $pengalaman->sebagai)->first();
                            if (!$candidatePosition) {
                                $candidatePosition = new CandidatePosition();
                                $candidatePosition->name = $employee->job;
                                $candidatePosition->save();
                            }
                            $cvExperiences[] = [
                                'user_id' => $user->id_kustomer,
                                'employment_type_id' => null,
                                'position_id' => $candidatePosition->id,
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

                        $pendidikans = $pendidikansAll->where('idlogin', $administrator->idlogin)->all();
                        foreach ($pendidikans as $pendidikan) {
                            $degree = $degrees->where('name', 'like', '%' . $pendidikan->pendidikan . '%')->first();
                            if (!$degree) {
                                $degree = new Degree();
                                $degree->name = $pendidikan->pendidikan;
                                $degree->save();
                            }
                            $cvEducation[] = [
                                'user_id' => $user->id_kustomer,
                                'instance' => $pendidikan->asal,
                                'field_of_study' => '-',
                                'degree_id' => $degree->id,
                                'grade' => '-',
                                'started_at' => $pendidikan->Tahun,
                                'ended_at' => $pendidikan->sampai,
                                'description' => '-',
                            ];
                        }

                        $kualifikasis = $kualifikasisAll->where('idlogin', $administrator->idlogin)->all();
                        foreach ($kualifikasis as $kualifikasi) {
                            $cvSpecialities[] = [
                                'user_id' => $user->id_kustomer,
                                'name' => $kualifikasi->kualifikasi,
                            ];
                        }

                        $hobies = $hobiesAll->where('idlogin', $administrator->idlogin)->all();
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
                Log::info('Index ke: ' . $index);
            }
            CvProfileDetail::insert($profileDetails);
            CvSosmed::insert($sosmeds);
            CvDomicile::insert($domiciles);
            CvExpectedJob::insert($expectedJobs);
            CvHobby::insert($cvHobbies);
            CvSpeciality::insert($cvSpecialities);
            CvExperience::insert($cvExperiences);
            CvEducation::insert($cvEducation);
        } catch (Exception $e) {
            Log::info('Error :' . $e);
        }
    }
}
