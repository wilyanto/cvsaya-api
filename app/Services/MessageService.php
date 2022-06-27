<?php

namespace App\Services;

use App\Models\CRMCredential;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessageService
{
    public function constructMessage($template, $paramValue)
    {
        $message = $template;
        foreach ($paramValue as $key => $field) {
            $search = "{{" . $key . "}}";
            $message = str_replace($search, $field, $message);
        }
        return $message;
    }
}
