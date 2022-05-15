<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CandidateNote;
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
use App\Models\Employee;
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
            $lowerBoundaryId = 1;
            $upperBoundaryId = 50;
            $users = User::whereRaw('LENGTH(telpon) > 7')->get();
            Log::info('Kustomer : ' . count($users));
            $administrators = DB::connection('cvsaya')->table('administrator')->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'idlogin', 'username', 'id_ktp', 'nama_lengkap', 'no_telp', 'TglPost',
            ]);
            Log::info('Administrator : ' . count($administrators));
            $oldEmployeeDetails = DB::connection('cvsaya')->table('1employeedetail')->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'idlogin', 'ttl', 'tpl', 'jk', 'referensi', 'TglPost',
            ]);
            Log::info('1employeedetail : ' . count($oldEmployeeDetails));
            $employees = DB::connection('cvsaya')->table('1employee')->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'inginposisi', 'gambar', 'alamat', 'TglPost',
            ]);
            Log::info('1employee : ' . count($employees));
            $keinginanGajis = DB::connection('cvsaya')->table('1keinginangaji')->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'Desired', 'Ulasan',
            ]);
            Log::info('1keinginangaji : ' . count($keinginanGajis));
            $pengalamansAll = DB::connection('cvsaya')->table('1pengalaman')->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'sebagai', 'tahun', 'sampai', 'perusahaan', 'resign'
            ]);
            Log::info('1pengalaman : ' . count($pengalamansAll));
            $candidatePositions = CandidatePosition::all([
                'id', 'name',
            ]);
            Log::info('Candidate : ' . count($candidatePositions));
            $pendidikansAll = DB::connection('cvsaya')->table('1pendidikan')->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'idlogin', 'Tahun', 'sampai', 'asal'
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
            $recentCometsAll = DB::connection('cvsaya')->table('9Recentcomet')->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'idlogin', 'ulasan', 'TglPost'
            ]);
            Log::info('9Recentcomet : ' . count($recentCometsAll));
            // $cvEmployeeDetails = Employee::all();
            // $users = User::whereNotNull('telpon')->get();
            // dd($users);
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
            $note = [];

            $profileDetails = [];
            $cvSpecialities = [];
            $cvExperiences = [];
            $cvEducation = [];
            $candidates = [];
            $sosmeds = [];
            $domiciles = [];
            $expectedJobs = [];
            $cvHobbies = [];

            // Log::info(count($user2s));
            // if ($index == 1) {
            foreach ($administrators as $admin) {
                $kustomer = $users->where('telpon', $admin->no_telp)->first();
                $oldEmployeeDetail = $oldEmployeeDetails->where('idLogin', $admin->idlogin)->first();
                Log::info($oldEmployeeDetail);

                //     $administrator = $administrators->where('no_telp', $user->telpon)->first();
                //     if ($administrator) {
                //         $employeeDetail = $employeeDetails->where('idlogin', $administrator->idlogin)->first();
                //         if ($employeeDetail) {
                //             $employee = $employees->where('idlogin', $administrator->idlogin)->first();
                //             $keinginanGaji = $keinginanGajis->where('idlogin', $administrator->idlogin)->first();
                //             $name = explode(" ", $administrator->nama_lengkap);
                //             $profileDetail = [
                //                 'user_id' => $user->id_kustomer,
                //                 'first_name' => $name[0],
                //                 'last_name' => substr($administrator->nama_lengkap, strlen($name[0]) + 1),
                //                 'birth_location' => $employeeDetail->tpl,
                //                 'birth_date' => date('Y-m-d\TH:i:s.v\Z', strtotime($employeeDetail->ttl)),
                //                 'gender' => $employeeDetail->jk == 'P' ? 'Perempuan' : ($employeeDetail->jk == 'L' ? 'Laki - Laki' : 'M'),
                //                 'identity_number' => $administrator->id_ktp,
                //                 'reference' => !empty($employeeDetail->referensi) ? $employeeDetail->referensi : null,
                //                 'religion_id' => !empty($employeeDetail->IdAgama) ? $employeeDetail->IdAgama : null,
                //                 'marriage_status_id' => null,
                //                 'created_at' => $administrator->TglPost ? $employeeDetail->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //                 'updated_at' => $employeeDetail->TglPost ? $employeeDetail->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //             ];
                //             $candidate = Candidate::create([
                //                 'user_id' => $user->id_kustomer,
                //                 'name' => $user->nama_lengkap,
                //                 'country_code' => 62,
                //                 'phone_number' => substr($user->telpon, 1),
                //                 'status' => 3,
                //                 'registered_at' => date('Y-m-d\TH:i:s.v\Z', strtotime($user->tgl_kus)),
                //             ]);
                //             $recentComets = $recentCometsAll->where('idlogin', $administrator->idlogin)->all();
                //             foreach ($recentComets as $recentComet) {
                //                 $AdminInterivewer = $administrators->where('idlogin', $recentComet->Usercreate)->first();
                //                 if ($AdminInterivewer) {
                //                     $interivewer = $users->where('telpon', $AdminInterivewer->no_telp)->first();
                //                     if ($interivewer) {
                //                         $cvEmployeeDetail = $cvEmployeeDetails->where('user_id', $interivewer->id_kustomer)->first();
                //                         if ($cvEmployeeDetail) {
                //                             $note[] = [
                //                                 'candidate_id' => $candidate->id,
                //                                 'employee_id' => $cvEmployeeDetail->id,
                //                                 'note' => $recentComet->ulasan,
                //                                 'created_at' => $recentComet->TglPost ? $recentComet->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //                                 'updated_at' => $recentComet->TglPost ? $recentComet->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //                             ];
                //                         }
                //                     }
                //                 }
                //             }

                //             $sosmed = [
                //                 'user_id' => $user->id_kustomer,
                //             ];

                //             if ($employee) {
                //                 $domicile = [
                //                     'user_id' => $user->id_kustomer,
                //                     'country_id' => 62,
                //                     'province_id' => $administrator->IDprovinces,
                //                     'city_id' => 0,
                //                     'subdistrict_id' => 0,
                //                     'village_id' => 0,
                //                     'address' => $employee->alamat,
                //                     'created_at' => $administrator->TglPost ? $administrator->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //                     'updated_at' => $employee->TglPost ? $employee->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //                 ];
                //                 if ($keinginanGaji) {
                //                     $candidatePosition = $candidatePositions->where('name', $employee->job)->first();
                //                     if (!$candidatePosition) {
                //                         $candidatePosition = new CandidatePosition();
                //                         $candidatePosition->name = $employee->job;
                //                         $candidatePosition->save();
                //                     }
                //                     $expectedJob = [
                //                         'user_id' => $user->id_kustomer,
                //                         'expected_salary' => $keinginanGaji->Desired,
                //                         'expected_position' => $candidatePosition->id,
                //                         'position_reason' => $employee->inginposisi,
                //                         'salary_reason' => $keinginanGaji->Ulasan,
                //                         'created_at' => $employee->TglPost ? $employee->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //                         'updated_at' => $employee->TglPost ? $employee->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //                     ];
                //                 }
                //             }
                //             $pengalamans = $pengalamansAll->where('idlogin', $administrator->idlogin)->all();
                //             foreach ($pengalamans as $pengalaman) {
                //                 $candidatePosition = $candidatePositions->where('name', $pengalaman->sebagai)->first();
                //                 if (!$candidatePosition) {
                //                     $candidatePosition = new CandidatePosition();
                //                     $candidatePosition->name = $employee->job;
                //                     $candidatePosition->save();
                //                 }
                //                 $cvExperiences[] = [
                //                     'user_id' => $user->id_kustomer,
                //                     'employment_type_id' => null,
                //                     'position_id' => $candidatePosition->id,
                //                     'company_name' => $pengalaman->perusahaan,
                //                     'company_location' => null,
                //                     'jobdesc' => $pengalaman->sebagai,
                //                     'resign_reason' => $pengalaman->resign,
                //                     'reference' => null,
                //                     'previous_salary' => 0,
                //                     'started_at' => date('Y-m-d\TH:i:s.v\Z', strtotime($pengalaman->tahun)),
                //                     'ended_at' => date('Y-m-d\TH:i:s.v\Z', strtotime($pengalaman->sampai)),
                //                     'created_at' => date('Y-m-d\TH:i:s.v\Z', time()),
                //                     'updated_at' => date('Y-m-d\TH:i:s.v\Z', time()),
                //                 ];
                //             }

                //             $pendidikans = $pendidikansAll->where('idlogin', $administrator->idlogin)->all();
                //             foreach ($pendidikans as $pendidikan) {
                //                 $degree = $degrees->where('name', 'like', '%' . $pendidikan->pendidikan . '%')->first();
                //                 if (!$degree) {
                //                     $degree = new Degree();
                //                     $degree->name = $pendidikan->pendidikan;
                //                     $degree->save();
                //                 }
                //                 $cvEducation[] = [
                //                     'user_id' => $user->id_kustomer,
                //                     'instance' => $pendidikan->asal,
                //                     'field_of_study' => '-',
                //                     'degree_id' => $degree->id,
                //                     'grade' => '-',
                //                     'started_at' => $pendidikan->Tahun,
                //                     'ended_at' => $pendidikan->sampai,
                //                     'description' => '-',
                //                     'created_at' => date('Y-m-d\TH:i:s.v\Z', time()),
                //                     'updated_at' => date('Y-m-d\TH:i:s.v\Z', time()),
                //                 ];
                //             }

                //             $kualifikasis = $kualifikasisAll->where('idlogin', $administrator->idlogin)->all();
                //             foreach ($kualifikasis as $kualifikasi) {
                //                 $cvSpecialities[] = [
                //                     'user_id' => $user->id_kustomer,
                //                     'name' => $kualifikasi->kualifikasi,
                //                     'created_at' => $kualifikasi->TglPost ? $kualifikasi->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //                     'updated_at' =>  $kualifikasi->TglPost ? $kualifikasi->TglPost : date('Y-m-d\TH:i:s.v\Z', time()),
                //                 ];
                //             }

                //             $hobies = $hobiesAll->where('idlogin', $administrator->idlogin)->all();
                //             foreach ($hobies as $hoby) {
                //                 $cvHobbies[] = [
                //                     'user_id' => $user->id_kustomer,
                //                     'name' => $hoby->hoby,
                //                     'created_at' => date('Y-m-d\TH:i:s.v\Z', time()),
                //                     'updated_at' => date('Y-m-d\TH:i:s.v\Z', time()),
                //                 ];
                //             }
                //             if ($profileDetail) {
                //                 $profileDetails[] = $profileDetail;
                //             }
                //             if ($candidate) {
                //                 $candidates[] = $candidate;
                //             }
                //             if ($sosmed) {
                //                 $sosmeds[] = $sosmed;
                //             }
                //             if ($domicile) {
                //                 $domiciles[] = $domicile;
                //             }
                //             if ($expectedJob) {
                //                 $expectedJobs[] = $expectedJob;
                //             }
                //         }
                //     }
                //     Log::info('Index ke: ' . $index . ',' . $index2);
                // }
                // CvProfileDetail::insert($profileDetails);
                // CandidateNote::insert($note);
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
            }
            // }
            // }
        } catch (Exception $e) {
            Log::info('Error :' . $e);
        }
    }
}
