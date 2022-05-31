<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CandidatePosition;
use App\Models\CvExpectedJob;
use App\Models\CvExperience;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CandidatePositionController extends Controller
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
            'keyword' => 'string|nullable',
            'is_verified' => 'nullable|boolean',
            'page' => 'nullable|numeric|gt:0',
            'page_size' => 'nullable|numeric|gt:0'
        ]);
        $keyword = $request->keyword;
        $isVerified = $request->is_verified;
        $page = $request->page ? $request->page  : 1;
        $pageSize = $request->page_size ? $request->page_size : 10;
        $candidatePositions = CandidatePosition::where(function ($query) use ($keyword, $isVerified) {
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
        })->paginate(
            $pageSize,
            ['*'],
            'page',
            $page
        );

        return $this->showPagination('candidate_positions', $candidatePositions);
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
            'name' => 'string',
        ]);

        $data = $request->all();
        $data['validated_at'] = Carbon::now();
        $position = CandidatePosition::create($data);

        return $this->showOne($position);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidatePosition = CandidatePosition::where('id', $id)->firstOrFail();
        return $this->showOne($candidatePosition);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string',
        ]);

        $candidatePositionId = $request->candidate_position_id;

        if ($candidatePositionId != null) {
            CvExpectedJob::where('expected_position', $id)->update([
                'expected_position' => $candidatePositionId,
            ]);
            CvExperience::where('position_id', $id)->update([
                'position_id' => $candidatePositionId,
            ]);

            $oldCandidatePosition = CandidatePosition::findOrFail($id);
            $oldCandidatePosition->delete();

            return $this->showOne($candidatePositionId);
        }

        $candidatePosition = CandidatePosition::find($id)
            ->update([$request->all(), 'validated_at' => now()]);

        return $this->showOne($candidatePosition);
    }

    public function verified($id)
    {
        $validate = CandidatePosition::findOrFail($id);
        $validate->validated_at = Carbon::now();
        $validate->save();

        return $this->showOne($validate);
    }

    public function unverified($id)
    {
        $validate = CandidatePosition::findOrFail($id);
        $validate->validated_at = null;
        $validate->save();

        return $this->showOne($validate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
