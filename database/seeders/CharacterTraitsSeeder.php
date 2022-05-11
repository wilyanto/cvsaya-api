<?php

namespace Database\Seeders;

use App\Models\CharacterTrait;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CharacterTraitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $characterTraits = ['ability', 'communication', 'Serious'];

        foreach ($characterTraits as $characterTrait) {
            CharacterTrait::create([
                'name' => $characterTrait,
            ]);
        }
    }
}
