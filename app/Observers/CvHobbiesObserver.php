<?php

namespace App\Observers;

use App\Models\CvHobbies;
use App\Models\CvLogHobbies;

class CvHobbiesObserver
{
    /**
     * Handle the CvSayaHobbies "created" event.
     *
     * @param  \App\Models\CvSayaHobbies  $cvSayaHobbies
     * @return void
     */
    public function created(CvHobbies $cvSayaHobbies)
    {
        $newLog = new CvLogHobbies();
        $newLog->hobby_id = $cvSayaHobbies->id;
        $newLog->name = $cvSayaHobbies->name;
        $newLog->created_at =  date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    /**
     * Handle the CvSayaHobbies "updated" event.
     *
     * @param  \App\Models\CvSayaHobbies  $cvSayaHobbies
     * @return void
     */
    public function updated(CvHobbies $cvSayaHobbies)
    {
        $newLog = new CvLogHobbies();
        $newLog->hobby_id = $cvSayaHobbies->id;
        $newLog->name = $cvSayaHobbies->name;
        $newLog->created_at =  date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    /**
     * Handle the CvSayaHobbies "deleted" event.
     *
     * @param  \App\Models\CvSayaHobbies  $cvSayaHobbies
     * @return void
     */
    public function deleted(CvHobbies $cvSayaHobbies)
    {
        //
    }

    /**
     * Handle the CvSayaHobbies "restored" event.
     *
     * @param  \App\Models\CvSayaHobbies  $cvSayaHobbies
     * @return void
     */
    public function restored(CvHobbies $cvSayaHobbies)
    {
        //
    }

    /**
     * Handle the CvSayaHobbies "force deleted" event.
     *
     * @param  \App\Models\CvSayaHobbies  $cvSayaHobbies
     * @return void
     */
    public function forceDeleted(CvLogHobbies $cvSayaHobbies)
    {
        //
    }
}
