<?php

namespace App\Observers;

use App\Models\CvAddress;
use App\Models\CvLogAddress;

class CvAddressObserver
{
    public function created(CvAddress $cvAddress)
    {
        $newLog = new CvLogAddress();
        $newLog->cv_address_id = $cvAddress->id;
        $newLog->country_id = $cvAddress->country_id;
        $newLog->province_id = $cvAddress->province_id;
        $newLog->city_id = $cvAddress->city_id;
        $newLog->district_id = $cvAddress->district_id;
        $newLog->village_id = $cvAddress->village_id;
        $newLog->address = $cvAddress->address;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    public function updated(CvAddress $cvAddress)
    {
        $newLog = new CvLogAddress();
        $newLog->cv_address_id = $cvAddress->id;
        $newLog->country_id = $cvAddress->country_id;
        $newLog->province_id = $cvAddress->province_id;
        $newLog->city_id = $cvAddress->city_id;
        $newLog->district_id = $cvAddress->district_id;
        $newLog->village_id = $cvAddress->village_id;
        $newLog->address = $cvAddress->address;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }
}
