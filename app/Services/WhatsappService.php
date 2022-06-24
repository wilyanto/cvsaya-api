<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    public function sendMessage($batchMessages)
    {
        $url = 'http://localhost:8001/api/v1/batch/send-whatsapp-message';
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
