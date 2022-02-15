<?php

namespace App\Http\Controllers\Api\v1\Customer;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    use ApiResponser;

    public function register(Request $request)
    {
        $request->validate([
            'telpon'        => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'nama_lengkap'  => 'required|max:255',
            'email'         => 'required|email',
            'jeniskelamin'  => 'required|in:N,L,P',
            'tgl_lahir'     => 'required|date',
            'password'      => 'required'
        ]);

        $input = $request->all();

        $emailAlreadyExist = User::where('email', $request->email)->first();


        if ($emailAlreadyExist) return $this->errorResponse('Email already registered', 409, 40901);

        if (Auth::attempt(['telpon' => $request->telpon, 'password' => $request->password])) {
            User::where('telpon', '=', $input['telpon'])->update($input);
            $user                   = Auth::user();
            $tokenResult            =  $user->createToken('Personal Access Token');
            $token                  = $tokenResult->token;
            $token->expires_at      = Carbon::now()->addWeeks(1);
            $token->save();

            $success['message']         =  'User registered successfully';
            $success['access_token']    =  $tokenResult->accessToken;
            $success['expires_at']      =  Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();
            $success['user_role']       =  $user->level;
            $success['nama_lengkap']    =  $user->nama_lengkap;

            return $this->showOne($success);
        }
        return $this->errorResponse('Invalid OTP', 401, 40101);
    }

    public function Login(Request $request)
    {
        $request->validate([
            'phone'     => 'required|regex:/^[0-9]+$/|max:13',
            'password'  => 'required',
        ]);

        if (Auth::attempt(['telpon' => $request->phone, 'password' => $request->password])) {
            $user = Auth::user();
            $tokenResult =  $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();

            if ($user->nama_lengkap === null) {
                $success['user_role']     =   'new';
            } else {
                $success['access_token']    =  $tokenResult->accessToken;
                $success['expires_at']      =  Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();
                $success['user_role']       =  $user->level;
                $success['nama_lengkap']    =  $user->nama_lengkap;
            }
            return $this->showOne($success, null, null);
        }

        return $this->errorResponse('Invalid OTP', 401, 40100);
    }


    public function otpRequest(Request $request)
    {
        $request->validate([
            'phone'     => 'required|regex:/^[0-9]+$/|max:13',
        ]);
        $white = [];// json_decode(file_get_contents('whitelist.json'), true);

        if(array_key_exists($request->phone, $white)) {
            $white = $white[$request->phone];
            $mt_rand = bcrypt($white);
            $random = $white;
        } else {
            $random  = mt_rand(100000, 999999);
            $mt_rand = bcrypt($random);
            Http::get('https://danmogot.com/kada/scripts/APIWA/gootp.php?otp=' . $random . ' adalah kode untuk login anda di aplikasi KADA, Harap untuk merahasiakan kode tersebut.&no=' . $request->phone);
        }

        $user = User::where('telpon', $request->phone)->first();

        if ($user) {
            User::where('telpon', $request->phone)->update(['password' => $mt_rand]);
        } else {
            date_default_timezone_set("Asia/Jakarta");
            $dateTime = date("Y-m-d H:i:s");
            $user = User::create([
                'telpon' => $request->phone,
                'password' => $mt_rand,
                'ID_perusahaan' => 0,
                'nama_lengkap' => 'Guest',
                'jeniskelamin' => 'N',
                'level' => 'customer',
                'alamat' => '',
                'email' => null,
                'id_kota' => 1,
                'coll' => 'new',
                'tgl_kus' => $dateTime,
                'blokir' => 'N',
                'diskon' => 0,
                'NIK' => 0,
                'tgl_lahir' => date("Y-m-d",0000-00-00),
                'jam_slot' => "00:00:00",
            ]);
        }
        return $this->showOne($user, null, "OTP: " . $random);
    }

    public function changeEmail(Request $request)
    {
        $request->validate([
            'email'  => 'required|email'
        ]);
        auth()->user()->update(['email' =>  $request->email]);
        $success['message']  =   'Email changed successfully';
        $success['email']    =   $request->email;
        return $this->showOne($success, null, null);
    }

    public function changeName(Request $request)
    {
        $request->validate([
            'username'  => 'required|regex:/^[a-zA-Z ]*$/|max:25'
        ]);
        auth()->user()->update(['nama_lengkap' =>  $request->username]);
        return $this->showOne(auth()->user(), null, null);
    }

    public function changeGender(Request $request)
    {
        $request->validate([
            'gender' => 'required|in:P,L,N'
        ]);

        auth()->user()->update(['jeniskelamin' =>  $request->gender]);
        return $this->showOne(auth()->user());
    }

    public function changeBirthdate(Request $request)
    {
        $request->validate([
            'birthdate' => 'required|date|date_format:Y-m-d',
        ]);
        auth()->user()->update(['tgl_lahir' =>  $request->birthdate]);
        return $this->showOne(auth()->user());
    }

    public function profile()
    {
        return $this->showOne(auth()->user());
    }
}
