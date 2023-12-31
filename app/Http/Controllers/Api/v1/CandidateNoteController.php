<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\CandidateNoteVisibility;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCandidateNoteRequest;
use App\Models\Candidate;
use App\Models\CandidateNote;
use App\Models\Employee;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CandidateNoteController extends Controller
{
    use ApiResponser;

    public function getCandidateNotes(Request $request, $candidateId)
    {
        $page = $request->page ? $request->page : 1;
        $pageSize = $request->page_size ? $request->page_size : 10;
        $userId = auth()->user()->id_kustomer;

        $candidate = Candidate::findOrFail($candidateId);
        $interviewer = Candidate::where('user_id', $userId)->firstOrFail();
        $employee = Employee::where('candidate_id', $interviewer->id)->firstOrFail();

        if ($userId == $candidate->user_id) {
            return $this->errorResponse('Data not found', 404, 40400);
        }

        $candidateNoteQuery = CandidateNote::where('candidate_id', $candidateId);

        if ($request->visibility == null) {
            $candidateNoteQuery->where('visibility', CandidateNoteVisibility::public())
                ->orWhere(function ($query) use ($employee, $candidateId) {
                    $query->where('visibility', CandidateNoteVisibility::private())
                        ->where('candidate_id', $candidateId)
                        ->where('employee_id', $employee->id);
                });
        } else {
            if ($request->visibility == CandidateNoteVisibility::private()) {
                $candidateNoteQuery->where('employee_id', $employee->id);
            }
            $candidateNoteQuery->where('visibility', $request->visibility);
        }

        $candidateNotes = $candidateNoteQuery
            ->with('candidate', 'employee.candidate')
            ->orderBy('created_at', 'desc')
            ->paginate($pageSize);

        return $this->showPagination(
            'candidate_notes',
            $candidateNotes,
        );
    }

    public function storeCandidateNotes(StoreCandidateNoteRequest $request, $candidateId)
    {
        $userId = auth()->user()->id_kustomer;
        $candidate = Candidate::findOrFail($candidateId);

        if ($userId == $candidate->user_id) {
            return $this->errorResponse('Can\'t perform this action', 409, 40900);
        }

        $interviewer = Candidate::where('user_id', $userId)->firstOrFail();
        $employee = Employee::where('candidate_id', $interviewer->id)->firstOrFail();

        // fix status not nullable
        $candidateNote = CandidateNote::create([
            'note' => $request->note,
            'employee_id' => $employee->id,
            'candidate_id' => $candidate->id,
            'status' => '',
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
