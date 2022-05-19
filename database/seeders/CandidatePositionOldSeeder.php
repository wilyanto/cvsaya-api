<?php

namespace Database\Seeders;

use App\Models\Candidate;
use Illuminate\Support\Collection;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CandidatePosition;

class CandidatePositionOldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pengalamans = DB::connection('cvsaya')
            ->table('1pengalaman')
            ->groupBy('sebagai')
            ->select('sebagai')
            ->get();
        $employees = DB::connection('cvsaya')
            ->table('1employee')
            ->groupBy('job')
            ->select('job')
            ->get();

        $candidates = $pengalamans->map(function ($item) {
            if ($item !== null || $item !== '') {
                return strtolower($item->sebagai);
            }
        });

        $employees = $employees->map(function ($item) {
            if ($item !== null || $item !== '') {
                return strtolower($item->job);
            }
        });

        Log::info(count($candidates));
        Log::info(count($employees));
        Log::info(count(array_unique(array_merge($candidates->toArray(), $employees->toArray()))));

        $candidatePositionNames = array_unique(array_merge($candidates->toArray(), $employees->toArray()));

        // dd($candidatePositionNames);

        $candidatePositions = [];
        foreach ($candidatePositionNames as $candidatePositionName) {
            if ($candidatePositionName !== null && $candidatePositionName !== '') {
                array_push(
                    $candidatePositions,
                    [
                        'name' => ucwords($candidatePositionName),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        $chunckedPositions = array_chunk($candidatePositions, 1000);
        foreach ($chunckedPositions as $chunckedPositionsVal) {
            CandidatePosition::insert($chunckedPositionsVal);
        }
    }
}
