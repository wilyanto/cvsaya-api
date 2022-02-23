<?php

namespace App\Observers;

use App\Models\CvSosmeds;
use App\Models\CvLogSosmeds;

class CvSosmedObserver
{
    /**
     * Handle the CvSayaSosmed "created" event.
     *
     * @param  \App\Models\CvSayaSosmed  $cvSayaSosmed
     * @return void
     */
    public function created(CvSosmeds $cvSayaSosmed)
    {
        $newLog = new CvLogSosmeds();
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
    public function updated(CvSosmeds $cvSayaSosmed)
    {
        $newLog = new CvLogSosmeds();
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
    public function deleted(CvSosmeds $cvSayaSosmed)
    {
        //
    }

    /**
     * Handle the CvSayaSosmed "restored" event.
     *
     * @param  \App\Models\Sosmed  $cvSayaSosmed
     * @return void
     */
    public function restored(CvSosmeds $cvSayaSosmed)
    {
        //
    }

    /**
     * Handle the CvSayaSosmed "force deleted" event.
     *
     * @param  \App\Models\CvSayaSosmed  $cvSayaSosmed
     * @return void
     */
    public function forceDeleted(CvSosmeds $cvSayaSosmed)
    {
        //
    }
}
