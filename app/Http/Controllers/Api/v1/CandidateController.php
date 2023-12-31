<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Candidate;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\CvProfileDetailController;
use App\Http\Resources\EmployeeResource;
use App\Models\CandidatePosition;
use App\Models\CandidateInterviewSchedule;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class CandidateController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $request->validate([
            'keyword' => 'nullable|string',
            'status' => 'nullable|integer',
            'country_id' => 'nullable',
            'province_id' => 'nullable',
            'city_id' => 'nullable',
            'position_id' => 'nullable|exists:App\Models\CandidatePosition,id',
            'order_by' => [
                'nullable',
                Rule::in(['DESC', 'ASC']),
            ],
            'is_reviewed' => 'nullable'
        ]);

        $name = $request->keyword;
        $phoneNumber = ltrim($request->keyword, '0');
        $status = $request->status;
        $countryId = $request->country_id;
        $provinceId = $request->province_id;
        $cityId = $request->city_id;
        $position = $request->position_id;
        $startDate = $request->started_at;
        $endDate = $request->ended_at;
        $isReviewed = $request->is_reviewed;

        $candidates = Candidate::when($startDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('registered_at', [$startDate, $endDate]);
        })
            ->where(function ($query) use ($name, $status,  $countryId, $provinceId, $cityId, $position, $isReviewed, $phoneNumber) {
                if ($name != null) {
                    $query->where('name', 'LIKE', '%' . $name . '%')
                        ->orWhere('phone_number', 'LIKE', '%' . $phoneNumber . '%');
                    if ($position == null) {
                        $query->orWhereHas('job', function ($secondQuery) use ($name) {
                            $secondQuery->whereHas('position', function ($thirdQuery) use ($name) {
                                $thirdQuery->Where('candidate_positions.name', 'LIKE', '%' . $name . '%');
                            });
                        });
                    }
                }

                if (($countryId != null) || ($provinceId != null) || ($cityId != null)) {
                    $query->whereHas('domicile', function ($secondQuery) use ($countryId, $provinceId, $cityId) {
                        if ($countryId != null) {
                            $secondQuery->where('country_id', $countryId);
                        }
                        if ($provinceId != null) {
                            $secondQuery->where('province_id', $provinceId);
                        }
                        if ($cityId != null) {
                            $secondQuery->where('city_id', $cityId);
                        }
                    });
                }

                if ($position != null) {
                    $query->whereHas('job', function ($secondQuery) use ($position) {
                        $secondQuery->where('expected_position', $position);
                    });
                }
                if ($status != null) {
                    if ($status == Candidate::READY_TO_INTERVIEW) {
                        $query->where('status', 3);
                    } else {
                        $query->where('status', $status);
                    }
                }

                if ($isReviewed !== null) {
                    if ($isReviewed) {
                        $query->has('candidateNotes');
                    } else {
                        $query->doesntHave('candidateNotes');
                    }
                }
            })
            ->orderBy('created_at', $request->input('order_by', 'desc'))
            ->paginate($request->input('page_size', 10));

        $data = [];
        foreach ($candidates as $candidate) {
            if ($status == Candidate::READY_TO_INTERVIEW) {
                $candidateController = new CvProfileDetailController;

                $status = $candidateController->getStatus($candidate->id);
                $status = $status->original;
                $status = $status['data']['completeness_status'];
                if (
                    $status['is_profile_completed'] == true &&
                    $status['is_job_completed'] == true &&
                    $status['is_document_completed']  == true &&
                    $status['is_cv_completed'] == true
                ) {
                    $data[] = $candidate->listDefaultCandidate();
                }
            } else {
                $data[] = $candidate->listDefaultCandidate();
            }
        }
        return $this->showPagination('candidates', $candidates);
    }

    public function indexDetail(Request $request, $id)
    {
        $candidate = Candidate::where('id', $id)->firstOrFail();
        return $this->showOne($candidate->listDefaultCandidate());
    }

    public function getSummaryByDay(Request $request)
    {
        $startDate = $request->started_at ?? null;
        $endDate = $request->ended_at ?? null;
        $candidateQuery = Candidate::when($startDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        });
        $totalCandidate = $candidateQuery->count();
        $interviewedCandidates = $candidateQuery->has('candidateNotes')->count();
        $data = [
            'total' => $totalCandidate,
            'interviewed' => $interviewedCandidates,
        ];

        return $this->showOne($data);
    }

    public function getPosition(Request $request)
    {
        $request->validate([
            'keyword' => 'nullable',
            'page' => 'nullable|numeric|gt:0',
            'page_size' => 'nullable|numeric|gt:0',
        ]);

        $page = $request->page ? $request->page  : 1;
        $pageSize = $request->page_size ? $request->page_size : 10;
        $keyword = $request->keyword;
        $startDate = $request->started_at ?? null;
        $endDate = $request->ended_at ?? null;
        $result = [];
        $positions = CandidatePosition::where(function ($query) use ($keyword) {
            if ($keyword != null) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            }
        })->orderBy('name')->whereNotNull('validated_at')
            ->paginate(
                $pageSize,
                ['*'],
                'page',
                $page
            );
        foreach ($positions as $position) {
            $result[] = [
                'id' => $position->id,
                'name' => $position->name,
                'statistics' => $this->getCount($position, $startDate, $endDate),
            ];
        }

        return $this->showPaginate('positions', collect($result), collect($positions));
    }

    public function getUncategorizedPosition(Request $request)
    {
        $request->validate([
            'keyword' => 'nullable',
            'page' => 'nullable|numeric|gt:0',
            'page_size' => 'nullable|numeric|gt:0',
        ]);

        $page = $request->page ? $request->page  : 1;
        $pageSize = $request->page_size ? $request->page_size : 10;
        $keyword = $request->keyword;
        $startDate = $request->started_at ?? null;
        $endDate = $request->ended_at ?? null;
        $result = [];
        $positions = CandidatePosition::where(function ($query) use ($keyword) {
            if ($keyword != null) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            }
        })->orderBy('name')->whereNull('validated_at')
            ->paginate(
                $pageSize,
                ['*'],
                'page',
                $page
            );
        foreach ($positions as $position) {
            $result[] = [
                'id' => $position->id,
                'name' => $position->name,
                'applicant' => $position->getTotalApplicant($startDate, $endDate),
            ];
        }

        return $this->showPaginate('positions', collect($result), collect($positions));
    }

    public function getCount($position, $startDate, $endDate)
    {
        $data['total'] = $position->getTotalCandidates($startDate, $endDate);
        $data['interviewed'] = $position->getTotalInterviewedCandidates($startDate, $endDate);

        return $data;
    }

    public function updateStatus(Request $request, $id)
    {
        $user = auth()->user();
        $request->validate([
            'status' => 'integer|required',
        ]);

        $candidate = Candidate::where('id', $id)->firstOrFail();

        if ($candidate->user_id == $user->id_kustomer) {
            return $this->errorResponse('Candidate cannot update his own status', 422, 42204);
        }

        if ($request->status < Candidate::INTERVIEW) {
            return $this->errorResponse('candidate cannot change with that status', 422, 42202);
        }

        if (!$candidate->label() && count($candidate->schedules)) {
            return $this->errorResponse('Candidate has not finish old schedule yet', 422, 42203);
        }

        if ($request->status == Candidate::INTERVIEW) {
            $request->validate([
                'interviewed_at' => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
                'interviewed_by' => 'integer|exists:employees,id',
            ]);

            $candidateController = new CvProfileDetailController;

            $status = $candidateController->getStatus($candidate->id);
            $status = $status->original;
            $status = $status['data']['completeness_status'];
            if (
                $candidate->status != Candidate::INTERVIEW &&
                $status['is_profile_completed'] == false &&
                $status['is_job_completed'] == false &&
                $status['is_document_completed']  == false &&
                $status['is_cv_completed'] == false
            ) {
                return $this->errorResponse('this Candidate cannot going interview', 422, 42201);
            }
            $data = $request->all();
            if ($request->interviewed_at) {
                $data['interviewed_at'] = date('Y-m-d H:i:s', strtotime($data['interviewed_at']));
            } else {
                $data['interviewed_at'] = null;
            }
            $data['candidate_id'] = $id;

            CandidateInterviewSchedule::create($data);
        }

        $candidate->status = $request->status;
        $candidate->save();
        return $this->showOne($candidate);
    }

    public function addSchedule(Request $request, $id)
    {
        $user = auth()->user();
        $request->validate([
            'interviewed_at' => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'interviewed_by' => 'integer|exists:employees,id',
        ]);

        $candidate = Candidate::where('id', $id)->firstOrFail();
        $employee = Employee::where('id', $request->interviewed_by)->firstOrFail();
        if ($employee->user_id == $candidate->user_id) {
            return $this->errorResponse('Candidate cannot set own Interviewer', 422, 42201);
        }

        if ($candidate->status >= Candidate::INTERVIEW) {
            if ($candidate->user_id == $user->id_kustomer) {
                return $this->errorResponse('Candidate cannot update his own status', 422, 42202);
            }

            if (!$candidate->label() && count($candidate->schedules)) {
                return $this->errorResponse('Candidate has not finish old schedule yet', 422, 42203);
            }
        } else {
            $candidateController = new CvProfileDetailController;

            $status = $candidateController->getStatus($candidate->id);
            $status = $status->original;
            $status = $status['data']['completeness_status'];
            if (
                $candidate->status != Candidate::INTERVIEW &&
                $status['is_profile_completed'] == false &&
                $status['is_job_completed'] == false &&
                $status['is_document_completed']  == false &&
                $status['is_cv_completed'] == false
            ) {
                return $this->errorResponse('this Candidate cannot going interview', 422, 42204);
            }
            $candidate->status = $request->status;
            $candidate->save();
        }
        $data = $request->all();
        if ($request->interviewed_at) {
            $data['interviewed_at'] = date('Y-m-d H:i:s', strtotime($data['interviewed_at']));
        } else {
            $data['interviewed_at'] = null;
        }
        $data['candidate_id'] = $id;

        CandidateInterviewSchedule::create($data);

        $candidate->refresh();

        return $this->showOne($candidate);
    }

    public function getCompletenessStatus()
    {
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $userProfileDetail = $candidate->profile;
        $document = $candidate->document;
        $expectedJob = $candidate->job;

        $data['is_profile_completed'] = 0;
        $data['is_job_completed'] = 0;
        $data['is_document_completed'] = 0;
        $data['is_cv_completed'] = 0;
        // this is because withDefault();
        // profile
        $profileCompletedTotal = 0;
        $profileCompletedScore = 0;

        $profileCompletedTotal += 3;
        if ($userProfileDetail->id != null) {
            $profileCompletedScore++;
        }
        if ($candidate->domicile->id != null) {
            $profileCompletedScore++;
        }
        if ($candidate->sosmeds->count() != 0) {
            $profileCompletedScore++;
        }

        $data['is_profile_completed'] = ceil($profileCompletedScore / $profileCompletedTotal * 100);

        // job
        $jobCompletedTotal = 0;
        $jobCompletedScore = 0;

        $jobCompletedTotal += 1;
        if ($expectedJob) {
            if ($expectedJob->expected_salary) {
                $jobCompletedScore++;
            }
        }
        $data['is_job_completed'] = ceil($jobCompletedScore / $jobCompletedTotal * 100);

        // cv
        $cvCompletedTotal = 0;
        $cvCompletedScore = 0;

        $cvCompletedTotal += 5;
        if ($candidate->educations->count() != 0) {
            $cvCompletedScore++;
        }

        if ($candidate->experiences->count() != 0) {
            $cvCompletedScore++;
        }

        if ($candidate->certifications->count() != 0) {
            $cvCompletedScore++;
        }

        if ($candidate->specialities->count() != 0) {
            $cvCompletedScore++;
        }

        if ($candidate->hobbies->count() != 0) {
            $cvCompletedScore++;
        }

        $data['is_cv_completed'] = ceil($cvCompletedScore / $cvCompletedTotal * 100);

        // document
        $documentCompletedTotal = 0;
        $documentCompletedScore = 0;

        $documentCompletedTotal += 4;
        if ($document) {
            if ($document->identity_card) {
                $documentCompletedScore++;
            }

            if ($document->front_selfie) {
                $documentCompletedScore++;
            }

            if ($document->right_selfie) {
                $documentCompletedScore++;
            }

            if ($document->left_selfie) {
                $documentCompletedScore++;
            }
        }

        $data['is_document_completed'] = ceil($documentCompletedScore / $documentCompletedTotal * 100);

        // note => need to change to name next release
        $result['basic_profile'] = [
            'first_name' => $candidate->name ?? null,
            'profile_picture_url' => $candidate->getProfilePictureUrl(),
        ];

        $employees = Employee::where('candidate_id', $candidate->id)->get();
        $result['employees'] = EmployeeResource::collection($employees);

        $result['completeness_status'] = $data;

        // TODO: better approach using relationship
        // $employee = Employee::where('user_id', auth()->id())
        //     ->with([
        //         'position' => function ($query) {
        //             $query->select(['id', 'name', 'company_id']);
        //         },
        //         'position.company' => function ($query) {
        //             $query->select(['id', 'name']);
        //         }
        //     ])
        //     ->first(['id', 'position_id']);

        return $this->showOne($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function show(Candidate $candidate)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function edit(Candidate $candidate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Candidate  $Candidate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Candidate $candidate)
    {
        //
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'file' => 'file|required',
        ]);

        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        // delete old image
        Storage::disk('public')->delete('images/profile_picture/' . $candidate->profile_picture);

        $image = $request->file;
        $img = Image::make($image)->encode($image->extension(), 70);
        $fileName = time() . '.' . $image->extension();
        $candidate->update(['profile_picture' => $fileName]);
        Storage::disk('public')->put('images/profile_picture/' . $fileName, $img);

        return $this->showOne($candidate);
    }

    public function updateCandidateName(Request $request)
    {
        $request->validate(['name' => 'required']);

        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $candidate->update(['name' => $request->name]);

        return $this->showOne($candidate);
    }
}
