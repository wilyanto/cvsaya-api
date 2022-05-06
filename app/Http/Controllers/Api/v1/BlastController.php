<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\BlastLog;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BlastController extends Controller
{
    use ApiResponser;

    public function blast(Request $request)
    {
        $request->merge([
            'gender' => strtolower($request->gender),
            'source' => strtolower($request->source),
        ]);
        $request->validate([
            'country_code' => 'required|string',
            'phone_number' => 'required|string|regex:/^\d{1,13}$/',
            'gender' => 'nullable|string|in:male,female',
            'email' => 'required|email',
            'name' => 'required|string',
            'source' => 'required|in:jobstreet',
        ]);

        $message = 'Selamat pagi,' . ($request->gender === 'male' ? ' Pak' : ($request->gender === 'female' ? ' Bu' : '')) . ' ' . $request->name . '. Diinformasikan kepada seluruh pendaftar CVsaya.id untuk memperbaharui data di Kada agar diseleksi secara otomatis oleh ATS kami.

Untuk Kada dapat diunduh dari link di bawah ini:
Android:
https://play.google.com/store/apps/details?id=id.kada.mobileapp&hl=in&gl=US
AppStore:
https://apps.apple.com/id/app/kada-id/id1602215141

Atas perhatiannya saya ucapkan terima kasih.

*Mohon balas pesan ini dengan "YA" untuk melanjutkan proses.';

        $response = Http::asForm()->withHeaders(['Authorization' => config('blast.authorization_token')])->post('https://md.fonnte.com/api/send_message.php', [
            'phone' => $request->country_code . $request->phone_number,
            'type' => 'text',
            'text' => $message,
        ]);

        $newBlastLogRecord = [
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'sender_country_code' => '62',
            'sender_phone_number' => config('blast.phone_number'),
            'recipient_country_code' => $request->country_code,
            'recipient_phone_number' => $request->phone_number,
            'message' => $message,
            'source' => $request->source
        ];

        if ($response->failed()) {
            $newBlastLogRecord['status'] = 'fail';
            BlastLog::create($newBlastLogRecord);

            return $this->errorResponse('Something went wrong, please try again.', 502, 50200);
        }

        $newBlastLogRecord['status'] = 'success';
        BlastLog::create($newBlastLogRecord);

        return $this->showOne($response);
    }
}