<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CandidateNote;
use App\Models\CandidatePosition;
use App\Models\CvDocument;
use App\Models\CvDomicile;
use App\Models\CvEducation;
use App\Models\CvExpectedJob;
use App\Models\CvExperience;
use App\Models\CvHobby;
use App\Models\CvProfileDetail;
use App\Models\CvSosmed;
use App\Models\CvSpeciality;
use App\Models\Document;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CleanerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CvHobby::truncate();
        CvSosmed::truncate();
        CvDomicile::truncate();
        CvEducation::truncate();
        CvExperience::truncate();
        CvProfileDetail::truncate();
        CvDocument::truncate();
        CvExpectedJob::truncate();
        CvSpeciality::truncate();
        CandidateNote::truncate();
        CandidatePosition::truncate();
        Candidate::truncate();
        Document::truncate();
    }
}
