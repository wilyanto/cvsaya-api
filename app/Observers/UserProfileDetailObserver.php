<?php

namespace App\Observers;

use App\Models\LogUserProfileDetails;
use  App\Models\UserProfileDetail;
use Carbon\Carbon;


class UserProfileDetailObserver
{
    public function created(UserProfileDetail $profileDetail)
    {
        $newLog = new LogUserProfileDetails;
        $newLog->user_profile_detail_id = $profileDetail->id;
        $newLog->about = $profileDetail->about;
        $newLog->website_url = $profileDetail->website_url;
        $newLog->selfie_picture = $profileDetail->selfie_picture;
        $newLog->religion = $profileDetail->religion;
        $newLog->reference = $profileDetail->reference;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        // $newLog->updated_at = time();
        $newLog->save();

    }

    public function updated(UserProfileDetail $profileDetail)
    {
        $newLog = new LogUserProfileDetails;
        $newLog->user_profile_detail_id = $profileDetail->id;
        $newLog->about = $profileDetail->about;
        $newLog->website_url = $profileDetail->website_url;
        $newLog->selfie_picture = $profileDetail->selfie_picture;
        $newLog->religion = $profileDetail->religion;
        $newLog->reference = $profileDetail->reference;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        // $newLog->updated_at = time();
        $newLog->save();

    }
}
