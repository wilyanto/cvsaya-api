<?php

namespace App\Services;

use App\Models\CRMCredential;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    public function sendMessage($batchMessages)
    {
        $url = env('ECRM_URL') . CRMCredential::URL_SEND_BATCH_MESSAGE;
        $body = [
            'messages' => $batchMessages,
        ];
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withOptions([
            'debug' => true,
        ])->acceptJson()->post(
            $url,
            $body,
        );

        return response()->json($response->json(), $response->status());
    }
}
