<?php

namespace App\Observers;

use App\Models\Hobbies;
use App\Models\LogHobbies;

class HobbiesObserver
{
    /**
     * Handle the CvSayaHobbies "created" event.
     *
     * @param  \App\Models\CvSayaHobbies  $cvSayaHobbies
     * @return void
     */
    public function created(Hobbies $cvSayaHobbies)
    {
        $newLog = new LogHobbies();
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
    public function updated(Hobbies $cvSayaHobbies)
    {
        $newLog = new LogHobbies();
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
    public function deleted(Hobbies $cvSayaHobbies)
    {
        //
    }

    /**
     * Handle the CvSayaHobbies "restored" event.
     *
     * @param  \App\Models\CvSayaHobbies  $cvSayaHobbies
     * @return void
     */
    public function restored(Hobbies $cvSayaHobbies)
    {
        //
    }

    /**
     * Handle the CvSayaHobbies "force deleted" event.
     *
     * @param  \App\Models\CvSayaHobbies  $cvSayaHobbies
     * @return void
     */
    public function forceDeleted(Hobbies $cvSayaHobbies)
    {
        //
    }
}
