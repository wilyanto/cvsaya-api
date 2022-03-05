<?php

namespace App\Observers;

use App\Models\CvSosmed;
use App\Models\CvLogSosmed;

class CvSosmedObserver
{
    /**
     * Handle the CvSayaSosmed "created" event.
     *
     * @param  \App\Models\CvSayaSosmed  $cvSayaSosmed
     * @return void
     */
    public function created(CvSosmed $cvSayaSosmed)
    {
        $newLog = new CvLogSosmed();
        $newLog->sosial_media_id=$cvSayaSosmed->id;
        $newLog->instagram = $cvSayaSosmed->instagram;
        $newLog->tiktok = $cvSayaSosmed->tiktok;
        $newLog->youtube = $cvSayaSosmed->youtube;
        $newLog->facebook = $cvSayaSosmed->facebook;
        $newLog->website_url = $cvSayaSosmed->website_url;
        $newLog->created_at =  date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    /**
     * Handle the CvSayaSosmed "updated" event.
     *
     * @param  \App\Models\CvSayaSosmed  $cvSayaSosmed
     * @return void
     */
    public function updated(CvSosmed $cvSayaSosmed)
    {
        $newLog = new CvLogSosmed();
        $newLog->sosial_media_id=$cvSayaSosmed->id;
        $newLog->instagram = $cvSayaSosmed->instagram;
        $newLog->tiktok = $cvSayaSosmed->tiktok;
        $newLog->youtube = $cvSayaSosmed->youtube;
        $newLog->facebook = $cvSayaSosmed->facebook;
        $newLog->website_url = $cvSayaSosmed->website_url;
        $newLog->created_at =  date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    /**
     * Handle the CvSayaSosmed "deleted" event.
     *
     * @param  \App\Models\CvSayaSosmed  $cvSayaSosmed
     * @return void
     */
    public function deleted(CvSosmed $cvSayaSosmed)
    {
        //
    }

    /**
     * Handle the CvSayaSosmed "restored" event.
     *
     * @param  \App\Models\Sosmed  $cvSayaSosmed
     * @return void
     */
    public function restored(CvSosmed $cvSayaSosmed)
    {
        //
    }

    /**
     * Handle the CvSayaSosmed "force deleted" event.
     *
     * @param  \App\Models\CvSayaSosmed  $cvSayaSosmed
     * @return void
     */
    public function forceDeleted(CvSosmed $cvSayaSosmed)
    {
        //
    }
}
