<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CvExpectedJob;
use App\Models\CandidatePosition;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class CvExpectedJobController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidate = Candidate::findOrFail($id);
        $expectedSalaries = CvExpectedJob::where('candidate_id', $candidate->user_id)->orderBy('updated_at', 'DESC')->firstOrFail();

        return $this->showOne($expectedSalaries);
    }

    public function index()
    {
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $expectedSalaries = CvExpectedJob::where('candidate_id', $candidate->id)
            ->firstOrFail();

        return $this->showOne($expectedSalaries);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOrUpdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'expected_position' => 'required',
            'expected_salary' => 'integer|required',
            'position_reason' => 'string|required|min:30',
            'salary_reason' => 'string|required|min:30',
        ]);
        $data = $request->all();
        $data['candidate_id'] = $user->id_kustomer;
        $data['expected_position'] = json_decode($request->expected_position);
        $position = CandidatePosition::where('id', $data['expected_position']->id)->orWhere('name', $data['expected_position']->name)->first();
        if (!$position) {
            $position = new CandidatePosition();
            $position->name = $data['expected_position']->name;
            $position->save();
        }
        $data['expected_position'] = $position->id;
        $expectedSalaries = CvExpectedJob::where('candidate_id', $user->id_kustomer)->first();
        if (!$expectedSalaries) {
            $expectedSalaries = CvExpectedJob::create($data);

            return $this->showOne($expectedSalaries);
        }
        $expectedSalaries->update($data);

        return $this->showOne($expectedSalaries);
    }

    public function getListCandidatePositions(Request $request)
    {
        $request->validate([
            'keyword' => 'string|nullable',
            'is_verified' => 'nullable|boolean'
        ]);
        $keyword = $request->keyword;
        $isVerified = $request->is_verified;
        $specialities = CandidatePosition::where(function ($query) use ($keyword, $isVerified) {
            if ($keyword != null) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            }
            if (isset($isVerified)) {
                if ($isVerified) {
                    $query->whereNotNull('validated_at');
                } else {
                    $query->whereNull('validated_at');
                }
            }
        })->get();

        return $this->showAll($specialities);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpectedSalaries  $expectedSalaries
     * @return \Illuminate\Http\Response
     */
    public function verifiedCandidatePositions($id)
    {
        $validate = CandidatePosition::where('id', $id)->firstOrFail();
        $validate->validated_at = date('Y-m-d h:i:s', time());
        $validate->save();

        return $this->showOne($validate);
    }

    public function deleteVerifiedCandidatePositions($id)
    {
        $validate = CandidatePosition::where('id', $id)->firstOrFail();
        $validate->validated_at = null;
        $validate->save();

        return $this->showOne($validate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpectedSalaries  $expectedSalaries
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'candidate_position_id' => 'exists:candidate_positions,id|nullable',
            'name' => 'string',
        ]);
        $data = $request->all();
        unset($data['candidate_position_id']);
        $data['validated_at'] = date('Y-m-d h:i:s', time());
        $position = CandidatePosition::findOrFail($id);
        if ($request->candidate_position_id) {
            $newPosition = CandidatePosition::findOrFail($request->candidate_position_id);
            CvExpectedJob::where('expected_position', $id)->update([
                'expected_position' => $newPosition->id,
            ]);
            $position = $newPosition;
        } else {
            $position = CandidatePosition::where('id', $id)->update($data);
        }

        return $this->showOne($position);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpectedSalaries  $expectedSalaries
     * @return \Illuminate\Http\Response
     */
    public function destroy(CvExpectedJob $expectedSalaries)
    {
        //
    }
}
