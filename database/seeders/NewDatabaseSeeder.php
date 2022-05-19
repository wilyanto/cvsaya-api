<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CandidatePosition;
use App\Models\CvEducation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $existingCandidates = DB::connection('existing-cvsaya')->table('candidates')
        //     ->where('user_id', '>=', 30000)
        //     ->get();

        // $candidates = Candidate::where('user_id', '!=', null)->get();

        // $existingUserIds = $existingCandidates->pluck('user_id');
        // $currentUserIds = $candidates->pluck('user_id');
        // $unintersectedUserIds = array_diff($existingUserIds->toArray(), $currentUserIds->toArray());

        // $newCandidates = Candidate::whereIn('user_id', $unintersectedUserIds)->get();
        // $candidatePositions = CandidatePosition::all();

        $addCandidatePositions = [];
        Log::info('Helo');
        $currentCandidatePositions = CandidatePosition::all();
        $existingCandidatePositions = DB::connection('existing-cvsaya')
            ->table('candidate_positions')->get();

        Log::info('Heloe');

        $currentCandidatePositionNames = $currentCandidatePositions->pluck('name');
        $existingCandidatePositionNames = $existingCandidatePositions->pluck('name');

        $currentCandidatePositionNames = $currentCandidatePositionNames->map(function ($item) {
            if ($item !== null || $item !== '') {
                return strtolower($item);
            }
        });

        $existingCandidatePositionNames = $existingCandidatePositionNames->map(function ($item) {
            if ($item !== null || $item !== '') {
                return strtolower($item);
            }
        });

        $unintersectedCandidatePositionNames = array_diff($currentCandidatePositionNames->toArray(), $existingCandidatePositionNames->toArray());

        Log::info('Existing: ' . count($currentCandidatePositionNames));
        Log::info('Current: ' . count($existingCandidatePositionNames));
        Log::info('Diff: ' . count($unintersectedCandidatePositionNames));

        foreach ($unintersectedCandidatePositionNames as $unintersectedCandidatePositionName) {
            if ($unintersectedCandidatePositionName !== null && $unintersectedCandidatePositionName !== '') {
                array_push(
                    $addCandidatePositions,
                    [
                        'name' => ucwords($unintersectedCandidatePositionName),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        $chunckedPositions = array_chunk($addCandidatePositions, 1000);
        foreach ($chunckedPositions as $chunckedPositionsVal) {
            CandidatePosition::insert($chunckedPositionsVal);
        }

        dd();

        // Log::info('Existing: ' . count($existingCandidates));
        // Log::info('Current: ' . count($candidates));
        // Log::info('Diff: ' . count($unintersectedUserIds));


        // $existingCandidatePositions = DB::connection('existing-cvsaya')
        //     ->table('candidate_positions')->all();
        // $newDocuments = DB::connection('existing-cvsaya')->table('documents')
        //     ->whereIn('user_id', $unintersectedUserIds)->get();
        // $newCvDocuments = DB::connection('existing-cvsaya')->table('cv_documents')
        //     ->whereIn('user_id', $unintersectedUserIds)->get();
        // $newCvDomiciles = DB::connection('existing-cvsaya')->table('cv_domiciles')
        //     ->whereIn('user_id', $unintersectedUserIds)->get();
        // $newCvEducations = DB::connection('existing-cvsaya')->table('cv_educations')
        //     ->whereIn('user_id', $unintersectedUserIds)->get();
        // $newCvExpectedJobs = DB::connection('existing-cvsaya')->table('cv_expected_jobs')
        //     ->whereIn('user_id', $unintersectedUserIds)->get();
        // $newCvExperiences = DB::connection('existing-cvsaya')->table('cv_experiences')
        //     ->whereIn('user_id', $unintersectedUserIds)->get();
        // $newCvHobbies = DB::connection('existing-cvsaya')->table('cv_hobbies')
        //     ->whereIn('user_id', $unintersectedUserIds)->get();
        // $newCvProfileDetails = DB::connection('existing-cvsaya')->table('cv_profile_details')
        //     ->whereIn('user_id', $unintersectedUserIds)->get();
        // $newCvSocialMedias = DB::connection('existing-cvsaya')->table('cv_social_medias')
        //     ->whereIn('user_id', $unintersectedUserIds)->get();
        // $newCvCertifications = DB::connection('existing-cvsaya')->table('cv_certifications')
        //     ->whereIn('user_id', $unintersectedUserIds)->get();
        // $newCvSpecialities = DB::connection('existing-cvsaya')->table('cv_specialities')
        //     ->whereIn('user_id', $unintersectedUserIds)->get();
        // // $newCvSpecialitiesCertifications = DB::connection('existing-cvsaya')->table('cv_specialities_certifications')
        // //     ->whereIn('user_id', $unintersectedUserIds)->get();

        // $addCandidates = [];
        // $addCandidatePositions = [];
        // $addDocuments = [];
        // $addCvDocuments = [];
        // $addCvCertifications = [];
        // $addCvDomiciles = [];
        // $addCvEducations = [];
        // $addCvExpectedJobs = [];
        // $addCvExperiences = [];
        // $addCvHobbies = [];
        // $addCvProfileDetails = [];
        // $addCvSocialMedias = [];
        // $addCvSpecialities = [];
        // $addCvSpecialitiesCertifications = [];

        // foreach ($newCandidates as $newCandidate) {
        //     $candidateId = $newCandidate->id;
        //     $userId = $newCandidate->user_id;

        //     // Ambil 

        //     array_push($addCandidates, $newCandidate);

        //     $documents = $newDocuments->where('user_id', $userId)->all();
        //     foreach ($documents as $document) {
        //         array_push($addDocuments, $document);
        //     }

        //     $cvDocuments = $newCvDocuments->where('user_id', $userId)->all();
        //     foreach ($cvDocuments as $cvDocument) {
        //         $cvDocument['candidate_id'] = $candidateId;
        //         array_push($addCvDocuments, $cvDocument);
        //     }

        //     $cvCertifications = $newCvCertifications->where('user_id', $userId)->all();
        //     foreach ($cvCertifications as $cvCertification) {
        //         $cvCertification['candidate_id'] = $candidateId;
        //         array_push($addCvCertifications, $cvCertification);
        //     }

        //     $cvDomiciles = $newCvDomiciles->where('user_id', $userId)->all();
        //     foreach ($cvDomiciles as $cvDomicile) {
        //         $cvDomicile['candidate_id'] = $candidateId;
        //         array_push($addCvDomiciles, $cvDomicile);
        //     }

        //     $cvEducations = $newCvEducations->where('user_id', $userId)->all();
        //     foreach ($cvEducations as $cvEducation) {
        //         $cvEducation['candidate_id'] = $candidateId;
        //         array_push($addCvEducations, $cvEducation);
        //     }

        //     $cvExpectedJobs = $newCvExpectedJobs->where('user_id', $userId)->all();
        //     foreach ($cvExpectedJobs as $cvExpectedJob) {
        //         $cvExpectedJob['candidate_id'] = $candidateId;
        //         $existingPositionId = $cvExpectedJob['expected_position'];
        //         if ($existingPositionId) {
        //             $existingCandidatePosition = $existingCandidatePositions->where('id', $existingPositionId)->first();
        //             $newCandidatePosition = $candidatePositions->where('name', $existingCandidatePosition->name)->first();
        //             $cvExpectedJob['expected_position'] = $newCandidatePosition->id;
        //         }
        //         array_push($addCvExpectedJobs, $cvExpectedJob);
        //     }

        //     $cvExperiences = $newCvExperiences->where('user_id', $userId)->all();
        //     foreach ($cvExperiences as $cvExperience) {
        //         $cvExperience['candidate_id'] = $candidateId;
        //         $existingPositionId = $cvExperience['position_id'];
        //         if ($existingPositionId) {
        //             $existingCandidatePosition = $existingCandidatePositions->where('id', $existingPositionId)->first();
        //             $newCandidatePosition = $candidatePositions->where('name', $existingCandidatePosition->name)->first();
        //             $cvExperience['position_id'] = $newCandidatePosition->id;
        //         }
        //         array_push($addCvExperiences, $cvExperience);
        //     }

        //     $cvHobbies = $newCvHobbies->where('user_id', $userId)->all();
        //     foreach ($cvHobbies as $cvHobby) {
        //         $cvHobby['candidate_id'] = $candidateId;
        //         array_push($addCvHobbies, $cvHobby);
        //     }

        //     $cvProfileDetails = $newCvProfileDetails->where('user_id', $userId)->all();
        //     foreach ($cvProfileDetails as $cvProfileDetail) {
        //         $cvProfileDetail['candidate_id'] = $candidateId;
        //         array_push($addCvProfileDetails, $cvProfileDetail);
        //     }

        //     $cvSocialMedias = $newCvSocialMedias->where('user_id', $userId)->all();
        //     foreach ($cvSocialMedias as $cvSocialMedias) {
        //         $cvSocialMedias['candidate_id'] = $candidateId;
        //         array_push($addCvSocialMedias, $cvSocialMedias);
        //     }

        //     $cvSpecialities = $newCvSpecialities->where('user_id', $userId)->all();
        //     foreach ($cvSpecialities as $cvSpeciality) {
        //         $cvSpeciality['candidate_id'] = $candidateId;
        //         array_push($addCvSpecialities, $cvSpeciality);
        //     }

        //     $cvSocialMedia = $newCvSocialMedias->where('user_id', $userId)->all();
        //     foreach ($cvSocialMedia as $cvSocialMedium) {
        //         $cvSocialMedium['candidate_id'] = $candidateId;
        //         array_push($addCvSocialMedias, $cvSocialMedium);
        //     }
        // }

        // DB::transaction(function () use (
        //     $addCandidates,
        //     $addCvProfileDetails,
        //     $newCandidatePositions,
        //     $domiciles,
        //     $cvExperiences,
        //     $cvEducations,
        //     $cvSpecialities,
        //     $cvHobbies,
        //     $expectedJobs,
        //     $documents,
        //     $cvDocuments,
        // ) {
        //     $chunckedCandidates = array_chunk($candidates, 1000);
        //     foreach ($chunckedCandidates as $candidates) {
        //         DB::table('candidates')->insert($candidates);
        //     }
        //     $chunckedCandidateNotes = array_chunk($candidateNotes, 1000);
        //     foreach ($chunckedCandidateNotes as $candidateNotes) {
        //         CandidateNote::insert($candidateNotes);
        //     }
        //     $chunckedProfileDetails = array_chunk($profileDetails, 1000);
        //     foreach ($chunckedProfileDetails as $profileDetails) {
        //         CvProfileDetail::insert($profileDetails);
        //     }
        //     $chunckedCandidatePositions = array_chunk($newCandidatePositions, 1000);
        //     foreach ($chunckedCandidatePositions as $candidatePositions) {
        //         DB::table('candidate_positions')->insert($candidatePositions);
        //     }
        //     $chunckedDomiciles = array_chunk($domiciles, 1000);
        //     foreach ($chunckedDomiciles as $domiciles) {
        //         CvDomicile::insert($domiciles);
        //     }
        //     $chunckedExperiences = array_chunk($cvExperiences, 1000);
        //     foreach ($chunckedExperiences as $experiences) {
        //         CvExperience::insert($experiences);
        //     }
        //     $chunckedEducations = array_chunk($cvEducations, 1000);
        //     foreach ($chunckedEducations as $educations) {
        //         CvEducation::insert($educations);
        //     }
        //     $chunckedSpecialities = array_chunk($cvSpecialities, 1000);
        //     foreach ($chunckedSpecialities as $specialities) {
        //         CvSpeciality::insert($specialities);
        //     }
        //     $chunckedHobbies = array_chunk($cvHobbies, 1000);
        //     foreach ($chunckedHobbies as $hobbies) {
        //         CvHobby::insert($hobbies);
        //     }
        //     $chunckedExpectedJobs = array_chunk($expectedJobs, 1000);
        //     foreach ($chunckedExpectedJobs as $expectedJobs) {
        //         CvExpectedJob::insert($expectedJobs);
        //     }
        //     $chunckedDocuments = array_chunk($documents, 1000);
        //     foreach ($chunckedDocuments as $documents) {
        //         Document::insert($documents);
        //     }
        //     $chunckedCvDocuments = array_chunk($cvDocuments, 1000);
        //     foreach ($chunckedCvDocuments as $cvDocuments) {
        //         CvDocument::insert($cvDocuments);
        //     }
        // });
    }
}
