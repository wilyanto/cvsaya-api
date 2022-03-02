<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\api\v1\CandidateEmployeesController;
use App\Models\CandidateEmployees;
use App\Models\CandidateEmpolyeeSchedule;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\CvProfileDetailController;
use App\Models\CvExpectedSalaries;
use App\Models\EmployeeDetails;
use App\Models\Positions;

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

        $candidate = CandidateEmpolyeeSchedule::where('result', null)->get();

        return $this->showALl($candidate);
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
        $candidate = CandidateEmployees::where('id', $request->employee_candidate_id)->first();
        if (!$candidate) {
            return $this->errorResponse('Candidate not found', 404, 40401);
        }

        $candidateController = new CvProfileDetailController;

        $status = $candidateController->getStatus($candidate->user_id);
        $status = $status->original;
        $status = $status['data']['is_all_form_filled'];
        if (
            $candidate->status != CandidateEmployees::INTERVIEW &&
            $candidate->status != CandidateEmployees::PASS &&
            $status == false
        ) {
            return $this->errorResponse('this Candidate cannot going interview', 401, 40101);
        }

        if ($request->status < CandidateEmployees::ACCEPTED) {
            $candidate->status = CandidateEmployees::INTERVIEW;
            $candidate->save();
        }
        dump($request->all());
        $candidateEmpolyeeSchedule = CandidateEmpolyeeSchedule::create($request->all());

        return $this->showOne($candidateEmpolyeeSchedule);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CandidateEmpolyeeSchedule  $candidateEmpolyeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(CandidateEmpolyeeSchedule $candidateEmpolyeeSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CandidateEmpolyeeSchedule  $candidateEmpolyeeSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(CandidateEmpolyeeSchedule $candidateEmpolyeeSchedule)
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

        $candidate = CandidateEmployees::where('id', $request->employee_candidate_id)->first();
        if (!$candidate) {
            return $this->errorResponse('Candidate Not Found', 404, 40401);
        }

        $schedule = CandidateEmpolyeeSchedule::where('id', $request->schedule_id)->first();
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
            if ($request->result_interview == CandidateEmployees::PASS) {
                $newSchedule = new CandidateEmpolyeeSchedule();
                $newSchedule->employee_candidate_id = $request->employee_candidate_id;
                $newSchedule->interview_by = $request->next_interviewer;
                $newSchedule->date_time =
                    $request->date == null || $request->time == null
                    ? null :
                    date('Y-m-d H:i:s', strtotime($request->date . ' ' . $request->time));
                $newSchedule->save();
            } elseif ($request->result_interview == CandidateEmployees::ACCEPTED) {

                $request->validate([
                    'position_id' => 'integer|required',
                    'salary_value' => 'integer|required',
                ]);

                $position = Positions::where('id', $request->user_id)->first();
                if (!$position) {

                    return $this->errorResponse('Schedule not found', 404, 40403);
                }

                $newEmpolyee = new EmployeeDetails();
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
    public function destroy(CandidateEmpolyeeSchedule $candidateEmpolyeeSchedule)
    {
        //
    }
}
