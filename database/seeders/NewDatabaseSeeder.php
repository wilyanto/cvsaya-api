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
use App\Models\CvSpeciality;
use App\Models\Document;
use Exception;
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

        // Log::info('Start migrate new candidate positions');
        // $addCandidatePositions = [];
        // $currentCandidatePositions = CandidatePosition::all();
        // $existingCandidatePositions = DB::connection('existing-cvsaya')
        //     ->table('candidate_positions')->get();
        // $currentCandidatePositions = CandidatePosition::all();
        // $existingCandidatePositions = DB::connection('existing-cvsaya')
        //     ->table('candidate_positions')->get();

        // $currentCandidatePositionNames = $currentCandidatePositions->pluck('name');
        // $existingCandidatePositionNames = $existingCandidatePositions->pluck('name');

        // $currentCandidatePositionNames = $currentCandidatePositionNames->map(function ($item) {
        //     if ($item !== null || $item !== '') {
        //         return trim(strtolower($item));
        //     }
        // });

        // $existingCandidatePositionNames = $existingCandidatePositionNames->map(function ($item) {
        //     if ($item !== null || $item !== '') {
        //         return trim(strtolower($item));
        //     }
        // });

        // $unintersectedCandidatePositionNames = array_diff($currentCandidatePositionNames->toArray(), $existingCandidatePositionNames->toArray());
        // $unintersectedCandidatePositionNames = array_unique($unintersectedCandidatePositionNames);

        // Log::info('Existing: ' . count($currentCandidatePositionNames));
        // Log::info('Current: ' . count($existingCandidatePositionNames));
        // Log::info('Diff: ' . count($unintersectedCandidatePositionNames));

        // foreach ($unintersectedCandidatePositionNames as $unintersectedCandidatePositionName) {
        //     if ($unintersectedCandidatePositionName !== null && $unintersectedCandidatePositionName !== '') {
        //         array_push(
        //             $addCandidatePositions,
        //             [
        //                 'name' => ucwords($unintersectedCandidatePositionName),
        //                 'created_at' => now(),
        //                 'updated_at' => now(),
        //             ]
        //         );
        //         Log::info($unintersectedCandidatePositionName);
        //     }
        // }

        // $chunckedPositions = array_chunk($addCandidatePositions, 1000);
        // foreach ($chunckedPositions as $chunckedPositionsVal) {
        //     CandidatePosition::insert($chunckedPositionsVal);
        // }
        // Log::info('End migrate new candidate positions');

        $candidatePositions = CandidatePosition::all();
        $candidateNotes = CandidateNote::all();

        $existingCandidates = DB::connection('existing-cvsaya')->table('candidates')
            ->where('user_id', '>=', 30000)
            ->get();

        $candidates = Candidate::where('user_id', '!=', null)->get();

        $existingUserIds = $existingCandidates->pluck('user_id');
        $currentUserIds = $candidates->pluck('user_id');
        $unintersectedUserIds = array_diff($existingUserIds->toArray(), $currentUserIds->toArray());

        // $newCandidates = whereIn('user_id', $unintersectedUserIds)->get();
        $newCandidates = $existingCandidates->whereIn('user_id', $unintersectedUserIds);

        Log::info('Existing: ' . count($existingCandidates));
        Log::info('Current: ' . count($candidates));
        Log::info('Diff: ' . count($unintersectedUserIds));

        $existingCandidatePositions = DB::connection('existing-cvsaya')
            ->table('candidate_positions')->get();
        $newDocuments = DB::connection('existing-cvsaya')->table('documents')
            ->whereIn('user_id', $unintersectedUserIds)->get();
        $newCvDocuments = DB::connection('existing-cvsaya')->table('cv_documents')
            ->whereIn('user_id', $unintersectedUserIds)->get();
        $newCvDomiciles = DB::connection('existing-cvsaya')->table('cv_domiciles')
            ->whereIn('user_id', $unintersectedUserIds)->get();
        $newCvEducations = DB::connection('existing-cvsaya')->table('cv_educations')
            ->leftJoin('degrees', 'cv_educations.degree_id', '=', 'degrees.id')
            ->whereIn('user_id', $unintersectedUserIds)->get();
        $newCvExpectedJobs = DB::connection('existing-cvsaya')->table('cv_expected_jobs')
            ->whereIn('user_id', $unintersectedUserIds)->get();
        $newCvExperiences = DB::connection('existing-cvsaya')->table('cv_experiences')
            ->whereIn('user_id', $unintersectedUserIds)->get();
        $newCvHobbies = DB::connection('existing-cvsaya')->table('cv_hobbies')
            ->whereIn('user_id', $unintersectedUserIds)->get();
        $newCvProfileDetails = DB::connection('existing-cvsaya')->table('cv_profile_details')
            ->whereIn('user_id', $unintersectedUserIds)->get();
        $newCvSocialMedias = DB::connection('existing-cvsaya')->table('cv_social_medias')
            ->whereIn('user_id', $unintersectedUserIds)->get();
        $newCvCertifications = DB::connection('existing-cvsaya')->table('cv_certifications')
            ->whereIn('user_id', $unintersectedUserIds)->get();
        $newCvSpecialities = DB::connection('existing-cvsaya')->table('cv_specialities')
            ->whereIn('user_id', $unintersectedUserIds)->get();
        $newCvSpecialitiesCertifications = DB::connection('existing-cvsaya')->table('cv_specialities_certifications')
            ->get();

        $addCandidates = [];
        $addCandidatePositions = [];
        $addDocuments = [];
        $addCvDocuments = [];
        $addCvCertifications = [];
        $addCvDomiciles = [];
        $addCvEducations = [];
        $addCvExpectedJobs = [];
        $addCvExperiences = [];
        $addCvHobbies = [];
        $addCvProfileDetails = [];
        $addCvSocialMedias = [];
        $addCvSpecialities = [];
        $addCvSpecialitiesCertifications = [];

        Log::info(count($newCandidates));

        $lastCandidateId = Candidate::all()->count();

        try {
            DB::transaction(function () use (
                $lastCandidateId,
                $existingCandidatePositions,
                $candidatePositions,
                $addCandidates,
                $newCandidates,
                $addDocuments,
                $newDocuments,
                $addCvDocuments,
                $newCvDocuments,
                $addCvCertifications,
                $newCvCertifications,
                $addCvDomiciles,
                $newCvDomiciles,
                $addCvEducations,
                $newCvEducations,
                $addCvExpectedJobs,
                $newCvExpectedJobs,
                $addCvExperiences,
                $newCvExperiences,
                $addCvSpecialities,
                $newCvSpecialities,
                $addCvHobbies,
                $newCvHobbies,
                $addCvProfileDetails,
                $newCvProfileDetails,
                $addCvSocialMedias,
                $newCvSocialMedias,
                $newCvSpecialitiesCertifications,
            ) {
                foreach ($newCandidates as $newCandidate) {
                    $lastCandidateId++;
                    Log::info(json_encode($newCandidate));
                    $candidateId = $lastCandidateId;
                    $userId = $newCandidate->user_id;

                    array_push($addCandidates, [
                        'id' => $candidateId,
                        'user_id' => $userId,
                        'name' => $newCandidate->name,
                        'country_code' => $newCandidate->country_code,
                        'phone_number' => $newCandidate->phone_number,
                        'status' => $newCandidate->status,
                        'registered_at' => $newCandidate->registered_at,
                        'created_at' => $newCandidate->created_at,
                        'updated_at' => $newCandidate->updated_at,
                    ]);
                    // array_push($addCandidates, (array) $newCandidate);
                    $documents = $newDocuments->where('user_id', $userId)->all();
                    foreach ($documents as $document) {
                        Log::info(json_encode($document));
                        $documentFound = array_filter($addDocuments, function ($item) use ($document) {
                            return $item['id'] === $document->id;
                        });
                        if (!$documentFound) array_push($addDocuments, (array) $document);
                    }

                    $cvDocuments = $newCvDocuments->where('user_id', $userId)->all();
                    foreach ($cvDocuments as $cvDocument) {
                        $cvDocument->candidate_id = $candidateId;
                        unset($cvDocument->id);
                        unset($cvDocument->user_id);
                        $createdAt = date('Y-m-d', strtotime($cvDocument->created_at));
                        if ($createdAt === '0000-00-00 00:00:00' || !$createdAt) {
                            $cvDocument->created_at = '1970-01-01';
                        }
                        $updatedAt = date('Y-m-d', strtotime($cvDocument->updated_at));
                        if ($updatedAt === '0000-00-00 00:00:00' || !$updatedAt) {
                            $cvDocument->updated_at = '1970-01-01';
                        }
                        array_push($addCvDocuments, (array) $cvDocument);
                    }

                    $cvCertifications = $newCvCertifications->where('user_id', $userId)->all();
                    foreach ($cvCertifications as $cvCertification) {
                        $cvCertification->candidate_id = $candidateId;
                        unset($cvCertification->id);
                        unset($cvCertification->user_id);
                        $createdAt = date('Y-m-d', strtotime($cvCertification->created_at));
                        if ($createdAt === '0000-00-00 00:00:00' || !$createdAt) {
                            $cvCertification->created_at = '1970-01-01';
                        }
                        $updatedAt = date('Y-m-d', strtotime($cvCertification->updated_at));
                        if ($updatedAt === '0000-00-00 00:00:00' || !$updatedAt) {
                            $cvCertification->updated_at = '1970-01-01';
                        }
                        array_push($addCvCertifications, (array) $cvCertification);
                    }

                    $cvDomiciles = $newCvDomiciles->where('user_id', $userId)->all();
                    foreach ($cvDomiciles as $cvDomicile) {
                        $cvDomicile->candidate_id = $candidateId;
                        unset($cvDomicile->id);
                        unset($cvDomicile->user_id);
                        $createdAt = date('Y-m-d', strtotime($cvDomicile->created_at));
                        if ($createdAt === '0000-00-00 00:00:00' || !$createdAt) {
                            $cvDomicile->created_at = '1970-01-01';
                        }
                        $updatedAt = date('Y-m-d', strtotime($cvDomicile->updated_at));
                        if ($updatedAt === '0000-00-00 00:00:00' || !$updatedAt) {
                            $cvDomicile->updated_at = '1970-01-01';
                        }
                        array_push($addCvDomiciles, (array) $cvDomicile);
                    }

                    $cvEducations = $newCvEducations->where('user_id', $userId)->all();
                    foreach ($cvEducations as $cvEducation) {
                        $cvEducation->candidate_id = $candidateId;
                        unset($cvEducation->id);
                        unset($cvEducation->user_id);
                        $createdAt = date('Y-m-d', strtotime($cvEducation->created_at));
                        if ($createdAt === '0000-00-00 00:00:00' || !$createdAt) {
                            $cvEducation->created_at = '1970-01-01';
                        }
                        $updatedAt = date('Y-m-d', strtotime($cvEducation->updated_at));
                        if ($updatedAt === '0000-00-00 00:00:00' || !$updatedAt) {
                            $cvEducation->updated_at = '1970-01-01';
                        }
                        if ($cvEducation->degree_id !== NULL && $cvEducation->degree_id <= 6) {
                            array_push($addCvEducations, [
                                'candidate_id' => $cvEducation->candidate_id,
                                'instance' => $cvEducation->instance,
                                'field_of_study' => $cvEducation->field_of_study,
                                'degree_id' => $cvEducation->degree_id,
                                'grade' => $cvEducation->grade,
                                'started_at' => $cvEducation->started_at,
                                'ended_at' => $cvEducation->ended_at,
                                'description' => $cvEducation->description,
                                'created_at' => $cvEducation->created_at,
                                'updated_at' => $cvEducation->updated_at,
                            ]);
                        }
                    }

                    $cvExpectedJobs = $newCvExpectedJobs->where('user_id', $userId)->all();
                    foreach ($cvExpectedJobs as $cvExpectedJob) {
                        $cvExpectedJob->candidate_id = $candidateId;
                        $existingPositionId = $cvExpectedJob->expected_position;
                        if ($existingPositionId) {
                            $existingCandidatePosition = $existingCandidatePositions->where('id', $existingPositionId)->first();
                            if ($existingCandidatePosition) {
                                $newCandidatePosition = $candidatePositions->where('name', trim(strtolower($existingCandidatePosition->name)))->first();
                                if ($newCandidatePosition) {
                                    $cvExpectedJob->expected_position = $newCandidatePosition->id;
                                } else {
                                    $candidatePosition = CandidatePosition::create([
                                        'name' => ucwords($existingCandidatePosition->name),
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ]);
                                    $candidatePositions->push($candidatePosition);
                                    $cvExpectedJob->expected_position = $candidatePosition->id;
                                }
                            }
                        }
                        unset($cvExpectedJob->id);
                        unset($cvExpectedJob->user_id);
                        array_push($addCvExpectedJobs, (array) $cvExpectedJob);
                    }

                    $cvExperiences = $newCvExperiences->where('user_id', $userId)->all();
                    foreach ($cvExperiences as $cvExperience) {
                        $cvExperience->candidate_id = $candidateId;
                        $existingPositionId = $cvExperience->position_id;
                        if ($existingPositionId) {
                            $existingCandidatePosition = $existingCandidatePositions->where('id', $existingPositionId)->first();
                            if ($existingCandidatePosition) {
                                $newCandidatePosition = $candidatePositions->where('name', trim(strtolower($existingCandidatePosition->name)))->first();
                                if ($newCandidatePosition) {
                                    $cvExperience->position_id = $newCandidatePosition->id;
                                } else {
                                    $candidatePosition = CandidatePosition::create([
                                        'name' => ucwords($existingCandidatePosition->name),
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ]);
                                    $candidatePositions->push($candidatePosition);
                                    $cvExperience->position_id = $candidatePosition->id;
                                }
                            }
                        }
                        unset($cvExperience->id);
                        unset($cvExperience->user_id);
                        array_push($addCvExperiences, (array) $cvExperience);
                    }

                    $cvHobbies = $newCvHobbies->where('user_id', $userId)->all();
                    foreach ($cvHobbies as $cvHobby) {
                        $cvHobby->candidate_id = $candidateId;
                        unset($cvHobby->id);
                        unset($cvHobby->user_id);
                        $createdAt = date('Y-m-d', strtotime($cvHobby->created_at));
                        if ($createdAt === '0000-00-00 00:00:00' || !$createdAt) {
                            $cvHobby->created_at = '1970-01-01';
                        }
                        $updatedAt = date('Y-m-d', strtotime($cvHobby->updated_at));
                        if ($updatedAt === '0000-00-00 00:00:00' || !$updatedAt) {
                            $cvHobby->updated_at = '1970-01-01';
                        }
                        array_push($addCvHobbies, (array) $cvHobby);
                    }

                    $cvProfileDetails = $newCvProfileDetails->where('user_id', $userId)->all();
                    foreach ($cvProfileDetails as $cvProfileDetail) {
                        $cvProfileDetail->candidate_id = $candidateId;
                        unset($cvProfileDetail->id);
                        unset($cvProfileDetail->user_id);
                        $createdAt = date('Y-m-d', strtotime($cvProfileDetail->created_at));
                        if ($createdAt === '0000-00-00 00:00:00' || !$createdAt) {
                            $cvProfileDetail->created_at = '1970-01-01';
                        }
                        $updatedAt = date('Y-m-d', strtotime($cvProfileDetail->updated_at));
                        if ($updatedAt === '0000-00-00 00:00:00' || !$updatedAt) {
                            $cvProfileDetail->updated_at = '1970-01-01';
                        }
                        array_push($addCvProfileDetails, (array) $cvProfileDetail);
                    }

                    $cvSocialMedias = $newCvSocialMedias->where('user_id', $userId)->all();
                    foreach ($cvSocialMedias as $cvSocialMedia) {
                        $cvSocialMedia->candidate_id = $candidateId;
                        unset($cvSocialMedia->id);
                        unset($cvSocialMedia->user_id);
                        $createdAt = date('Y-m-d', strtotime($cvSocialMedia->created_at));
                        if ($createdAt === '0000-00-00 00:00:00' || !$createdAt) {
                            $cvSocialMedia->created_at = '1970-01-01';
                        }
                        $updatedAt = date('Y-m-d', strtotime($cvSocialMedia->updated_at));
                        if ($updatedAt === '0000-00-00 00:00:00' || !$updatedAt) {
                            $cvSocialMedia->updated_at = '1970-01-01';
                        }
                        array_push($addCvSocialMedias, (array) $cvSocialMedia);
                    }

                    $cvSpecialities = $newCvSpecialities->where('user_id', $userId)->all();
                    foreach ($cvSpecialities as $cvSpeciality) {
                        $cvSpeciality->candidate_id = $candidateId;
                        unset($cvSpeciality->id);
                        unset($cvSpeciality->user_id);
                        $createdAt = date('Y-m-d', strtotime($cvSpeciality->created_at));
                        if ($createdAt === '0000-00-00 00:00:00' || !$createdAt) {
                            $cvSpeciality->created_at = '1970-01-01';
                        }
                        $updatedAt = date('Y-m-d', strtotime($cvSpeciality->updated_at));
                        if ($updatedAt === '0000-00-00 00:00:00' || !$updatedAt) {
                            $cvSpeciality->updated_at = '1970-01-01';
                        }
                        array_push($addCvSpecialities, (array) $cvSpeciality);
                    }
                }

                // foreach ($newCvSpecialitiesCertifications as $cvSpecialitiesCertification) {

                // };

                $chunckedCandidates = array_chunk($addCandidates, 500);
                foreach ($chunckedCandidates as $candidates) {
                    Candidate::insert($candidates);
                }
                $chunckedDocuments = array_chunk($addDocuments, 1000);
                foreach ($chunckedDocuments as $documents) {
                    Document::insert($documents);
                }
                $chunckedCvDocuments = array_chunk($addCvDocuments, 1000);
                foreach ($chunckedCvDocuments as $cvDocuments) {
                    CvDocument::insert($cvDocuments);
                }
                // $chunckedCandidateNotes = array_chunk($candidateNotes, 1000);
                // foreach ($chunckedCandidateNotes as $candidateNotes) {
                //     CandidateNote::insert($candidateNotes);
                // }
                $chunckedProfileDetails = array_chunk($addCvProfileDetails, 1000);
                foreach ($chunckedProfileDetails as $profileDetails) {
                    CvProfileDetail::insert($profileDetails);
                }
                // $chunckedCandidatePositions = array_chunk($addCandidatePositions, 1000);
                // foreach ($chunckedCandidatePositions as $candidatePositions) {
                //     DB::table('candidate_positions')->insert($candidatePositions);
                // }
                $chunckedDomiciles = array_chunk($addCvDomiciles, 1000);
                foreach ($chunckedDomiciles as $domiciles) {
                    CvDomicile::insert($domiciles);
                }
                $chunckedExperiences = array_chunk($addCvExperiences, 1000);
                foreach ($chunckedExperiences as $experiences) {
                    CvExperience::insert($experiences);
                }
                $chunckedEducations = array_chunk($addCvEducations, 1000);
                foreach ($chunckedEducations as $educations) {
                    CvEducation::insert($educations);
                }
                $chunckedSpecialities = array_chunk($addCvSpecialities, 1000);
                foreach ($chunckedSpecialities as $specialities) {
                    CvSpeciality::insert($specialities);
                }
                $chunckedHobbies = array_chunk($addCvHobbies, 1000);
                foreach ($chunckedHobbies as $hobbies) {
                    CvHobby::insert($hobbies);
                }
                $chunckedExpectedJobs = array_chunk($addCvExpectedJobs, 1000);
                foreach ($chunckedExpectedJobs as $expectedJobs) {
                    CvExpectedJob::insert($expectedJobs);
                }
            });
        } catch (Exception $e) {
            Log::info('Error :' . $e);
        }




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
