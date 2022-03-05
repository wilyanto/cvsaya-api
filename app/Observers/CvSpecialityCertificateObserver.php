<?php

namespace App\Observers;

use App\Models\CvLogSpecialityCertificate;
use App\Models\CvSpecialityCertificate;

class CvSpecialityCertificateObserver
{
    /**
     * Handle the CvSayaSpecialityCertificates "created" event.
     *
     * @param  \App\Models\CvSayaSpecialityCertificates  $cvSayaSpecialityCertificates
     * @return void
     */
    public function created(CvSpecialityCertificate $cvSayaSpecialityCertificates)
    {
        $newLog = new CvLogSpecialityCertificate();
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
    public function updated(CvSpecialityCertificate $cvSayaSpecialityCertificates)
    {
        $newLog = new CvLogSpecialityCertificate();
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
    public function deleted(CvSpecialityCertificate $cvSayaSpecialityCertificates)
    {
        //
    }

    /**
     * Handle the CvSayaSpecialityCertificates "restored" event.
     *
     * @param  \App\Models\CvSayaSpecialityCertificates  $cvSayaSpecialityCertificates
     * @return void
     */
    public function restored(CvSpecialityCertificate $cvSayaSpecialityCertificates)
    {
        //
    }

    /**
     * Handle the CvSayaSpecialityCertificates "force deleted" event.
     *
     * @param  \App\Models\CvSayaSpecialityCertificates  $cvSayaSpecialityCertificates
     * @return void
     */
    public function forceDeleted(CvSpecialityCertificate $cvSayaSpecialityCertificates)
    {
        //
    }
}
