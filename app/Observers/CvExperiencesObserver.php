<?php

namespace App\Observers;

use App\Models\CvExperiences;
use App\Models\CvLogExperiences;

class CvExperiencesObserver
{
    /**
     * Handle the CvSayaExperiences "created" event.
     *
     * @param  \App\Models\Experiences  $cvSayaExperiences
     * @return void
     */
    public function created(CvExperiences $cvSayaExperiences)
    {
        $newLog = new CvLogExperiences();
        $newLog->experience_id = $cvSayaExperiences->id;
        $newLog->position = $cvSayaExperiences->position;
        $newLog->employment_type = $cvSayaExperiences->employment_type;
        $newLog->location = $cvSayaExperiences->location;
        $newLog->start_at = $cvSayaExperiences->start_at;
        $newLog->until_at = $cvSayaExperiences->until_at;
        $newLog->description = $cvSayaExperiences->description;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();

    }

    /**
     * Handle the CvSayaExperiences "updated" event.
     *
     * @param  \App\Models\Experiences  $cvSayaExperiences
     * @return void
     */
    public function updated(CvExperiences $cvSayaExperiences)
    {

        $newLog = new CvLogExperiences();
        $newLog->experience_id = $cvSayaExperiences->id;
        $newLog->position = $cvSayaExperiences->position;
        $newLog->employment_type = $cvSayaExperiences->employment_type;
        $newLog->location = $cvSayaExperiences->location;
        $newLog->start_at = $cvSayaExperiences->start_at;
        $newLog->until_at = $cvSayaExperiences->until_at;
        $newLog->description = $cvSayaExperiences->description;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    /**
     * Handle the CvSayaExperiences "deleted" event.
     *
     * @param  \App\Models\Experiences  $cvSayaExperiences
     * @return void
     */
    public function deleted(CvExperiences $cvSayaExperiences)
    {
        //
    }

    /**
     * Handle the CvSayaExperiences "restored" event.
     *
     * @param  \App\Models\Experiences  $cvSayaExperiences
     * @return void
     */
    public function restored(CvExperiences $cvSayaExperiences)
    {
        //
    }

    /**
     * Handle the CvSayaExperiences "force deleted" event.
     *
     * @param  \App\Models\Experiences  $cvSayaExperiences
     * @return void
     */
    public function forceDeleted(CvExperiences $cvSayaExperiences)
    {
        //
    }
}
