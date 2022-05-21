<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CandidateNote;
use App\Models\CandidatePosition;
use App\Models\CvDocument;
use App\Models\CvDomicile;
use App\Models\CvEducation;
use App\Models\CvExpectedJob;
use App\Models\CvExperience;
use App\Models\CvHobby;
use App\Models\CvProfileDetail;
use App\Models\CvSosmed;
use App\Models\CvSpeciality;
use App\Models\Degree;
use App\Models\Document;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



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
            $lowerBoundaryId = 1;
            $upperBoundaryId = 5000;
            $users = User::whereRaw('LENGTH(telpon) > 7')->get();
            Log::info('Kustomer : ' . count($users));
            $administrators = DB::connection('cvsaya')->table('administrator')->whereRaw('LENGTH(no_telp) > 7')
                ->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                    'idlogin', 'username', 'id_ktp', 'nama_lengkap', 'no_telp', 'TglPost', 'IDprovinces'
                ]);
            Log::info('Administrator : ' . count($administrators));

            // Filter intersected users
            $userPhoneNumbers = $users->pluck('telpon');
            $administratorPhoneNumbers = $administrators->pluck('no_telp');
            $intersectedPhoneNumbers = array_intersect($administratorPhoneNumbers->toArray(), $userPhoneNumbers->toArray());
            $filteredUsers = $users->whereIn('telpon', $intersectedPhoneNumbers);

            $oldEmployeeDetails = DB::connection('cvsaya')->table('1employeedetail')->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'idlogin', 'ttl', 'tpl', 'jk', 'referensi', 'TglPost',
            ]);
            Log::info('1employeedetail : ' . count($oldEmployeeDetails));
            $oldEmployees = DB::connection('cvsaya')->table('1employee')->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'idlogin', 'inginposisi', 'gambar', 'alamat', 'TglPost', 'job', 'gambar'
            ]);
            Log::info('1employee : ' . count($oldEmployees));
            $keinginanGajis = DB::connection('cvsaya')->table('1keinginangaji')->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'idlogin', 'Desired', 'Ulasan',
            ]);
            Log::info('1keinginangaji : ' . count($keinginanGajis));
            $pengalamansAll = DB::connection('cvsaya')->table('1pengalaman')->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'idlogin', 'sebagai', 'tahun', 'sampai', 'perusahaan', 'resign', 'gaji'
            ]);
            Log::info('1pengalaman : ' . count($pengalamansAll));
            $candidatePositions = CandidatePosition::all([
                'id', 'name',
            ]);
            Log::info('Candidate : ' . count($candidatePositions));
            $pendidikansAll = DB::connection('cvsaya')->table('1pendidikan')->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'idlogin', 'Tahun', 'sampai', 'asal', 'pendidikan'
            ]);
            Log::info('1pendidikan : ' . count($pendidikansAll));
            $kualifikasisAll = DB::connection('cvsaya')->table('1kualifikasi')->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'idlogin', 'kualifikasi', 'TglPost',
            ]);
            Log::info('1kualifikasi : ' . count($kualifikasisAll));
            $hobiesAll = DB::connection('cvsaya')->table('1hoby')->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'idlogin', 'hoby',
            ]);
            Log::info('1hoby : ' . count($hobiesAll));
            $degrees = Degree::all();
            Log::info('degree : ' . count($degrees));
            $oldRecentComments = DB::connection('cvsaya')->table('9Recentcomet')->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'idlogin', 'ulasan', 'TglPost', 'Usercreate'
            ]);
            Log::info('9Recentcomet : ' . count($oldRecentComments));
            $employees = Employee::all();

            // $userPhoneNumbers = $users->pluck('telpon');
            // $administratorPhoneNumbers = $administrators->pluck('no_telp');
            // Log::info($userPhoneNumbers);
            // Log::info($administratorPhoneNumbers);
            // Log::info('Kada: ' . count($userPhoneNumbers->toArray()));
            // Log::info('CVsaya: ' . count($administratorPhoneNumbers->toArray()));
            // Log::info('Intersect: ' . count(array_intersect($administratorPhoneNumbers->toArray(), $userPhoneNumbers->toArray())));
            // Log::info('Non Intersect: ' . count(array_diff($administratorPhoneNumbers->toArray(), $userPhoneNumbers->toArray())));
            // Log::info('Non Intersect numbers: ' . json_encode(array_diff($administratorPhoneNumbers->toArray(), $userPhoneNumbers->toArray())));

            // foreach (collect($administrators)->chunk(ceil(count(collect($administrators)) / 3))->all() as $index => $chunckedAdmins) {

            $documents = [];
            $cvDocuments = [];
            $profileDetails = [];
            $cvSpecialities = [];
            $cvExperiences = [];
            $cvEducations = [];
            $candidates = [];
            $sosmeds = [];
            $domiciles = [];
            $expectedJobs = [];
            $cvHobbies = [];
            $newCandidatePositions = [];
            $candidates = [];
            $candidateNotes = [];

            // Log::info(count($user2s));
            // if ($index == 1) {
            $candidateId = Candidate::all()->count();
            $lastCandidatePositionId = count($candidatePositions);
            foreach ($administrators as $admin) {
                $kustomer = $filteredUsers->where('telpon', $admin->no_telp)->first();
                $oldEmployeeDetail = $oldEmployeeDetails->where('idlogin', $admin->idlogin)->first();
                // Insert candidates
                $candidateId++;
                array_push($candidates, [
                    'id' => $candidateId,
                    'user_id' => $kustomer != null ? $kustomer->id_kustomer : null,
                    'name' => $admin->nama_lengkap,
                    'country_code' => 62,
                    'phone_number' => substr($admin->no_telp, 1),
                    'status' => 3,
                    'registered_at' => Carbon::createFromTimestamp(strtotime($admin->TglPost)),
                    'created_at' => Carbon::createFromTimestamp(strtotime($admin->TglPost)),
                    'updated_at' => Carbon::createFromTimestamp(strtotime($admin->TglPost)),
                ]);

                if ($oldEmployeeDetail) {
                    $employee = $oldEmployees->where('idlogin', $admin->idlogin)->first();
                    $keinginanGaji = $keinginanGajis->where('idlogin', $admin->idlogin)->first();
                    $fullname = ltrim($admin->nama_lengkap);
                    $names = explode(" ", $fullname);
                    // Insert profile details

                    $birthDate = date('Y-m-d', strtotime($oldEmployeeDetail->ttl));
                    if ($oldEmployeeDetail->ttl === '0000-00-00' || !$birthDate) {
                        $birthDate = '1970-01-01';
                    }

                    $profileDetail = [
                        'candidate_id' => $candidateId,
                        'first_name' => $names[0],
                        'last_name' => substr($fullname, strlen($names[0]) + 1),
                        'birth_location' => $oldEmployeeDetail->tpl,
                        'birth_date' => $birthDate,
                        'gender' => $oldEmployeeDetail->jk == 'P' ? 'P' : ($oldEmployeeDetail->jk == 'L' ? 'L' : 'M'),
                        'identity_number' => $admin->id_ktp,
                        'reference' => $oldEmployeeDetail->referensi ?? null,
                        'religion_id' => $oldEmployeeDetail->IdAgama ?? null,
                        'marriage_status_id' => null,
                        'created_at' => $admin->TglPost ?? now(),
                        'updated_at' => $admin->TglPost ?? now(),
                    ];
                    if ($profileDetail) {
                        array_push($profileDetails, $profileDetail);
                    }

                    // Insert candidate notes
                    // $filteredOldRecentComments = $oldRecentComments->where('idlogin', $admin->idlogin)->all();
                    // foreach ($filteredOldRecentComments as $filteredOldRecentComment) {
                    //     $adminInterviewer = $administrators->where('idlogin', $filteredOldRecentComment->Usercreate)->first();
                    //     if ($adminInterviewer) {
                    //         $interviewer = $kustomer->where('telpon', $adminInterviewer->no_telp)->first();
                    //         if ($interviewer) {
                    //             $employee = $employees->where('user_id', $interviewer->id_kustomer)->first();
                    //             if ($employee) {
                    //                 $candidateNotes[] = [
                    //                     'candidate_id' => $candidateId,
                    //                     'employee_id' => $employee->id,
                    //                     'note' => $filteredOldRecentComment->ulasan,
                    //                     'created_at' => $filteredOldRecentComment->TglPost ?? now(),
                    //                     'updated_at' => $filteredOldRecentComment->TglPost ?? now(),
                    //                 ];
                    //             }
                    //         }
                    //     }
                    // }

                    if ($employee) {
                        $domicile = [
                            'candidate_id' => $candidateId,
                            'country_id' => 62,
                            'province_id' => $admin->IDprovinces === 0 ? null : $admin->IDprovinces,
                            'city_id' => null,
                            'subdistrict_id' => null,
                            'village_id' => null,
                            'address' => $employee->alamat,
                            'created_at' => $admin->TglPost ?? now(),
                            'updated_at' => $admin->TglPost ?? now(),
                        ];
                        array_push($domiciles, $domicile);
                        if ($keinginanGaji !== null && $employee->job !== '') {
                            $candidatePosition = $candidatePositions->where('name', $employee->job)->first();
                            if ($candidatePosition) {
                                $candidatePositionId = $candidatePosition->id;
                            } else {
                                $lastCandidatePositionId++;
                                array_push($newCandidatePositions, [
                                    'id' => $lastCandidatePositionId,
                                    'name' => ucwords($employee->job),
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                                $candidatePositionId = $lastCandidatePositionId;
                            }
                            $expectedJob = [
                                'candidate_id' => $candidateId,
                                'expected_salary' => $keinginanGaji->Desired,
                                'expected_position' => $candidatePositionId,
                                'position_reason' => $employee->inginposisi,
                                'salary_reason' => $keinginanGaji->Ulasan,
                                'created_at' => $employee->TglPost !== null && $employee->TglPost !== '0000-00-00 00:00:00' ? $employee->TglPost : now(),
                                'updated_at' => $employee->TglPost !== null && $employee->TglPost !== '0000-00-00 00:00:00' ? $employee->TglPost : now(),
                            ];
                            array_push($expectedJobs, $expectedJob);
                        }

                        if ($employee->gambar !== '' && $employee->gambar !== null) {
                            $documentId = Str::uuid();

                            array_push($documents, [
                                'id' => $documentId,
                                'user_id' => $kustomer != null ? $kustomer->id_kustomer : null,
                                'file_name' => $employee->gambar,
                                'original_file_name' => $employee->gambar,
                                'mime_type' => 'image/jpeg',
                                'type_id' => 2,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            array_push($cvDocuments, [
                                'candidate_id' => $candidateId,
                                'front_selfie' => $documentId,
                                'created_at' => $employee->TglPost !== null && $employee->TglPost !== '0000-00-00 00:00:00' ? $employee->TglPost : now(),
                                'updated_at' => $employee->TglPost !== null && $employee->TglPost !== '0000-00-00 00:00:00' ? $employee->TglPost : now(),
                            ]);
                        }
                    }

                    $pengalamans = $pengalamansAll->where('idlogin', $admin->idlogin)->all();
                    foreach ($pengalamans as $pengalaman) {
                        $candidatePosition = $candidatePositions->where('name', $pengalaman->sebagai)->first();
                        if ($candidatePosition) {
                            $candidatePositionId = $candidatePosition->id;
                        } else {
                            $candidatePositionFound = collect($newCandidatePositions)->where('name', $pengalaman->sebagai)->first();

                            if (!$candidatePositionFound) {
                                $lastCandidatePositionId++;
                                array_push($newCandidatePositions, [
                                    'id' => $lastCandidatePositionId,
                                    'name' => ucwords($pengalaman->sebagai),
                                    'created_at' => now(),
                                    'updated_at' => now(),
                            ]);
                                $candidatePositionId = $lastCandidatePositionId;
                            } else {
                                // Log::info($candidatePositionFound['id']);
                                $candidatePositionId = $candidatePositionFound['id'];
                            }
                        }
                        $cvExperiences[] = [
                            'candidate_id' => $candidateId,
                            'employment_type_id' => null,
                            'position_id' => $candidatePositionId,
                            'company_name' => $pengalaman->perusahaan,
                            'company_location' => null,
                            'jobdesc' => $pengalaman->sebagai,
                            'resign_reason' => $pengalaman->resign,
                            'reference' => null,
                            'previous_salary' => $pengalaman->gaji,
                            'started_at' => Carbon::createFromTimestamp($pengalaman->tahun)->toDateString(),
                            'ended_at' => Carbon::createFromTimestamp($pengalaman->sampai)->toDateString(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    $pendidikans = $pendidikansAll->where('idlogin', $admin->idlogin)->all();
                    foreach ($pendidikans as $pendidikan) {
                        $degreeId = null;
                        if (
                            str_contains(strtolower($pendidikan->pendidikan), 'sd')
                            || str_contains(strtolower($pendidikan->asal), 'sd')
                        ) {
                            $degreeId = 1;
                        } else if (
                            str_contains(strtolower($pendidikan->pendidikan), 'smp')
                            || str_contains(strtolower($pendidikan->asal), 'smp')
                        ) {
                            $degreeId = 2;
                        } else if (
                            str_contains(strtolower($pendidikan->pendidikan), 'sma')
                            || str_contains(strtolower($pendidikan->asal), 'sma')
                            || str_contains(strtolower($pendidikan->pendidikan), 'smk')
                            || str_contains(strtolower($pendidikan->asal), 'smk')
                            || str_contains(strtolower($pendidikan->pendidikan), 'smu')
                            || str_contains(strtolower($pendidikan->asal), 'smu')
                        ) {
                            $degreeId = 3;
                        } else if (
                            str_contains(strtolower($pendidikan->pendidikan), 'd3')
                            || str_contains(strtolower($pendidikan->pendidikan), 'd4')
                        ) {
                            $degreeId = 4;
                        } else if (
                            str_contains(strtolower($pendidikan->pendidikan), 's1')
                            || str_contains(strtolower($pendidikan->pendidikan), 'sarjana')
                            || str_contains(strtolower($pendidikan->asal), 'universitas')
                        ) {
                            $degreeId = 5;
                        } else if (
                            str_contains(strtolower($pendidikan->pendidikan), 's2')
                            || str_contains(strtolower($pendidikan->pendidikan), 'magister')
                        ) {
                            $degreeId = 6;
                        } else if (
                            str_contains(strtolower($pendidikan->pendidikan), 's3')
                            || str_contains(strtolower($pendidikan->pendidikan), 'doktor')
                        ) {
                            $degreeId = 7;
                        }
                        $startedAt = date('Y-m-d', strtotime($pendidikan->Tahun));
                        $endedAt = date('Y-m-d', strtotime($pendidikan->sampai));
                        if ($pendidikan->Tahun === '0000-00-00' || !$startedAt) {
                            $startedAt = '1970-01-01';
                        }
                        if ($pendidikan->sampai === '0000-00-00' || !$endedAt) {
                            $endedAt = null;
                        } 
                        $cvEducations[] = [
                            'candidate_id' => $candidateId,
                            'instance' => $pendidikan->asal,
                            'field_of_study' => '-',
                            'degree_id' => $degreeId,
                            'grade' => '-',
                            'started_at' => $startedAt,
                            'ended_at' => $endedAt,
                            'description' => '-',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    $kualifikasis = $kualifikasisAll->where('idlogin', $admin->idlogin)->all();
                    foreach ($kualifikasis as $kualifikasi) {
                        if ($kualifikasi->kualifikasi !== '' && $kualifikasi->kualifikasi !== null)
                            $cvSpecialities[] = [
                                'candidate_id' => $candidateId,
                                'name' => $kualifikasi->kualifikasi,
                                'created_at' => $kualifikasi->TglPost ?? now(),
                                'updated_at' =>  $kualifikasi->TglPost ?? now(),
                            ];
                    }

                    $hobies = $hobiesAll->where('idlogin', $admin->idlogin)->all();
                    foreach ($hobies as $hoby) {
                        if ($hoby->hoby !== '' && $hoby->hoby !== null)
                            $cvHobbies[] = [
                                'candidate_id' => $candidateId,
                                'name' => $hoby->hoby,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                    }
                }

                // $administrator = $administrators->where('no_telp', $user->telpon)->first();
                // if ($administrator) {
                //     $employeeDetail = $employeeDetails->where('idlogin', $administrator->idlogin)->first();
                //     if ($employeeDetail) {
                //         $employee = $employees->where('idlogin', $administrator->idlogin)->first();
                //         $keinginanGaji = $keinginanGajis->where('idlogin', $administrator->idlogin)->first();
                //         $name = explode(" ", $administrator->nama_lengkap);
                //         $profileDetail = [
                //             'user_id' => $user->id_kustomer,
                //             'first_name' => $name[0],
                //             'last_name' => substr($administrator->nama_lengkap, strlen($name[0]) + 1),
                //             'birth_location' => $employeeDetail->tpl,
                //             'birth_date' => date('Y-m-d\TH:i:s.v\Z', strtotime($employeeDetail->ttl)),
                //             'gender' => $employeeDetail->jk == 'P' ? 'Perempuan' : ($employeeDetail->jk == 'L' ? 'Laki - Laki' : 'M'),
                //             'identity_number' => $administrator->id_ktp,
                //             'reference' => !empty($employeeDetail->referensi) ? $employeeDetail->referensi : null,
                //             'religion_id' => !empty($employeeDetail->IdAgama) ? $employeeDetail->IdAgama : null,
                //             'marriage_status_id' => null,
                //             'created_at' => $administrator->TglPost ? $employeeDetail->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //             'updated_at' => $employeeDetail->TglPost ? $employeeDetail->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //         ];
                //         $candidate = Candidate::create([
                //             'user_id' => $user->id_kustomer,
                //             'name' => $user->nama_lengkap,
                //             'country_code' => 62,
                //             'phone_number' => substr($user->telpon, 1),
                //             'status' => 3,
                //             'registered_at' => date('Y-m-d\TH:i:s.v\Z', strtotime($user->tgl_kus)),
                //         ]);
                //         $recentComets = $recentCometsAll->where('idlogin', $administrator->idlogin)->all();
                //         foreach ($recentComets as $recentComet) {
                //             $AdminInterivewer = $administrators->where('idlogin', $recentComet->Usercreate)->first();
                //             if ($AdminInterivewer) {
                //                 $interivewer = $users->where('telpon', $AdminInterivewer->no_telp)->first();
                //                 if ($interivewer) {
                //                     $cvEmployeeDetail = $cvEmployeeDetails->where('user_id', $interivewer->id_kustomer)->first();
                //                     if ($cvEmployeeDetail) {
                //                         $note[] = [
                //                             'candidate_id' => $candidate->id,
                //                             'employee_id' => $cvEmployeeDetail->id,
                //                             'note' => $recentComet->ulasan,
                //                             'created_at' => $recentComet->TglPost ? $recentComet->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //                             'updated_at' => $recentComet->TglPost ? $recentComet->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //                         ];
                //                     }
                //                 }
                //             }
                //         }

                //         $sosmed = [
                //             'user_id' => $user->id_kustomer,
                //         ];

                //         if ($employee) {
                //             $domicile = [
                //                 'user_id' => $user->id_kustomer,
                //                 'country_id' => 62,
                //                 'province_id' => $administrator->IDprovinces,
                //                 'city_id' => 0,
                //                 'subdistrict_id' => 0,
                //                 'village_id' => 0,
                //                 'address' => $employee->alamat,
                //                 'created_at' => $administrator->TglPost ? $administrator->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //                 'updated_at' => $employee->TglPost ? $employee->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //             ];
                //             if ($keinginanGaji) {
                //                 $candidatePosition = $candidatePositions->where('name', $employee->job)->first();
                //                 if (!$candidatePosition) {
                //                     $candidatePosition = new CandidatePosition();
                //                     $candidatePosition->name = $employee->job;
                //                     $candidatePosition->save();
                //                 }
                //                 $expectedJob = [
                //                     'user_id' => $user->id_kustomer,
                //                     'expected_salary' => $keinginanGaji->Desired,
                //                     'expected_position' => $candidatePosition->id,
                //                     'position_reason' => $employee->inginposisi,
                //                     'salary_reason' => $keinginanGaji->Ulasan,
                //                     'created_at' => $employee->TglPost ? $employee->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //                     'updated_at' => $employee->TglPost ? $employee->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //                 ];
                //             }
                //         }
                //         $pengalamans = $pengalamansAll->where('idlogin', $administrator->idlogin)->all();
                //         foreach ($pengalamans as $pengalaman) {
                //             $candidatePosition = $candidatePositions->where('name', $pengalaman->sebagai)->first();
                //             if (!$candidatePosition) {
                //                 $candidatePosition = new CandidatePosition();
                //                 $candidatePosition->name = $employee->job;
                //                 $candidatePosition->save();
                //             }
                //             $cvExperiences[] = [
                //                 'user_id' => $user->id_kustomer,
                //                 'employment_type_id' => null,
                //                 'position_id' => $candidatePosition->id,
                //                 'company_name' => $pengalaman->perusahaan,
                //                 'company_location' => null,
                //                 'jobdesc' => $pengalaman->sebagai,
                //                 'resign_reason' => $pengalaman->resign,
                //                 'reference' => null,
                //                 'previous_salary' => 0,
                //                 'started_at' => date('Y-m-d\TH:i:s.v\Z', strtotime($pengalaman->tahun)),
                //                 'ended_at' => date('Y-m-d\TH:i:s.v\Z', strtotime($pengalaman->sampai)),
                //                 'created_at' => date('Y-m-d\TH:i:s.v\Z', time()),
                //                 'updated_at' => date('Y-m-d\TH:i:s.v\Z', time()),
                //             ];
                //         }

                //         $pendidikans = $pendidikansAll->where('idlogin', $administrator->idlogin)->all();
                //         foreach ($pendidikans as $pendidikan) {
                //             $degree = $degrees->where('name', 'like', '%' . $pendidikan->pendidikan . '%')->first();
                //             if (!$degree) {
                //                 $degree = new Degree();
                //                 $degree->name = $pendidikan->pendidikan;
                //                 $degree->save();
                //             }
                //             $cvEducation[] = [
                //                 'user_id' => $user->id_kustomer,
                //                 'instance' => $pendidikan->asal,
                //                 'field_of_study' => '-',
                //                 'degree_id' => $degree->id,
                //                 'grade' => '-',
                //                 'started_at' => $pendidikan->Tahun,
                //                 'ended_at' => $pendidikan->sampai,
                //                 'description' => '-',
                //                 'created_at' => date('Y-m-d\TH:i:s.v\Z', time()),
                //                 'updated_at' => date('Y-m-d\TH:i:s.v\Z', time()),
                //             ];
                //         }

                //         $kualifikasis = $kualifikasisAll->where('idlogin', $administrator->idlogin)->all();
                //         foreach ($kualifikasis as $kualifikasi) {
                //             $cvSpecialities[] = [
                //                 'user_id' => $user->id_kustomer,
                //                 'name' => $kualifikasi->kualifikasi,
                //                 'created_at' => $kualifikasi->TglPost ? $kualifikasi->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //                 'updated_at' =>  $kualifikasi->TglPost ? $kualifikasi->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //             ];
                //         }

                //         $hobies = $hobiesAll->where('idlogin', $administrator->idlogin)->all();
                //         foreach ($hobies as $hoby) {
                //             $cvHobbies[] = [
                //                 'user_id' => $user->id_kustomer,
                //                 'name' => $hoby->hoby,
                //                 'created_at' => date('Y-m-d\TH:i:s.v\Z', time()),
                //                 'updated_at' => date('Y-m-d\TH:i:s.v\Z', time()),
                //             ];
                //         }
                //         if ($profileDetail) {
                //             $profileDetails[] = $profileDetail;
                //         }
                //         if ($candidate) {
                //             $candidates[] = $candidate;
                //         }
                //         if ($sosmed) {
                //             $sosmeds[] = $sosmed;
                //         }
                //         if ($domicile) {
                //             $domiciles[] = $domicile;
                //         }
                //         if ($expectedJob) {
                //             $expectedJobs[] = $expectedJob;
                //         }
                //     }
                // }
                Log::info('Candidate ke: ' . $candidateId);
            }
            DB::transaction(function () use (
                $candidates,
                $candidateNotes,
                $profileDetails,
                $newCandidatePositions,
                $domiciles,
                $cvExperiences,
                $cvEducations,
                $cvSpecialities,
                $cvHobbies,
                $expectedJobs,
                $documents,
                $cvDocuments,
            ) {
                $chunckedCandidates = array_chunk($candidates, 1000);
                foreach ($chunckedCandidates as $candidates) {
                    DB::table('candidates')->insert($candidates);
                }
                $chunckedCandidateNotes = array_chunk($candidateNotes, 1000);
                foreach ($chunckedCandidateNotes as $candidateNotes) {
                    CandidateNote::insert($candidateNotes);
                }
                $chunckedProfileDetails = array_chunk($profileDetails, 1000);
                foreach ($chunckedProfileDetails as $profileDetails) {
                    CvProfileDetail::insert($profileDetails);
                }
                $chunckedCandidatePositions = array_chunk($newCandidatePositions, 1000);
                foreach ($chunckedCandidatePositions as $candidatePositions) {
                    DB::table('candidate_positions')->insert($candidatePositions);
                }
                $chunckedDomiciles = array_chunk($domiciles, 1000);
                foreach ($chunckedDomiciles as $domiciles) {
                    CvDomicile::insert($domiciles);
                }
                $chunckedExperiences = array_chunk($cvExperiences, 1000);
                foreach ($chunckedExperiences as $experiences) {
                    CvExperience::insert($experiences);
                }
                $chunckedEducations = array_chunk($cvEducations, 1000);
                foreach ($chunckedEducations as $educations) {
                    CvEducation::insert($educations);
                }
                $chunckedSpecialities = array_chunk($cvSpecialities, 1000);
                foreach ($chunckedSpecialities as $specialities) {
                    CvSpeciality::insert($specialities);
                }
                $chunckedHobbies = array_chunk($cvHobbies, 1000);
                foreach ($chunckedHobbies as $hobbies) {
                    CvHobby::insert($hobbies);
                }
                $chunckedExpectedJobs = array_chunk($expectedJobs, 1000);
                foreach ($chunckedExpectedJobs as $expectedJobs) {
                    CvExpectedJob::insert($expectedJobs);
                }
                $chunckedDocuments = array_chunk($documents, 1000);
                foreach ($chunckedDocuments as $documents) {
                    Document::insert($documents);
                }
                $chunckedCvDocuments = array_chunk($cvDocuments, 1000);
                foreach ($chunckedCvDocuments as $cvDocuments) {
                    CvDocument::insert($cvDocuments);
                }
            });
            // CvSosmed::insert($sosmeds);
            // CvDomicile::insert($domiciles);
            // CvExpectedJob::insert($expectedJobs);
            // CvHobby::insert($cvHobbies);
            // CvSpeciality::insert($cvSpecialities);
            // foreach (array_chunk($cvEducation, 1000) as $t) {
            //     CvEducation::insert($t);
            // }
            // foreach (array_chunk($cvExperiences, 1000) as $t) {
            //     CvExperience::insert($t);
            // }
            // }
            // }
        } catch (Exception $e) {
            Log::info('Error :' . $e);
        }
    }
}
