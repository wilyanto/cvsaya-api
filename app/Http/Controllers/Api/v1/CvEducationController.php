<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CvEducation;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\Degree;

class CvEducationController extends Controller
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
        $educations = CvEducation::where('candidate_id', $candidate->id)
            ->orderBy('started_at', 'DESC')
            ->orderByRaw("CASE WHEN ended_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('ended_at', 'DESC')
            ->get();

        return $this->showAll($educations);
    }


    public function degreeList()
    {
        $degrees = Degree::whereIn('id', [1, 2, 3, 4, 5, 6, 7])->get();

        return $this->showAll($degrees);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'instance' => 'required|string',
            'degree_id' => 'exists:degrees,id|required',
            'field_of_study' => 'required|string',
            'grade' => 'required|string',
            'started_at' => 'required|date',
            'ended_at' => 'nullable|date|after:started_at',
            'description' => 'nullable|string',
        ]);

        $candidate = Candidate::where('user_id', auth()->id())->first();
        $data = $request->all();
        $data['candidate_id'] = $candidate->id;
        if (!$request->ended_at) {
            $data['ended_at'] = null;
        }
        $educations = CvEducation::create($data);
        return $this->showOne($educations);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Educations  $educations
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Educations  $educations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'instance' => 'nullable|string',
            'degree_id' => 'exists:degrees,id|required',
            'field_of_study' => 'nullable|string',
            'grade' => 'nullable|string',
            'started_at' => 'nullable|date',
            'ended_at' => 'nullable|date|after:started_at',
            'description' => 'nullable|string',
        ]);

        $candidate = Candidate::where('user_id', auth()->id())->first();
        $data = $request->all();
        $data['candidate_id'] = $candidate->id;
        if (!$request->started_at) {
            $data['started_at'] = null;
        }
        if ($request->ended_at != null) {
            $data['ended_at'] = null;
        }
        $education = CvEducation::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->first();
        if (!$education) {
            return $this->errorResponse('id not found', 404, 40401);
        }

        $education->update($data);

        return $this->showOne($education);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Educations  $educations
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $educations = CvEducation::where('id', $id)->first();
        if (!$educations) {
            return $this->errorResponse('id not found', 404, 40401);
        }
        $educations->delete();

        return $this->showOne(null);
    }
}
