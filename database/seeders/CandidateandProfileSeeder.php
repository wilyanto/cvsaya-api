<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CandidatePosition;
use App\Models\CvDomicile;
use App\Models\CvEducation;
use App\Models\CvExpectedJob;
use App\Models\CvExperience;
use App\Models\CvHobby;
use App\Models\CvProfileDetail;
use App\Models\CvSosmed;
use App\Models\CvSpeciality;
use App\Models\Degree;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class CandidateandProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $divisis = DB::connection('cvsaya')->table('4divisi')->get();
    }
}
