<?php

namespace App\Observers;

use App\Models\CvDocumentations;
use App\Models\CvLogDocumentations;

class CvDocumentationsObserver
{
    public function created(CvDocumentations $cvDocumentations)
    {
        $newLog = new CvLogDocumentations();
        $newLog->document_id = $cvDocumentations->id;
        $newLog->identity_picture_card = $cvDocumentations->identity_picture_card;
        $newLog->selfie_front = $cvDocumentations->selfie_front;
        $newLog->selfie_left = $cvDocumentations->selfie_left;
        $newLog->selfie_right = $cvDocumentations->selfie_right;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    public function updated(CvDocumentations $cvDocumentations)
    {
        $newLog = new CvLogDocumentations();
        $newLog->document_id = $cvDocumentations->id;
        $newLog->identity_picture_card = $cvDocumentations->identity_picture_card;
        $newLog->selfie_front = $cvDocumentations->selfie_front;
        $newLog->selfie_left = $cvDocumentations->selfie_left;
        $newLog->selfie_right = $cvDocumentations->selfie_right;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }
}
