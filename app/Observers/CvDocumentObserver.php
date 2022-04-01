<?php

namespace App\Observers;

use App\Models\CvDocument;
use App\Models\CvLogDocumentation;

class CvDocumentObserver
{
    public function created(CvDocument $cvDocuments)
    {
        $newLog = new CvLogDocumentation();
        $newLog->document_id = $cvDocuments->id;
        $newLog->identity_card = $cvDocuments->identity_card;
        $newLog->front_selfie = $cvDocuments->front_selfie;
        $newLog->left_selfie = $cvDocuments->left_selfie;
        $newLog->right_selfie = $cvDocuments->right_selfie;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }

    public function updated(CvDocument $cvDocuments)
    {
        $newLog = new CvLogDocumentation();
        $newLog->document_id = $cvDocuments->id;
        $newLog->identity_card = $cvDocuments->identity_card;
        $newLog->front_selfie = $cvDocuments->front_selfie;
        $newLog->left_selfie = $cvDocuments->left_selfie;
        $newLog->right_selfie = $cvDocuments->right_selfie;
        $newLog->created_at = date('Y-m-d h:i:s',time());
        $newLog->save();
    }
}
