<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CandidatePosition;
use App\Models\CvExpectedJob;
use App\Models\CvExperience;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RevisePositionIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $oldEmployees = DB::connection('cvsaya')->table('1employee')
        //     ->leftJoin('administrator', '1employee.idlogin', '=', 'administrator.idlogin')
        //     ->limit(100)
        //     ->get();

        // $candidates = Candidate::all();

        // foreach ($oldEmployees as $oldEmployee) {
        //     // substr($user->telpon, 1);
        // }

        // // $prodCvExpectedJobs = DB::connection('existing-cvsaya')->table('cv_expected_jobs')
        // //     ->leftJoin('candidate_positions', 'cv_expected_jobs.expected_position', '=', 'candidate_positions.id')
        // //     ->limit(100)
        // //     ->get();

        // // $prodCvExperiences = DB::connection('existing-cvsaya')->table('cv_experiences')
        // //     ->leftJoin('candidate_positions', 'cv_experiences.position_id', '=', 'candidate_positions.id')
        // //     ->limit(100)
        // //     ->get();

        // dd($oldEmployee);

        // $newCvExpectedJobs = [];
        // $newCvExperiences = [];



        // // foreach ($prodCvExpectedJobs as $prodCvExpectedJob) {
        // //     Log::info($prodCvExpectedJob->name);
        // //     // Log::info(trim(strtolower($prodCvExpectedJob->name)));
        // //     // Log::info($candidatePositions->where('name', trim(strtolower($prodCvExpectedJob->name)))->first());
        // //     $candidatePositionFound = $candidatePositions->where('name', $prodCvExpectedJob->name)
        // //         ->first();

        // //     Log::info($candidatePositionFound);
        // //     // array_push($newCvExpectedJobs, $prodCvExpectedJob);
        // // }

        $newExpectedJobs = [];
        $newExperiences = [];

        $candidates = Candidate::all();
        $candidatePositions = CandidatePosition::all();

        // Pergi ke cvsayav, ambil administrator (idlogin dan telpon)

        $lowerBoundaryId = 1;
        $upperBoundaryId = 5000;
        $administrators = DB::connection('cvsaya')->table('administrator')->whereRaw('LENGTH(no_telp) > 7')
            ->whereBetween('idlogin', [$lowerBoundaryId, $upperBoundaryId])->get([
                'idlogin', 'username', 'id_ktp', 'nama_lengkap', 'no_telp', 'TglPost', 'IDprovinces'
            ]);
        Log::info('Administrator : ' . count($administrators));

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

        Log::info($administrators);

        foreach ($administrators as $admin) {
            $candidate = $candidates->where('phone_number', substr($admin->no_telp, 1))->first();
            $employee = $oldEmployees->where('idlogin', $admin->idlogin)->first();
            $keinginanGaji = $keinginanGajis->where('idlogin', $admin->idlogin)->first();
            $pengalamans = $pengalamansAll->where('idlogin', $admin->idlogin)->all();
            if ($employee && $keinginanGaji) {
                if ($employee->job !== '') {
                    $candidatePosition = collect($candidatePositions)->filter(function ($item) use ($employee) {
                        return trim(strtolower($item->name)) == trim(strtolower($employee->job));
                    })->first();

                    if ($candidatePosition) {
                        $candidatePositionId = $candidatePosition->id;
                    }
                    $expectedJob = [
                        'candidate_id' => $candidate->id,
                        'expected_salary' => $keinginanGaji->Desired,
                        'expected_position' => $candidatePositionId,
                        'position_reason' => $employee->inginposisi,
                        'salary_reason' => $keinginanGaji->Ulasan,
                        'created_at' => $employee->TglPost !== null && $employee->TglPost !== '0000-00-00 00:00:00' ? $employee->TglPost : now(),
                        'updated_at' => $employee->TglPost !== null && $employee->TglPost !== '0000-00-00 00:00:00' ? $employee->TglPost : now(),
                    ];
                    Log::info($candidate->id);
                    array_push($newExpectedJobs, $expectedJob);
                }
            }

            foreach ($pengalamans as $pengalaman) {
                $candidatePosition = collect($candidatePositions)->filter(function ($item) use ($pengalaman) {
                    return trim(strtolower($item->name)) == trim(strtolower($pengalaman->sebagai));
                })->first();
                if ($candidatePosition) {
                    $candidatePositionId = $candidatePosition->id;
                }
                $experience = [
                    'candidate_id' => $candidate->id,
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
                Log::info($candidate->id);
                array_push($newExperiences, $experience);
            }
        }

        DB::transaction(function () use (
            $newExpectedJobs,
            $newExperiences,
        ) {
            $chunckedExpectedJobs = array_chunk($newExpectedJobs, 1000);
            foreach ($chunckedExpectedJobs as $expectedJobs) {
                CvExpectedJob::insert($expectedJobs);
            }
            $chunckedExperiences = array_chunk($newExperiences, 1000);
            foreach ($chunckedExperiences as $experiences) {
                CvExperience::insert($experiences);
            }
        });
    }
}
