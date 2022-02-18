<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Certifications;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class CertificationsController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth()->user();

        $certifications = Certifications::where('user_id',$user->id_kustomer)->get();

        return $this->showAll($certifications);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string',
            'organization' => 'required|string',
            'issued_at' => 'required|date',
            'expired_at' => 'nullable|after:start_at',
            'credential_id' => 'string|nullable',
            'credential_url' => 'string|nullable',
        ]);
        // dump($user);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $data['issued_at'] = date('Y-m-d',strtotime($request->issued_at));
        $data['expired_at'] = date('Y-m-d',strtotime($request->expired_at));
        // dd($data);
        // dd($data);
        $certifications = Certifications::create($data);

        return $this->showOne($certifications);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Certifications  $certifications
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Certifications  $certifications
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Certifications  $certifications
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'id'=>'required|integer',
            'name' => 'nullable|string',
            'organization' => 'nullable|string',
            'issued_at' => 'nullable|date',
            'expired_at' => 'nullable|after:start_at',
            'credential_id' => 'nullable|nullable',
            'credential_url' => 'nullable|nullable',
        ]);
        // dump($user);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $data['issued_at'] = date('Y-m-d',strtotime($request->issued_at));
        $data['expired_at'] = date('Y-m-d',strtotime($request->expired_at));
        // dd($data);
        // dd($data);
        $certifications = Certifications::where('id',$request->id)->where('user_id',$user->id_kustomer)->first();

        if(!$certifications){
            return $this->errorResponse('id not found',404,40401);
        }
        $certifications->update($data);

        return $this->showOne($certifications);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Certifications  $certifications
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        {
            $user = auth()->user();
            $request->validate([
                'id'=> 'required|integer',
            ]);
            $certifications = Certifications::where('id',$request->id)->where('user_id',$user->id_kustomer)->first();
            if(!$certifications){
                return $this->errorResponse('id not found',404,40401);
            }
            $certifications->delete();

            return $this->showOne(null);

        }
    }
}
