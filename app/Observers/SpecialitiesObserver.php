<?php

namespace App\Observers;

use App\Models\Specialities;
use App\Models\LogSpecialities;

class SpecialitiesObserver
{
    /**
     * Handle the CvSayaSepecialities "created" event.
     *
     * @param  \App\Models\CvSayaSepecialities  $cvSayaSepecialities
     * @return void
     */
    public function created(Specialities $cvSayaSepecialities)
    {
        $newLog = new LogSpecialities;
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
    public function updated(Specialities $cvSayaSepecialities)
    {
        $newLog = new LogSpecialities;
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
    public function deleted(Specialities $cvSayaSepecialities)
    {
        //
    }

    /**
     * Handle the CvSayaSepecialities "restored" event.
     *
     * @param  \App\Models\CvSayaSepecialities  $cvSayaSepecialities
     * @return void
     */
    public function restored(Specialities $cvSayaSepecialities)
    {
        //
    }

    /**
     * Handle the CvSayaSepecialities "force deleted" event.
     *
     * @param  \App\Models\CvSayaSepecialities  $cvSayaSepecialities
     * @return void
     */
    public function forceDeleted(Specialities $cvSayaSepecialities)
    {
        //
    }
}
