<?php

namespace App\Observers;

use App\Models\CandidateEmployees;
use App\Models\CvLogProfileDetails;
use  App\Models\CvProfileDetail;
use Carbon\Carbon;


class CvProfileDetailObserver
{
    public function created(CvProfileDetail $profileDetail)
    {
        $newLog = new CvLogProfileDetails;
        $newLog->profile_detail_id = $profileDetail->id;
        $newLog->first_name = $profileDetail->first_name;
        $newLog->last_name= $profileDetail->last_name;
        $newLog->birth_location = $profileDetail->last_name;
        $newLog->birth_date =  date('Y-m-d h:i:s',strtotime($profileDetail->birth_date));
        $newLog->gender = $profileDetail->gender;
        $newLog->identity_number = $profileDetail->gender;
        $newLog->religion	 = $profileDetail->religion;
        $newLog->married = $profileDetail->married;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    public function updated(CvProfileDetail $profileDetail)
    {
        $newLog = new CvLogProfileDetails;
        $newLog->profile_detail_id = $profileDetail->id;
        $newLog->first_name = $profileDetail->first_name;
        $newLog->last_name= $profileDetail->last_name;
        $newLog->birth_location = $profileDetail->last_name;
        $newLog->birth_date =  date('Y-m-d h:i:s',strtotime($profileDetail->birth_date));
        $newLog->gender = $profileDetail->gender;
        $newLog->identity_number = $profileDetail->gender;
        $newLog->religion	 = $profileDetail->religion;
        $newLog->married = $profileDetail->married;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();

    }
}
