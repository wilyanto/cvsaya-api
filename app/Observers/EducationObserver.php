<?php

namespace App\Observers;

use App\Models\CvEducations;
use App\Models\CvLogEducations;

class EducationObserver
{
    /**
     * Handle the CvSayaEducations "created" event.
     *
     * @param  \App\Models\CvSayaEducations  $cvSayaEducations
     * @return void
     */
    public function created(CvEducations $cvSayaEducations)
    {
        $educations = new CvLogEducations();
        $educations->education_id = $cvSayaEducations->id;
        $educations->school = $cvSayaEducations->school;
        $educations->degree = $cvSayaEducations->degree;
        $educations->field_of_study = $cvSayaEducations->field_of_study;
        $educations->grade = $cvSayaEducations->grade;
        $educations->start_at = date('Y-m-d',strtotime($cvSayaEducations->start_at));
        $educations->until_at = date('Y-m-d',strtotime($cvSayaEducations->until_at));
        $educations->activity = $cvSayaEducations->activity;
        $educations->description = $cvSayaEducations->description;
        $educations->save();
    }

    /**
     * Handle the CvSayaEducations "updated" event.
     *
     * @param  \App\Models\CvSayaEducations  $cvSayaEducations
     * @return void
     */
    public function updated(CvEducations $cvSayaEducations)
    {
        $educations = new CvLogEducations();
        $educations->education_id = $cvSayaEducations->id;
        $educations->school = $cvSayaEducations->school;
        $educations->degree = $cvSayaEducations->degree;
        $educations->field_of_study = $cvSayaEducations->field_of_study;
        $educations->grade = $cvSayaEducations->grade;
        $educations->start_at = date('Y-m-d',strtotime($cvSayaEducations->start_at));
        $educations->until_at = date('Y-m-d',strtotime($cvSayaEducations->until_at));
        $educations->activity = $cvSayaEducations->activity;
        $educations->description = $cvSayaEducations->description;
        $educations->save();
    }

    /**
     * Handle the CvSayaEducations "deleted" event.
     *
     * @param  \App\Models\CvSayaEducations  $cvSayaEducations
     * @return void
     */
    public function deleted(CvEducations $cvSayaEducations)
    {
        //
    }

    /**
     * Handle the CvSayaEducations "restored" event.
     *
     * @param  \App\Models\CvSayaEducations  $cvSayaEducations
     * @return void
     */
    public function restored(CvEducations $cvSayaEducations)
    {
        //
    }

    /**
     * Handle the CvSayaEducations "force deleted" event.
     *
     * @param  \App\Models\CvSayaEducations  $cvSayaEducations
     * @return void
     */
    public function forceDeleted(CvEducations $cvSayaEducations)
    {
        //
    }
}
