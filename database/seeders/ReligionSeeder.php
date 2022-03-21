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
        $religions = ['Buddha','Kristen','Islam','Kong Hu Cu','Hindu'];
        foreach($religions as $religion){
            Religion::create([
                'name' => $religion,
            ]);
        }
    }
}
