<?php

namespace Database\Seeders;

use App\Models\InterviewResult;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InterviewResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $interviewResults = [
            'Recommended',
            'Hold',
            'Bad'
        ];

        foreach($interviewResults as $interviewResult){
            InterviewResult::create([
                'name' => $interviewResult,
            ]);
        }
    }
}
