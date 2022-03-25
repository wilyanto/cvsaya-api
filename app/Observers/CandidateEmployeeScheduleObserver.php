<?php

namespace App\Observers;

use App\Models\CandidateEmployeeSchedule;
use App\Models\CandidateLogEmployeeSchedule;

class CandidateEmployeeScheduleObserver
{
    public function created(CandidateEmployeeSchedule $candidateEmployees)
    {
        $newlog= new CandidateLogEmployeeSchedule();
        $newlog->employee_candidate_id = $candidateEmployees->id;
        $newlog->interview_at = $candidateEmployees->interview_at;
        $newlog->interview_by = $candidateEmployees->interview_by;
        $newlog->result_id = $candidateEmployees->result_id;
        $newlog->note = $candidateEmployees->note;
        $newlog->save();
    }

    public function updated(CandidateEmployeeSchedule $candidateEmployees)
    {
        $newlog= new CandidateLogEmployeeSchedule();
        $newlog->employee_candidate_id = $candidateEmployees->id;
        $newlog->interview_at = $candidateEmployees->interview_at;
        $newlog->interview_by = $candidateEmployees->interview_by;
        $newlog->result_id = $candidateEmployees->result_id;
        $newlog->note = $candidateEmployees->note;
        $newlog->save();
    }

}
