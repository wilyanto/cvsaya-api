<?php

namespace App\Observers;

use App\Models\CandidateInterviewSchedule;
use App\Models\CandidateLogEmployeeSchedule;

class CandidateInterviewScheduleObserver
{
    public function created(CandidateInterviewSchedule $interviewSchedule)
    {
        $newlog= new CandidateLogEmployeeSchedule();
        $newlog->candidate_id = $interviewSchedule->id;
        $newlog->interview_at = $interviewSchedule->interview_at;
        $newlog->interview_by = $interviewSchedule->interview_by;
        $newlog->result_id = $interviewSchedule->result_id;
        $newlog->note = $interviewSchedule->note;
        $newlog->save();
    }

    public function updated(CandidateInterviewSchedule $interviewSchedule)
    {
        $newlog= new CandidateLogEmployeeSchedule();
        $newlog->candidate_id = $interviewSchedule->id;
        $newlog->interview_at = $interviewSchedule->interview_at;
        $newlog->interview_by = $interviewSchedule->interview_by;
        $newlog->result_id = $interviewSchedule->result_id;
        $newlog->note = $interviewSchedule->note;
        $newlog->save();
    }

}
