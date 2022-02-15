<?php

namespace App\Observers;

use App\Models\Sosmeds;
use App\Models\LogSosmeds;

class SosmedObserver
{
    /**
     * Handle the CvSayaSosmed "created" event.
     *
     * @param  \App\Models\CvSayaSosmed  $cvSayaSosmed
     * @return void
     */
    public function created(Sosmeds $cvSayaSosmed)
    {
        $newLog = new LogSosmeds();
        $newLog->sosial_media_id=$cvSayaSosmed->id;
        $newLog->name = $cvSayaSosmed->name;
        $newLog->value = $cvSayaSosmed->value;
        $newLog->created_at =  date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    /**
     * Handle the CvSayaSosmed "updated" event.
     *
     * @param  \App\Models\CvSayaSosmed  $cvSayaSosmed
     * @return void
     */
    public function updated(Sosmeds $cvSayaSosmed)
    {
        $newLog = new LogSosmeds();
        $newLog->sosial_media_id=$cvSayaSosmed->id;
        $newLog->name = $cvSayaSosmed->name;
        $newLog->value = $cvSayaSosmed->value;
        $newLog->created_at =  date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    /**
     * Handle the CvSayaSosmed "deleted" event.
     *
     * @param  \App\Models\CvSayaSosmed  $cvSayaSosmed
     * @return void
     */
    public function deleted(Sosmeds $cvSayaSosmed)
    {
        //
    }

    /**
     * Handle the CvSayaSosmed "restored" event.
     *
     * @param  \App\Models\Sosmed  $cvSayaSosmed
     * @return void
     */
    public function restored(Sosmeds $cvSayaSosmed)
    {
        //
    }

    /**
     * Handle the CvSayaSosmed "force deleted" event.
     *
     * @param  \App\Models\CvSayaSosmed  $cvSayaSosmed
     * @return void
     */
    public function forceDeleted(Sosmeds $cvSayaSosmed)
    {
        //
    }
}
