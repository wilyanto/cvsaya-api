<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCandidateNoteRequest;
use App\Models\Candidate;
use App\Models\CandidateNote;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CandidateNoteController extends Controller
{
    use ApiResponser;

    public function getCandidateNotes($candidateId)
    {
        $userId = auth()->user()->id_kustomer;
        $candidate = Candidate::findOrFail($candidateId);

        if ($userId == $candidate->id) {
            return $this->errorResponse('Data not found', 404, 40400);
        }

        $candidateNotes = CandidateNote::where('candidate_id', $candidateId)
            ->where('visibility', 'public')
            ->orWhere(function ($query) use ($userId, $candidateId) {
                $query->where('visibility', 'private')
                    ->where('candidate_id', $candidateId)
                    ->where('employee_id', $userId);
            })
            ->with('employeeProfileDetail', function ($query) {
                $query->select(['first_name', 'last_name']);
            })
            ->get();

        return $this->showAll($candidateNotes);
    }

    public function storeCandidateNotes(StoreCandidateNoteRequest $request, $candidateId)
    {
        $userId = auth()->user()->id_kustomer;
        $candidate = Candidate::findOrFail($candidateId);

        if ($userId == $candidate->id) {
            return $this->errorResponse('Can\'t perform this action', 409, 40900);
        }

        $candidateNote = CandidateNote::create([
            'note' => $request->note,
            'employee_id' => $userId,
            'candidate_id' => $candidateId,
            'visibility' => $request->visibility,
        ]);

        return $this->showOne($candidateNote);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CandidateNote  $candidateNote
     * @return \Illuminate\Http\Response
     */
    public function show(CandidateNote $candidateNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CandidateNote  $candidateNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CandidateNote $candidateNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CandidateNote  $candidateNote
     * @return \Illuminate\Http\Response
     */
    public function destroy(CandidateNote $candidateNote)
    {
        //
    }
}
