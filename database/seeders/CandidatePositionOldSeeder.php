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
            // ->distinct('sebagai')
            ->get();
        $employees = DB::connection('cvsaya')
        ->table('1employee')
        ->groupBy('job')
        ->select('job')
        ->get();

        $candidates = $pengalamans->map(function ($item, $key) {
            return [
                'name' => $item->sebagai,
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ];
        });

        $employees = $employees->map(function ($item, $key) {
            return [
                'name' => $item->job,
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ];
        });
        CandidatePosition::insert($candidates->toArray());
        CandidatePosition::insert($employees->toArray());
    }
}
