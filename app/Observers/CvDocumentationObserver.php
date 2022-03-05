<?php

namespace App\Observers;

use App\Models\CvDocumentation;
use App\Models\CvLogDocumentation;

class CvDocumentationObserver
{
    public function created(CvDocumentation $cvDocumentations)
    {
        $newLog = new CvLogDocumentation();
        $newLog->document_id = $cvDocumentations->id;
        $newLog->identity_picture_card = $cvDocumentations->identity_picture_card;
        $newLog->selfie_front = $cvDocumentations->selfie_front;
        $newLog->selfie_left = $cvDocumentations->selfie_left;
        $newLog->selfie_right = $cvDocumentations->selfie_right;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    public function updated(CvDocumentation $cvDocumentations)
    {
        $newLog = new CvLogDocumentation();
        $newLog->document_id = $cvDocumentations->id;
        $newLog->identity_picture_card = $cvDocumentations->identity_picture_card;
        $newLog->selfie_front = $cvDocumentations->selfie_front;
        $newLog->selfie_left = $cvDocumentations->selfie_left;
        $newLog->selfie_right = $cvDocumentations->selfie_right;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }
}
