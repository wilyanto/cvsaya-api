<?php

namespace App\Observers;
use App\Models\Candidate;
use App\Models\CandidateLogEmployee;

class CandidateObserver
{
    public function created(Candidate $candidate)
    {
        $newlog= new CandidateLogEmployee();
        $newlog->candidate_id = $candidate->id;
        $newlog->name =$candidate->name;
        $newlog->phone_number = $candidate->phone_number;
        $newlog->user_id = $candidate->user_id;
        $newlog->register_date = $candidate->register_date;
        $newlog->status = $candidate->status;
        $newlog->suggest_by = $candidate->suggest_by;
        $newlog->save();

    }

    public function updated(Candidate $candidate)
    {
        $newlog= new CandidateLogEmployee();
        $newlog->candidate_id = $candidate->id;
        $newlog->name =$candidate->name;
        $newlog->phone_number = $candidate->phone_number;
        $newlog->user_id = $candidate->user_id;
        $newlog->register_date = $candidate->register_date;
        $newlog->status = $candidate->status;
        $newlog->suggest_by = $candidate->suggest_by;
        $newlog->save();

    }
}
