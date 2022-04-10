<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $religions = ['Islam','Kristen','Hindu','Buddha','Kong Hu Cu'];
        foreach($religions as $religion){
            Religion::create([
                'name' => $religion,
            ]);
        }
    }
}
