<?php

namespace App\Observers;
use App\Models\CandidateEmployees;
use App\Models\CandidateLogEmpolyees;

class CandidateEmpolyessObserver
{
    public function created(CandidateEmployees $candidateEmployees)
    {
        $newlog= new CandidateLogEmpolyees();
        $newlog->candidate_id = $candidateEmployees->id;
        $newlog->name =$candidateEmployees->name;
        $newlog->country_code = $candidateEmployees->country_code;
        $newlog->phone_number = $candidateEmployees->phone_number;
        $newlog->user_id = $candidateEmployees->user_id;
        $newlog->register_date = $candidateEmployees->register_date;
        $newlog->status = $candidateEmployees->status;
        $newlog->suggest_by = $candidateEmployees->suggest_by;
        $newlog->save();

    }

    public function updated(CandidateEmployees $candidateEmployees)
    {
        $newlog= new CandidateLogEmpolyees();
        $newlog->candidate_id = $candidateEmployees->id;
        $newlog->name =$candidateEmployees->name;
        $newlog->country_code = $candidateEmployees->country_code;
        $newlog->phone_number = $candidateEmployees->phone_number;
        $newlog->user_id = $candidateEmployees->user_id;
        $newlog->register_date = $candidateEmployees->register_date;
        $newlog->status = $candidateEmployees->status;
        $newlog->suggest_by = $candidateEmployees->suggest_by;
        $newlog->save();

    }
}
