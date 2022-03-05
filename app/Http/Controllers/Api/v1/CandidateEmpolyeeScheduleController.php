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
    public function index()
    {
        $user = auth()->user();

        $candidate = CandidateEmployeeSchedule::where('result', null)->orderBy('status')->distinct('employee_candidate_id')->get();

        return $this->showALl($candidate);
    }


    public function indexByDate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'start_at' => 'date|required',
            'until_at' => 'date|nullable',
        ]);

            $begin = new DateTime(date('Y-m-d H:i:s', strtotime($request->start_at)));
            $until = new DateTime(date('Y-m-d H:i:s', strtotime($request->until_at)));
            $interval = DateInterval::createFromDateString('1 day');
            $periods = new DatePeriod($begin, $interval, $until);

        $data = [];
        foreach($periods as $period){
            $scheduleArray = [];
            $schedules = CandidateEmployeeSchedule::whereDate('date_time','==',$period)
            ->where('interview_by',$user->id_kustomer)
            ->whereNull('result')
            ->distinct('employee_candidate_id')
            ->get();

            foreach($schedules as $schedule){
                $scheduleArray[] = [
                    'date_time' => $schedule->date_time,
                    'candidate' => $schedule->candidate,
                ];
            }
            $data[] = [
                'date' => $period,
                'schedulues' => $scheduleArray,
            ];
        }

        return $this->showAll(collect($data));


        // $candidate = CandidateEmployeeSchedule::where('interview_by',$user->id_kustomer)->whereDate('date_time','>='.$request->start_at)->where(function($qurey) use ($untilAt){
        //     if($untilAt != null){
        //         $qurey->whereDate('date_time','<=',$untilAt);
        //     }
        // })->distinct('employee_candidate_id')->get();
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
    public function store(Request $request)
    {
        $user = auth()->user();
        // $posistion = EmployeeDetails::where('user_id',$user->id_kustomer)->first();
        // if(!$posistion){
        //     return $this->errorResponse('user tidak di temukan',404,40401);
        // }
        $request->validate([
            'employee_candidate_id' => 'integer|required',
            'date_time' => 'date|nullable',
            'interview_by' => 'integer|required',
            'note' => 'longtext|nullable',
        ]);
        $candidate = CandidateEmployee::where('id', $request->employee_candidate_id)->first();
        if (!$candidate) {
            return $this->errorResponse('Candidate not found', 404, 40401);
        }

        $candidateController = new CvProfileDetailController;

        $status = $candidateController->getStatus($candidate->user_id);
        $status = $status->original;
        $status = $status['data']['is_all_form_filled'];
        if (
            $candidate->status != CandidateEmployee::INTERVIEW &&
            $candidate->status != CandidateEmployee::PASS &&
            $status == false
        ) {
            return $this->errorResponse('this Candidate cannot going interview', 401, 40101);
        }

        if ($request->status < CandidateEmployee::ACCEPTED) {
            $candidate->status = CandidateEmployee::INTERVIEW;
            $candidate->save();
        }
        dump($request->all());
        $candidateEmpolyeeSchedule = CandidateEmployeeSchedule::create($request->all());

        return $this->showOne($candidateEmpolyeeSchedule);
    }

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
    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'employee_candidate_id' => 'integer|required',
            'schedule_id' => 'integer|required',
        ]);

        // dd($request->input());

        $candidate = CandidateEmployee::where('id', $request->employee_candidate_id)->first();
        if (!$candidate) {
            return $this->errorResponse('Candidate Not Found', 404, 40401);
        }

        $schedule = CandidateEmployeeSchedule::where('id', $request->schedule_id)->first();
        if (!$schedule) {
            return $this->errorResponse('Schedule not found', 404, 40402);
        }


        if ($request->result_interview == null) {
            $request->validate([
                'date' => 'date|required',
                'time' => 'date_format:H:i:s|required',
            ]);

            $schedule->date_time = date('Y-m-d H:i:s', strtotime($request->date . ' ' . $request->time));
            $schedule->save();
            // dump($schedule);
        } elseif ($request->result_interview) {
            $request->validate([
                'result_interview' => 'integer|required',
                'next_interviewer' => 'integer|required',
                'note_interview' => 'longText|nullable',
                'date' => 'date|nullable',
                'time' => 'date_format:H:i:s|nullable',
            ]);
            $schedule->result = $request->result_interview;
            $schedule->note = $request->note_interview;
            $candidate->result = $request->result_interview;
            $schedule->save();
            $candidate->save();
            if ($request->result_interview == CandidateEmployee::PASS) {
                $newSchedule = new CandidateEmployeeSchedule();
                $newSchedule->employee_candidate_id = $request->employee_candidate_id;
                $newSchedule->interview_by = $request->next_interviewer;
                $newSchedule->date_time =
                    $request->date == null || $request->time == null
                    ? null :
                    date('Y-m-d H:i:s', strtotime($request->date . ' ' . $request->time));
                $newSchedule->save();
            } elseif ($request->result_interview == CandidateEmployee::ACCEPTED) {

                $request->validate([
                    'position_id' => 'integer|required',
                    'salary_value' => 'integer|required',
                ]);

                $position = Position::where('id', $request->user_id)->first();
                if (!$position) {

                    return $this->errorResponse('Schedule not found', 404, 40403);
                }

                $newEmpolyee = new EmployeeDetail();
                $newEmpolyee->user_id = $candidate->user_id;
                $newEmpolyee->position_id = $position->id;
                $newEmpolyee->salary = $request->salary_value;
                $newEmpolyee->save();
            }
        }

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
