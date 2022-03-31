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
        $newLog->identity_card = $cvDocumentations->identity_card;
        $newLog->front_selfie = $cvDocumentations->front_selfie;
        $newLog->left_selfie = $cvDocumentations->left_selfie;
        $newLog->right_selfie = $cvDocumentations->right_selfie;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    public function updated(CvDocumentation $cvDocumentations)
    {
        $newLog = new CvLogDocumentation();
        $newLog->document_id = $cvDocumentations->id;
        $newLog->identity_card = $cvDocumentations->identity_card;
        $newLog->front_selfie = $cvDocumentations->front_selfie;
        $newLog->left_selfie = $cvDocumentations->left_selfie;
        $newLog->right_selfie = $cvDocumentations->right_selfie;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }
}
