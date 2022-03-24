<?php

namespace App\Observers;

use App\Models\CvExpectedJob;
use App\Models\CvLogExpectedJob;

class CvExpectedJobObserver
{
    public function created(CvExpectedJob $cvExpectedSalaries)
    {
        $newLog = new CvLogExpectedJob();
        $newLog->expected_salary_id = $cvExpectedSalaries->id;
        $newLog->expected_amount = $cvExpectedSalaries->expected_amount;
        $newLog->expected_position = $cvExpectedSalaries->expected_position;
        $newLog->reason_position = $cvExpectedSalaries->reason_position;
        $newLog->reason_salary = $cvExpectedSalaries->reason_salary;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    public function updated(CvExpectedJob $cvExpectedSalaries)
    {
        $newLog = new CvLogExpectedJob();
        $newLog->expected_salary_id = $cvExpectedSalaries->id;
        $newLog->expected_amount = $cvExpectedSalaries->expected_amount;
        $newLog->expected_position = $cvExpectedSalaries->expected_position;
        $newLog->reason_position = $cvExpectedSalaries->reason_position;
        $newLog->reason_salary = $cvExpectedSalaries->reason_salary;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }
}
