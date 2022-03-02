<?php

namespace App\Observers;

use App\Models\CandidateEmpolyeeSchedule;
use App\Models\CandidateLogEmpolyeeSchedule;

class CandidateEmpolyeeScheduleObserver
{
    public function created(CandidateEmpolyeeSchedule $candidateEmployees)
    {
        $newlog= new CandidateLogEmpolyeeSchedule();
        $newlog->employee_candidate_id = $candidateEmployees->id;
        $newlog->date_time = $candidateEmployees->date_time;
        $newlog->interview_by = $candidateEmployees->interview_by;
        $newlog->result = $candidateEmployees->result;
        $newlog->note = $candidateEmployees->note;
        $newlog->save();
    }

    public function updated(CandidateEmpolyeeSchedule $candidateEmployees)
    {
        $newlog= new CandidateLogEmpolyeeSchedule();
        $newlog->employee_candidate_id = $candidateEmployees->id;
        $newlog->date_time = $candidateEmployees->date_time;
        $newlog->interview_by = $candidateEmployees->interview_by;
        $newlog->result = $candidateEmployees->result;
        $newlog->note = $candidateEmployees->note;
        $newlog->save();
    }

}
