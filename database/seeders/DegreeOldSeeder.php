<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Degree;

class DegreeOldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pendidikans = DB::connection('cvsaya')
            ->table('1pendidikan')
            ->groupBy('pendidikan')
            ->select('pendidikan')
            ->get();
        $candidates = $pendidikans->map(function ($item, $key) {
            return [
                'name' => $item->pendidikan,
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ];
        });
        Degree::insert($candidates->toArray());
    }
}
