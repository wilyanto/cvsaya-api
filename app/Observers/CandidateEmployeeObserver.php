<?php

namespace App\Observers;
use App\Models\CandidateEmployee;
use App\Models\CandidateLogEmployee;

class CandidateEmployeeObserver
{
    public function created(CandidateEmployee $candidateEmployees)
    {
        $newlog= new CandidateLogEmployee();
        $newlog->candidate_id = $candidateEmployees->id;
        $newlog->name =$candidateEmployees->name;
        $newlog->phone_number = $candidateEmployees->phone_number;
        $newlog->user_id = $candidateEmployees->user_id;
        $newlog->register_date = $candidateEmployees->register_date;
        $newlog->status = $candidateEmployees->status;
        $newlog->suggest_by = $candidateEmployees->suggest_by;
        $newlog->save();

    }

    public function updated(CandidateEmployee $candidateEmployees)
    {
        $newlog= new CandidateLogEmployee();
        $newlog->candidate_id = $candidateEmployees->id;
        $newlog->name =$candidateEmployees->name;
        $newlog->phone_number = $candidateEmployees->phone_number;
        $newlog->user_id = $candidateEmployees->user_id;
        $newlog->register_date = $candidateEmployees->register_date;
        $newlog->status = $candidateEmployees->status;
        $newlog->suggest_by = $candidateEmployees->suggest_by;
        $newlog->save();

    }
}
