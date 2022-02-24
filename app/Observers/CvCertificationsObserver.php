<?php

namespace App\Observers;

use App\Models\CvCertifications;
use App\Models\CvLogCertifications;

class CvCertificationsObserver
{
    /**
     * Handle the CvSayaCertifications "created" event.
     *
     * @param  \App\Models\Certifications  $cvSayaCertifications
     * @return void
     */
    public function created(CvCertifications $cvSayaCertifications)
    {
        $newLog = new CvLogCertifications();
        $newLog->certification_id = $cvSayaCertifications->id;
        $newLog->name = $cvSayaCertifications->name;
        $newLog->organization = $cvSayaCertifications->organization;
        $newLog->issued_at = $cvSayaCertifications->issued_at;
        $newLog->expired_at = $cvSayaCertifications->expired_at;
        $newLog->credential_id = $cvSayaCertifications->credential_id;
        $newLog->credential_url = $cvSayaCertifications->credential_url;
        $newLog->created_at = date('Y-m-d H:i:s',time());
        $newLog->save();
    }

    /**
     * Handle the CvSayaCertifications "updated" event.
     *
     * @param  \App\Models\Certifications  $cvSayaCertifications
     * @return void
     */
    public function updated(CvCertifications $cvSayaCertifications)
    {
        $newLog = new CvLogCertifications();
        $newLog->certification_id = $cvSayaCertifications->id;
        $newLog->name = $cvSayaCertifications->name;
        $newLog->organization = $cvSayaCertifications->organization;
        $newLog->issued_at = $cvSayaCertifications->issued_at;
        $newLog->expired_at = $cvSayaCertifications->expired_at;
        $newLog->credential_id = $cvSayaCertifications->credential_id;
        $newLog->credential_url = $cvSayaCertifications->credential_url;
        $newLog->created_at = date('Y-m-d H:i:s',time());
        $newLog->save();
    }

    /**
     * Handle the CvSayaCertifications "deleted" event.
     *
     * @param  \App\Models\CvSayaCertifications  $cvSayaCertifications
     * @return void
     */
    public function deleted(CvCertifications $cvSayaCertifications)
    {
        //
    }

    /**
     * Handle the CvSayaCertifications "restored" event.
     *
     * @param  \App\Models\CvSayaCertifications  $cvSayaCertifications
     * @return void
     */
    public function restored(CvCertifications $cvSayaCertifications)
    {
        //
    }

    /**
     * Handle the CvSayaCertifications "force deleted" event.
     *
     * @param  \App\Models\CvSayaCertifications  $cvSayaCertifications
     * @return void
     */
    public function forceDeleted(CvCertifications $cvSayaCertifications)
    {
        //
    }
}
