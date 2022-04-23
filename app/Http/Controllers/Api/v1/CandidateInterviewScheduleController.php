<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Candidate;
use App\Models\CandidateInterviewSchedule;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Models\EmployeeDetail;
use App\Models\CandidateInterviewSchedulesCharacterTrait;
use App\Models\CharacterTrait;
use Carbon\Carbon;
use App\Models\InterviewResult;

class CandidateInterviewScheduleController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $employee = EmployeeDetail::where('user_id', $user->id_kustomer)->firstOrFail();
        $request->validate([
            'started_at' => [
                'date_format:Y-m-d\TH:i:s.v\Z',
                'nullable',
                'required_with:ended_at',
            ],
            'ended_at' => [
                'date_format:Y-m-d\TH:i:s.v\Z',
                'required_with:started_at',
                'nullable',
            ],
        ]);
        $startedAt = $request->started_at;
        $endedAt = $request->ended_at;
        $candidate = CandidateInterviewSchedule::where(function ($query) use ($startedAt, $endedAt) {
            if ($startedAt || $endedAt) {
                $query->whereBetween('interviewed_at', [$startedAt, $endedAt]);
            }
        })
            ->whereNull('result_id')
            ->whereNull('rejected_at')
            ->where('interviewed_by', $employee->id)
            ->get();

        return $this->showALl($candidate);
    }

    public function getDetail($id)
    {
        $candidate = Candidate::where('user_id', $id)->firstOrFail();

        $schedules = CandidateInterviewSchedule::where('candidate_id', $candidate->id)->get();

        return $this->showAll($schedules);
    }

    public function indexWithoutInterviewDate()
    {
        $user = auth()->user();
        $employee = EmployeeDetail::where('user_id', $user->id_kustomer)->firstOrFail();

        $schedules = CandidateInterviewSchedule::
            // whereBettween('date_time',)
            whereNull('interviewed_at')
            ->where('interviewed_by', $employee->id)
            ->whereNull('result_id')
            ->whereNull('rejected_at')
            ->distinct('candidate_id')
            ->get();
        return $this->showAll($schedules);
    }

    public function assessmentInterview()
    {
        $results = InterviewResult::all();

        return $this->showAll($results);
    }

    public function indexInterviewer()
    {

        $employee = EmployeeDetail::with('roles')->get();

        Collection::macro('interviewer', function () {
            return $this->map(function ($value) {
                return [
                    'id' => $value->id,
                    'user_id' => $value->user_id,
                    'first_name' => $value->profileDetail->first_name,
                    'last_name' => $value->profileDetail->last_name
                ];
            });
        });

        $interviewers = $employee->filter(function ($employee) {
            if ($employee->hasRole('interviewer')) {
                return $employee;
            }
        });
        $interviewers = collect($interviewers)->interviewer();

        return $this->showAll($interviewers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CandidateEmpolyeeSchedule  $candidateEmpolyeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(CandidateInterviewSchedule $candidateEmpolyeeSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CandidateEmpolyeeSchedule  $candidateEmpolyeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(CandidateInterviewSchedule $candidateEmpolyeeSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CandidateEmpolyeeSchedule  $candidateEmpolyeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function isThereAnyOtherSchedule($date, $time, $interviewer)
    {
        $schedule = CandidateInterviewSchedule::where('interviewed_by', $interviewer)
            ->where('interviewed_at', date('Y-m-d H:i:s', strtotime($date . ' ' . $time)))->first();
        if ($schedule) {
            return true;
        }
        return false;
    }

    public function showNote($id)
    {
        $candidate = Candidate::where('user_id', $id)->firstOrFail();

        return $this->showOne($candidate->toArrayByNote());
    }

    public function indexCharacterTraits()
    {
        $traits = CharacterTrait::all();

        return $this->showAll($traits);
    }

    public function updateSchedule(Request $request, $id)
    {
        $request->validate([
            'interviewed_at' => 'required|date_format:Y-m-d\TH:i:s.v\Z',
        ]);
        $schedule = CandidateInterviewSchedule::where('id', $id)->firstOrFail();

        $schedule->interviewed_at = date('Y-m-d H:i:s', strtotime($request->interviewed_at));
        $schedule->save();
        return $this->showOne($schedule);
    }

    public function rejectInterview(Request $request, $id)
    {
        $request->validate([
            'note' => 'string|min:50|nullable',
        ]);
        $schedule = CandidateInterviewSchedule::where('id', $id)->whereNull('rejected_at')->firstOrFail();

        if ($schedule->candidate->status < Candidate::INTERVIEW) {
            return $this->errorResponse('candidate rejected because status not on interview', 422, 42201);
        }
        if ($request->note) {
            $schedule->note = $request->note;
        }

        $schedule->rejected_at = Carbon::now();
        $schedule->save();

        return $this->showOne($schedule);
    }

    public function giveResult(Request $request, $id)
    {
        $request->validate([
            'note' => 'string|min:50|nullable',
            'result_id' => 'exists:interview_results,id|required',
            'character_traits' => 'array|nullable',
        ]);
        $schedule = CandidateInterviewSchedule::where('id', $id)->firstOrFail();
        if ($schedule->candidate->status < Candidate::INTERVIEW) {
            return $this->errorResponse('candidate cannot change to new result', 422, 42201);
        }
        $schedule->result_id = $request->result_id;
        if ($request->note) {
            $schedule->note = $request->note;
        }
        if (count($request->character_traits)) {
            foreach ($request->character_traits as $characterTrait) {
                $characterTrait = CharacterTrait::where('id', $characterTrait)->firstOrFail();
                CandidateInterviewSchedulesCharacterTrait::create([
                    'candidate_interview_schedule_id' => $schedule->id,
                    'character_trait_id' => $characterTrait->id,
                ]);
            }
        }
        $schedule->save();
        return $this->showOne($schedule);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CandidateEmpolyeeSchedule  $candidateEmpolyeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(CandidateInterviewSchedule $candidateEmpolyeeSchedule)
    {
        //
    }
}
