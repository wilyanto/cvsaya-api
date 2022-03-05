<?php

namespace App\Observers;

use App\Models\CvSpeciality;
use App\Models\CvLogSpeciality;

class CvSpecialityObserver
{
    /**
     * Handle the CvSayaSepecialities "created" event.
     *
     * @param  \App\Models\CvSayaSepecialities  $cvSayaSepecialities
     * @return void
     */
    public function created(CvSpeciality $cvSayaSepecialities)
    {
        $newLog = new CvLogSpeciality;
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
    public function updated(CvSpeciality $cvSayaSepecialities)
    {
        $newLog = new CvLogSpeciality;
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
    public function deleted(CvSpeciality $cvSayaSepecialities)
    {
        //
    }

    /**
     * Handle the CvSayaSepecialities "restored" event.
     *
     * @param  \App\Models\CvSayaSepecialities  $cvSayaSepecialities
     * @return void
     */
    public function restored(CvSpeciality $cvSayaSepecialities)
    {
        //
    }

    /**
     * Handle the CvSayaSepecialities "force deleted" event.
     *
     * @param  \App\Models\CvSayaSepecialities  $cvSayaSepecialities
     * @return void
     */
    public function forceDeleted(CvSpeciality $cvSayaSepecialities)
    {
        //
    }
}
