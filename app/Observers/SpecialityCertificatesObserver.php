<?php

namespace App\Observers;

use App\Models\LogSpecialityCertificates;
use App\Models\SpecialityCertificates;

class SpecialityCertificatesObserver
{
    /**
     * Handle the CvSayaSpecialityCertificates "created" event.
     *
     * @param  \App\Models\CvSayaSpecialityCertificates  $cvSayaSpecialityCertificates
     * @return void
     */
    public function created(SpecialityCertificates $cvSayaSpecialityCertificates)
    {
        $newLog = new LogSpecialityCertificates();
        $newLog->speciality_certifications_id = $cvSayaSpecialityCertificates->id;
        $newLog->certificate_id = $cvSayaSpecialityCertificates->certificate_id;
        $newLog->speciality_id = $cvSayaSpecialityCertificates->speciality_id;
        $newLog->created_at = date('Y-m-d H:i:s',time());
        $newLog->save();
    }

    /**
     * Handle the CvSayaSpecialityCertificates "updated" event.
     *
     * @param  \App\Models\CvSayaSpecialityCertificates  $cvSayaSpecialityCertificates
     * @return void
     */
    public function updated(SpecialityCertificates $cvSayaSpecialityCertificates)
    {
        $newLog = new LogSpecialityCertificates();
        $newLog->speciality_certifications_id = $cvSayaSpecialityCertificates->id;
        $newLog->certificate_id = $cvSayaSpecialityCertificates->certificate_id;
        $newLog->speciality_id = $cvSayaSpecialityCertificates->speciality_id;
        $newLog->created_at = date('Y-m-d H:i:s',time());
        $newLog->save();
    }

    /**
     * Handle the CvSayaSpecialityCertificates "deleted" event.
     *
     * @param  \App\Models\CvSayaSpecialityCertificates  $cvSayaSpecialityCertificates
     * @return void
     */
    public function deleted(SpecialityCertificates $cvSayaSpecialityCertificates)
    {
        //
    }

    /**
     * Handle the CvSayaSpecialityCertificates "restored" event.
     *
     * @param  \App\Models\CvSayaSpecialityCertificates  $cvSayaSpecialityCertificates
     * @return void
     */
    public function restored(SpecialityCertificates $cvSayaSpecialityCertificates)
    {
        //
    }

    /**
     * Handle the CvSayaSpecialityCertificates "force deleted" event.
     *
     * @param  \App\Models\CvSayaSpecialityCertificates  $cvSayaSpecialityCertificates
     * @return void
     */
    public function forceDeleted(SpecialityCertificates $cvSayaSpecialityCertificates)
    {
        //
    }
}
