<?php

namespace App\Observers;

use App\Models\CvHobby;
use App\Models\CvLogHobby;

class CvHobbyObserver
{
    /**
     * Handle the CvSayaHobbies "created" event.
     *
     * @param  \App\Models\CvSayaHobbies  $cvSayaHobbies
     * @return void
     */
    public function created(CvHobby $cvSayaHobbies)
    {
        $newLog = new CvLogHobby();
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
    public function updated(CvHobby $cvSayaHobbies)
    {
        $newLog = new CvLogHobby();
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
    public function deleted(CvHobby $cvSayaHobbies)
    {
        //
    }

    /**
     * Handle the CvSayaHobbies "restored" event.
     *
     * @param  \App\Models\CvSayaHobbies  $cvSayaHobbies
     * @return void
     */
    public function restored(CvHobby $cvSayaHobbies)
    {
        //
    }

    /**
     * Handle the CvSayaHobbies "force deleted" event.
     *
     * @param  \App\Models\CvSayaHobbies  $cvSayaHobbies
     * @return void
     */
    public function forceDeleted(CvLogHobby $cvSayaHobbies)
    {
        //
    }
}
