<?php

namespace App\Observers;

use App\Models\CandidateInterviewSchedule;
use App\Models\CandidateLogEmployeeSchedule;

class CandidateInterviewScheduleObserver
{
    public function created(CandidateInterviewSchedule $interviewSchedule)
    {
        $newlog = new CandidateLogEmployeeSchedule();
        $newlog->candidate_interview_schedules_id = $interviewSchedule->id;
        $newlog->interview_at = $interviewSchedule->interview_at == null ? null : $interviewSchedule->interview_at;
        $newlog->interview_by = $interviewSchedule->interview_by == null ? null : $interviewSchedule->interview_by;
        $newlog->result_id = $interviewSchedule->result_id == null ? null : $interviewSchedule->result_id;
        $newlog->note = $interviewSchedule->note == null ? null : $interviewSchedule->note;
        $newlog->rejected_at = $interviewSchedule->rejected_at == null ? null : $interviewSchedule->rejected_at;
        $newlog->save();
    }

    public function updated(CandidateInterviewSchedule $interviewSchedule)
    {
        $newlog = new CandidateLogEmployeeSchedule();
        $newlog->candidate_interview_schedules_id = $interviewSchedule->id;
        $newlog->interview_at = $interviewSchedule->interview_at == null ? null : $interviewSchedule->interview_at;
        $newlog->interview_by = $interviewSchedule->interview_by == null ? null : $interviewSchedule->interview_by;
        $newlog->result_id = $interviewSchedule->result_id == null ? null : $interviewSchedule->result_id;
        $newlog->note = $interviewSchedule->note == null ? null : $interviewSchedule->note;
        $newlog->rejected_at = $interviewSchedule->rejected_at == null ? null : $interviewSchedule->rejected_at;
        $newlog->save();
    }
}
