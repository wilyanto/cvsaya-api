<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\api\v1\CandidateEmployeeController;
use App\Models\CandidateEmployee;
use App\Models\CandidateEmployeeSchedule;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\CvProfileDetailController;
use App\Models\CvExpectedSalary;
use App\Models\EmployeeDetail;
use App\Models\CandidateEmployeeScheduleCharacterTrait;
use App\Models\CharacterTrait;
use App\Models\Position;
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

        if($request->start_at){
            return $this->indexByDate($request);
        }
        $candidate = CandidateEmployeeSchedule::where('result_id', null)->distinct('employee_candidate_id')->get();

        return $this->showALl($candidate);
    }

    public function getDetail($id)
    {
        $schedules = CandidateEmployeeSchedule::where('employee_candidate_id', $id)->get();

        return $this->showAll($schedules);
    }

    public function indexWithoutInterviewDate(Request $request){
            $user = auth()->user();
            $employee = EmployeeDetail::where('user_id', $user->id_kustomer)->firstOrFail();

            $schedules = CandidateEmployeeSchedule::
                    // whereBettween('date_time',)
                    whereNull('interview_at')
                    ->where('interview_by', $employee->id)
                    ->whereNull('result_id')
                    ->distinct('employee_candidate_id')
                    ->get();
            return $this->showAll($schedules);
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
            $untilAt = $request->until_at;
        }
        $begin = new DateTime(date('Y-m-d H:i:s', strtotime($request->start_at)));
        $until = new DateTime(date('Y-m-d H:i:s', strtotime($untilAt)));
        $interval = DateInterval::createFromDateString('1 day');
        $periods = new DatePeriod($begin, $interval, $until);

        $data = [];
        foreach ($periods as $period) {
            $scheduleArray = [];
            $date = $period->format('Y-m-d H:i:s');
            $schedules = CandidateEmployeeSchedule::
                // whereBettween('date_time',)
                whereDate('interview_at', $period->format('Y-m-d'))
                ->where('interview_by', $employee->id)
                ->whereNull('result_id')
                ->distinct('employee_candidate_id')
                ->get();
            foreach ($schedules as $schedule) {
                $scheduleArray[] = [
                    'interview_at' => $schedule->interview_at,
                    'candidate' => $schedule->toArrayCandidate(),
                ];
            }
            $data[] = [
                'date' => $period,
                'schedules' => $scheduleArray,
            ];
        }

        return $this->showAll(collect($data));
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
    public function show(CandidateEmployeeSchedule $candidateEmpolyeeSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CandidateEmpolyeeSchedule  $candidateEmpolyeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(CandidateEmployeeSchedule $candidateEmpolyeeSchedule)
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
        $schedule = CandidateEmployeeSchedule::where('interview_by', $interviewer)
            ->where('interview_at', date('Y-m-d H:i:s', strtotime($date . ' ' . $time)))->first();
        if ($schedule) {
            return true;
        }
        return false;
    }

    public function updateSchedule(Request $request, $id)
    {
        //check role

        $request->validate([
            // 'employee_candidate_id' => 'required|exists:candidate_employee_schedules,employee_candidate_id',
            'date' => 'date|required',
            'time' => 'date_format:H:i:s|required',
        ]);
        $schedule = CandidateEmployeeSchedule::where('id', $id)->firstOrFail();

        // if ($this->isThereAnyOtherSchedule($request->date, $request->time, $schedule->interview_by)) {
        //     return $this->errorResponse('You have another schedule execpt this schedule', 422, 42201);
        // }
        $schedule->interview_at = date('Y-m-d H:i:s', strtotime($request->date . ' ' . $request->time));
        $schedule->save();
        return $this->showOne($schedule);
    }

    public function giveResult(Request $request, $id)
    {
        $request->validate([
            'employee_candidate_id' => 'required|exists:candidate_employee_schedules,employee_candidate_id',
            'note' => 'longtext|nullable',
            'result_id' => 'exists:interview_results,id|required',
            'character_traits' => 'array|nullable'
        ]);

        $schedule = CandidateEmployeeSchedule::where('id', $id)->firstOrFail();
        if ($schedule->candidate->status < CandidateEmployee::INTERVIEW) {
            return $this->errorResponse('candidate cannot change to new result', 422, 42201);
        }
        $schedule->result_id = $request->result_id;
        if ($request->note) {
            $schedule->note = $request->note;
        }
        if(count($request->character_traits)){
            foreach($request->character_traits as $characterTrait){
                $characterTrait = CharacterTrait::where('id',$characterTrait)->firstOrFail();
                CandidateEmployeeScheduleCharacterTrait::create([
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
    public function destroy(CandidateEmployeeSchedule $candidateEmpolyeeSchedule)
    {
        //
    }
}
