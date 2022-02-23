<?php

namespace App\Observers;

use App\Models\CvExpectedSalaries;
use App\Models\CvLogExpectedSalaries;

class CvExpectedSalariesObserver
{
    public function created(CvExpectedSalaries $cvExpectedSalaries)
    {
        $newLog = new CvLogExpectedSalaries();
        $newLog->expected_salary_id = $cvExpectedSalaries->id;
        $newLog->expected_amount = $cvExpectedSalaries->expected_amount;
        $newLog->expected_position = $cvExpectedSalaries->expected_position;
        $newLog->reason_position = $cvExpectedSalaries->reason_position;
        $newLog->reasons = $cvExpectedSalaries->reasons;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    public function updated(CvExpectedSalaries $cvExpectedSalaries)
    {
        $newLog = new CvLogExpectedSalaries();
        $newLog->expected_salary_id = $cvExpectedSalaries->id;
        $newLog->expected_amount = $cvExpectedSalaries->expected_amount;
        $newLog->expected_position = $cvExpectedSalaries->expected_position;
        $newLog->reason_position = $cvExpectedSalaries->reason_position;
        $newLog->reasons = $cvExpectedSalaries->reasons;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }
}
