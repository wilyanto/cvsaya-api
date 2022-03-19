<?php

namespace App\Observers;

use App\Models\CvEducation;
use App\Models\CvLogEducation;

class CvEducationObserver
{
    /**
     * Handle the CvSayaEducations "created" event.
     *
     * @param  \App\Models\CvSayaEducations  $cvSayaEducations
     * @return void
     */
    public function created(CvEducation $cvSayaEducations)
    {
        $educations = new CvLogEducation();
        $educations->education_id = $cvSayaEducations->id;
        $educations->school = $cvSayaEducations->school;
        $educations->degree_id = $cvSayaEducations->degree_id;
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
    public function updated(CvEducation $cvSayaEducations)
    {
        $educations = new CvLogEducation();
        $educations->education_id = $cvSayaEducations->id;
        $educations->school = $cvSayaEducations->school;
        $educations->degree_id = $cvSayaEducations->degree_id;
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
    public function deleted(CvEducation $cvSayaEducations)
    {
        //
    }

    /**
     * Handle the CvSayaEducations "restored" event.
     *
     * @param  \App\Models\CvSayaEducations  $cvSayaEducations
     * @return void
     */
    public function restored(CvEducation $cvSayaEducations)
    {
        //
    }

    /**
     * Handle the CvSayaEducations "force deleted" event.
     *
     * @param  \App\Models\CvSayaEducations  $cvSayaEducations
     * @return void
     */
    public function forceDeleted(CvEducation $cvSayaEducations)
    {
        //
    }
}
