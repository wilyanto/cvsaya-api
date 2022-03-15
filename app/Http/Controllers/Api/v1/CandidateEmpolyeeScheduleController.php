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

        $candidate = CandidateEmployeeSchedule::where('result_id', null)->orderBy('status')->distinct('employee_candidate_id')->get();

        return $this->showALl($candidate);
    }

    public function getDetail($id){
        $schedules = CandidateEmployeeSchedule::where('id',$id)->get();

        return $this->showAll($schedules);
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
        foreach ($periods as $period) {
            $scheduleArray = [];
            $schedules = CandidateEmployeeSchedule::whereDate('date_time', '==', $period)
                ->where('interview_by', $user->id_kustomer)
                ->whereNull('result_id')
                ->distinct('employee_candidate_id')
                ->get();

            foreach ($schedules as $schedule) {
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

    public function setInterview(Request $request,$id)
    {
        // $posistion = EmployeeDetails::where('user_id',$user->id_kustomer)->first();
        // if(!$posistion){
        //     return $this->errorResponse('user tidak di temukan',404,40401);
        // }
        $request->validate([
            'date_time' => 'date|nullable',
            'interview_by' => 'integer|required',
            'note' => 'longtext|nullable',
        ]);
        $candidate = CandidateEmployee::where('id', $id)->first();
        if (!$candidate) {
            return $this->errorResponse('Candidate not found', 404, 40401);
        }

        $candidateController = new CvProfileDetailController;

        $status = $candidateController->getStatus($candidate->user_id);
        $status = $status->original;
        $status = $status['data']['is_all_form_filled'];
        if (
            $candidate->status != CandidateEmployee::INTERVIEW &&
            $status == false
        ) {
            return $this->errorResponse('this Candidate cannot going interview', 401, 40101);
        }
        $candidate->status = CandidateEmployee::INTERVIEW;
        $candidate->save();
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
    public function updateSchedulue(Request $request,$id)
    {
        //check role

        $request->validate([
            'employee_candidate_id' => 'required|exists:candidate_employee_schedules,employee_candidate_id',
            'date' => 'date|required',
            'time' => 'date_format:H:i:s|required',
        ]);

        $schedule = CandidateEmployeeSchedule::where('id', $id)->firstOrFail();
        $schedule->date_time = date('Y-m-d H:i:s', strtotime($request->date . ' ' . $request->time));
        $schedule->save();
        return $this->showOne($schedule);
    }

    public function giveResult(Request $request,$id)
    {
        $request->validate([
            'employee_candidate_id' => 'required|exists:candidate_employee_schedules,employee_candidate_id',
            'note' => 'longtext|nullable',
            'result' => 'exists:result_interviews,id|required',
        ]);

        $schedule = CandidateEmployeeSchedule::where('id', $id)->firstOrFail();
        if ($request->reuslt < CandidateEmployee::INTERVIEW) {
            return $this->errorResponse('candidate cannot change to new result', 422, 42201);
        }
        $schedule->result = $request->result;
        if ($request->note) {
            $schedule->note = $request->note;
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
    public function destroy(CandidateEmployeeSchedule $candidateEmpolyeeSchedule)
    {
        //
    }
}
