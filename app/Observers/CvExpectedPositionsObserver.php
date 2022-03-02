<?php

namespace App\Observers;

use App\Models\CvExpectedPositions;
use App\Models\CvLogExpectedSalaries;

class CvExpectedPositionsObserver
{
    public function created(CvExpectedPositions $cvExpectedSalaries)
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

    public function updated(CvExpectedPositions $cvExpectedSalaries)
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
