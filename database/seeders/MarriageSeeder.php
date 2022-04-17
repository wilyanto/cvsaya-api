<?php

namespace Database\Seeders;

use App\Models\MarriageStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarriageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marriages = ['TK/0','K/1','K/2'];
        foreach($marriages as $marriage){
            MarriageStatus::create([
                'name' => $marriage,
            ]);
        }
    }
}
