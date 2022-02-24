<?php

namespace App\Observers;

use App\Models\CvSpecialities;
use App\Models\CvLogSpecialities;

class CvSpecialitiesObserver
{
    /**
     * Handle the CvSayaSepecialities "created" event.
     *
     * @param  \App\Models\CvSayaSepecialities  $cvSayaSepecialities
     * @return void
     */
    public function created(CvSpecialities $cvSayaSepecialities)
    {
        $newLog = new CvLogSpecialities;
        $newLog->name = $cvSayaSepecialities->name;
        $newLog->specialities_id = $cvSayaSepecialities->id;
        $newLog->speciality_certificate_id = $cvSayaSepecialities->speciality_certificate_id;
        $newLog->save();
    }

    /**
     * Handle the CvSayaSepecialities "updated" event.
     *
     * @param  \App\Models\CvSayaSepecialities  $cvSayaSepecialities
     * @return void
     */
    public function updated(CvSpecialities $cvSayaSepecialities)
    {
        $newLog = new CvLogSpecialities;
        $newLog->name = $cvSayaSepecialities->name;
        $newLog->specialities_id = $cvSayaSepecialities->id;
        $newLog->speciality_certificate_id = $cvSayaSepecialities->speciality_certificate_id;
        $newLog->save();
    }

    /**
     * Handle the CvSayaSepecialities "deleted" event.
     *
     * @param  \App\Models\CvSayaSepecialities  $cvSayaSepecialities
     * @return void
     */
    public function deleted(CvSpecialities $cvSayaSepecialities)
    {
        //
    }

    /**
     * Handle the CvSayaSepecialities "restored" event.
     *
     * @param  \App\Models\CvSayaSepecialities  $cvSayaSepecialities
     * @return void
     */
    public function restored(CvSpecialities $cvSayaSepecialities)
    {
        //
    }

    /**
     * Handle the CvSayaSepecialities "force deleted" event.
     *
     * @param  \App\Models\CvSayaSepecialities  $cvSayaSepecialities
     * @return void
     */
    public function forceDeleted(CvSpecialities $cvSayaSepecialities)
    {
        //
    }
}
