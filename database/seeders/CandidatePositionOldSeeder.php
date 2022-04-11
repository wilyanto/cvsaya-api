<?php

namespace Database\Seeders;

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
            ->limit(25000)
            ->get();


        $candidates = $pengalamans->map(function ($item, $key) {
            return [
                'name' => $item->sebagai,
                'created_at' => date('Y-m-d H:i:s',time()),
                'updated_at' => date('Y-m-d H:i:s',time()),
            ];
        });

        Log::info($candidates);
        $totalCandidates = count($candidates);
        if($totalCandidates >= 15000){
            Log::info($totalCandidates / 2);
        }
        Log::info(count($candidates));
    }
}
