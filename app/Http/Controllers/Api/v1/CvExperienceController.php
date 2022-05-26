<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CvExperienceRequest;
use App\Models\Candidate;
use App\Models\CvExperience;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\Document;
use App\Models\CandidatePosition;

class CvExperienceController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidate = Candidate::where('user_id', auth()->id())->first();
        $experiences = CvExperience::where('candidate_id', $candidate->id)
            ->orderBy('started_at', 'DESC')
            ->orderByRaw("CASE WHEN ended_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('ended_at', 'DESC')
            ->get();
        return $this->showAll($experiences);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CvExperienceRequest $request)
    {
        $candidate = Candidate::where('user_id', auth()->id())->first();
        $document = null;
        if ($request->payslip) {
            $documents = Document::where('id', $request->payslip)->firstOrFail();
            $document = $documents->id;
        }
        $data = $request->all();
        $data['candidate_id'] = $candidate->id;
        $requestPosition = json_decode($request->position);
        $position = CandidatePosition::where('id', $requestPosition->id)->orWhere('name', $requestPosition->name)->first();
        if (!$position) {
            $position = CandidatePosition::create([
                'name' => $requestPosition->name,
            ]);
        }
        $data['position_id'] = $position->id;
        if (!$request->ended_at) {
            $data['ended_at'] = null;
        }
        $data['payslip'] = $document;
        unset($data['position']);
        $experience = CvExperience::create($data);
        return $this->showOne($experience);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Experiences  $experiences
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Experiences  $experiences
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Experiences  $experiences
     * @return \Illuminate\Http\Response
     */

    public function update(CvExperienceRequest $request, $id)
    {
        $request->validated();

        $candidate = Candidate::where('user_id', auth()->id())->first();
        $experience = CvExperience::findOrFail($id)->where('candidate_id', $candidate->id);
        $requestPosition = $request->position;
        $position = CandidatePosition::where('id', $requestPosition['id'])
            ->orWhere('name', $requestPosition['name'])->first();
        if (!$position) {
            $position = CandidatePosition::create([
                'name' => $requestPosition['name'],
            ]);
        }
        $data = $request->all();
        $data['position_id'] = $position->id;
        $data['candidate_id'] = $candidate->id;
        unset($data['position']);
        $experience = $experience->fill($data);
        if ($experience->isDirty()) {
            $experience->update([$data]);
        }
        return $this->showOne($experience);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Experiences  $experiences
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = auth()->user();

        $experience = CvExperience::where('candidate_id', $id)->firstOrFail();
        if (!$experience) {
            return $this->errorResponse('id not found', 404, 40401);
        }
        $experience->delete();

        return $this->showOne(null);
    }
}
