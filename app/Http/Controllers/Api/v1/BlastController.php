<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\BlastLog;
use App\Models\Jobstreet;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BlastController extends Controller
{
    use ApiResponser;

    public function blast(Request $request)
    {
        // * Soon will use timestamp instead of id
        $request->validate([
            // 'start_timestamp' => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            // 'end_timestamp' => 'required|date_format:Y-m-d\TH:i:s.v\Z|after:start_timestamp',
            'start_id' => 'required|numeric|gte:1',
            'end_id' => 'required|numeric|after:start_id|gte:1',
        ]);

        $jobstreets = Jobstreet::whereBetween('id', [(int) $request->start_id, (int) $request->end_id])->get(['id', 'phone', 'country_code', 'gender', 'email', 'name', 'applied_position']);

        $blastLogs = BlastLog::whereIn('recipient_phone_number', $jobstreets->pluck('phone'))->get(['id', 'recipient_phone_number']);

        $notBlastedJobstreets = $jobstreets->reject(function ($jobstreet) use ($blastLogs) {
            return $blastLogs->contains('recipient_phone_number', $jobstreet->phone);
        });

        $users = User::whereIn('telpon',  $notBlastedJobstreets->pluck('phone')->map(function ($notBlastedJobstreetPhone) {
            return '0' . $notBlastedJobstreetPhone;
        }))->get(['id_kustomer', 'telpon']);

        $notRegisteredJobstreets = $notBlastedJobstreets->reject(function ($notBlastedJobstreet) use ($users) {
            return $users->contains('telpon', '0' . $notBlastedJobstreet->phone);
        });

        $data = $notRegisteredJobstreets->map(function ($notRegisteredJobstreet) {
            // * Will improve this by using maybe queue, job scheduler, etc.
            set_time_limit(30);

            $datum = [
                'country_code' => '62',
                'phone_number' => $notRegisteredJobstreet->phone,
                'gender' => $notRegisteredJobstreet->gender,
                'email' => $notRegisteredJobstreet->email,
                'name' => $notRegisteredJobstreet->name,
                'applied_position' => $notRegisteredJobstreet->applied_position,
                'source' => 'jobstreet'
            ];

            $response = Http::post(config('app.url') . '/api/v1/blast-wa', $datum);

            if ($response->failed()) {
                $datum['status'] = 'fail';
            } else {
                $datum['status'] = 'success';
            }

            sleep(rand(3, 5));

            return $datum;
        });

        return $this->showAll($data);
    }

    public function blastWhatsApp(Request $request)
    {
        $request->merge([
            'gender' => strtolower($request->gender),
            'source' => strtolower($request->source),
        ]);
        $request->validate([
            'country_code' => 'required|string',
            'phone_number' => 'required|string|regex:/^\d{1,13}$/',
            'gender' => 'nullable|string|in:male,female,laki-laki,perempuan',
            'email' => 'required|email',
            'name' => 'required|string',
            'source' => 'required|in:jobstreet',
            'applied_position' => 'required|string'
        ]);

        $message = 'Salam Hangat,' . ($request->gender === 'male' || $request->gender === 'laki-laki' ? ' Pak' : ($request->gender === 'female' || $request->gender === 'perempuan' ? ' Bu' : '')) . ' ' . $request->name . '. 
Merespon lamaran Anda di PT Seluruh Indonesia Online via Jobstreet sebagai ' . $request->applied_position . '.

Kami akan melakukan seleksi awal secara otomatis oleh ATS kami melalui aplikasi Kada.

Mohon balas dengan "Ya" jika anda bersedia untuk melanjutkan proses seleksi.

Terima Kasih
Lisa,
HR Spv';

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
