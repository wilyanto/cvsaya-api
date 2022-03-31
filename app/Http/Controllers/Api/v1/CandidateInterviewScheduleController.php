<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\api\v1\CandidateController;
use App\Models\Candidate;
use App\Models\CandidateInterviewSchedule;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\CvProfileDetailController;
use App\Models\CvExpectedSalary;
use App\Models\EmployeeDetail;
use App\Models\CandidateInterviewSchedulesCharacterTrait;
use App\Models\CharacterTrait;
use App\Models\Position;
use Spatie\Permission\Models\Role;
use App\Models\InterviewResult;
use DateTime;
use DateInterval;
use DatePeriod;

class CandidateEmpolyeeScheduleController extends Controller
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

        if ($request->start_at) {
            return $this->indexByDate($request);
        }
        $candidate = CandidateInterviewSchedule::where('result_id', null)->whereNotNull('interview_at')->distinct('employee_candidate_id')->get();

        return $this->showALl($candidate);
    }

    public function getDetail($id)
    {
        $candidate = Candidate::where('user_id',$id)->firstOrFail();

        $schedules = CandidateInterviewSchedule::where('employee_candidate_id', $candidate->id)->get();

        return $this->showAll($schedules);
    }

    public function indexWithoutInterviewDate(Request $request)
    {
        $user = auth()->user();
        $employee = EmployeeDetail::where('user_id', $user->id_kustomer)->firstOrFail();

        $schedules = CandidateInterviewSchedule::
            // whereBettween('date_time',)
            whereNull('interview_at')
            ->where('interview_by', $employee->id)
            ->whereNull('result_id')
            ->whereNull('rejected_at')
            ->distinct('employee_candidate_id')
            ->get();
        return $this->showAll($schedules);
    }

    public function assessmentInterview(Request $request)
    {
        $results = InterviewResult::all();

        return $this->showAll($results);
    }

    public function indexByDate(Request $request)
    {
        $user = auth()->user();
        $employee = EmployeeDetail::where('user_id', $user->id_kustomer)->firstOrFail();
        // dump($request->input());
        $request->validate([
            'start_at' => 'date|required',
            'until_at' => 'date|nullable',
        ]);

        if (!$request->until_at) {
            $untilAt = $request->start_at . "+1day";
        } else {
            $untilAt = $request->until_at . "+1day";
        }
        $begin = new DateTime(date('Y-m-d H:i:s', strtotime($request->start_at)));
        $until = new DateTime(date('Y-m-d H:i:s', strtotime($untilAt)));
        $interval = DateInterval::createFromDateString('1 day');
        $periods = new DatePeriod($begin, $interval, $until);

        $data = [];
        foreach ($periods as $period) {
            $scheduleArray = [];
            $date = $period->format('Y-m-d H:i:s');
            $schedules = CandidateInterviewSchedule::
                // whereBettween('date_time',)
                whereDate('interview_at', $period->format('Y-m-d'))
                ->where('interview_by', $employee->id)
                ->whereNull('result_id')
                ->distinct('employee_candidate_id')
                ->get();
            foreach ($schedules as $schedule) {
                $scheduleArray[] = [
                    'interview_at' => $schedule->interview_at,
                    'schedule_detail' => $schedule->toArraySchedule(),
                ];
            }
            $data[] = [
                'period' => $period,
                'schedules' => $scheduleArray,
            ];
        }

        return $this->showAll(collect($data));
    }

    public function indexInterviewer()
    {

        $employee = EmployeeDetail::with('roles')->get();

        Collection::macro('interviewer', function () {
            return $this->map(function ($value) {
                // dump($value);
                return [
                    'id' => $value->id,
                    'user_id' => $value->user_id,
                    'first_name' => $value->profileDetail->first_name,
                    'last_name' => $value->profileDetail->last_name
                ];
            });
        });

        $interviewers = $employee->filter(function ($employee, $key) {
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
        $schedule = CandidateInterviewSchedule::where('interview_by', $interviewer)
            ->where('interview_at', date('Y-m-d H:i:s', strtotime($date . ' ' . $time)))->first();
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
        //check role
        $request->validate([
            // 'employee_candidate_id' => 'required|exists:candidate_employee_schedules,employee_candidate_id',
            'interview_at' => 'required|date_format:Y-m-d\TH:i:s.v\Z',
        ]);
        $schedule = CandidateInterviewSchedule::where('id', $id)->firstOrFail();

        // if ($this->isThereAnyOtherSchedule($request->date, $request->time, $schedule->interview_by)) {
        //     return $this->errorResponse('You have another schedule execpt this schedule', 422, 42201);
        // }
        $schedule->interview_at = date('Y-m-d H:i:s', strtotime($request->interview_at));
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
                    'candidate_employee_schedule_id' => $schedule->id,
                    'character_trait_id' => $characterTrait->id,
                ]);
            }
        }
        // dump($schedule);
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
