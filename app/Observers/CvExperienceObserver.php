<?php

namespace App\Observers;

use App\Models\CvExperience;
use App\Models\CvLogExperience;

class CvExperienceObserver
{
    /**
     * Handle the CvSayaExperiences "created" event.
     *
     * @param  \App\Models\Experiences  $cvSayaExperiences
     * @return void
     */
    public function created(CvExperience $cvSayaExperiences)
    {
        $newLog = new CvLogExperience();
        $newLog->experience_id = $cvSayaExperiences->id;
        $newLog->position_id = $cvSayaExperiences->position_id;
        $newLog->employment_type_id = $cvSayaExperiences->employment_type_id;
        $newLog->location = $cvSayaExperiences->location;
        $newLog->start_at = $cvSayaExperiences->start_at;
        $newLog->until_at = $cvSayaExperiences->until_at;
        $newLog->jobdesc = $cvSayaExperiences->jobdesc;
        $newLog->reference = $cvSayaExperiences->reference;
        $newLog->previous_salary = $cvSayaExperiences->previous_salary;
        $newLog->reason_resign = $cvSayaExperiences->reason_resign;
        $newLog->slip_salary_img = $cvSayaExperiences->slip_salary_img;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();

    }

    /**
     * Handle the CvSayaExperiences "updated" event.
     *
     * @param  \App\Models\Experiences  $cvSayaExperiences
     * @return void
     */
    public function updated(CvExperience $cvSayaExperiences)
    {

        $newLog = new CvLogExperience();
        $newLog->experience_id = $cvSayaExperiences->id;
        $newLog->position_id = $cvSayaExperiences->position_id;
        $newLog->employment_type_id = $cvSayaExperiences->employment_type_id;
        $newLog->location = $cvSayaExperiences->location;
        $newLog->start_at = $cvSayaExperiences->start_at;
        $newLog->until_at = $cvSayaExperiences->until_at;
        $newLog->jobdesc = $cvSayaExperiences->jobdesc;
        $newLog->reference = $cvSayaExperiences->reference;
        $newLog->previous_salary = $cvSayaExperiences->previous_salary;
        $newLog->reason_resign = $cvSayaExperiences->reason_resign;
        $newLog->slip_salary_img = $cvSayaExperiences->slip_salary_img;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    /**
     * Handle the CvSayaExperiences "deleted" event.
     *
     * @param  \App\Models\Experiences  $cvSayaExperiences
     * @return void
     */
    public function deleted(CvExperience $cvSayaExperiences)
    {
        //
    }

    /**
     * Handle the CvSayaExperiences "restored" event.
     *
     * @param  \App\Models\Experiences  $cvSayaExperiences
     * @return void
     */
    public function restored(CvExperience $cvSayaExperiences)
    {
        //
    }

    /**
     * Handle the CvSayaExperiences "force deleted" event.
     *
     * @param  \App\Models\Experiences  $cvSayaExperiences
     * @return void
     */
    public function forceDeleted(CvExperience $cvSayaExperiences)
    {
        //
    }
}
