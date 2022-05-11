<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CvCertification;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;


class CvCertificationController extends Controller
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

        $certifications = CvCertification::where('user_id', $user->id_kustomer)
            ->orderBy('issued_at', 'DESC')
            ->orderByRaw("CASE WHEN expired_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('expired_at', 'DESC')
            ->get();

        return $this->showAll($certifications);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string',
            'organization' => 'required|string',
            'issued_at' => 'required|date',
            'expired_at' => 'nullable|after:issued_at',
            'credential_id' => 'string|nullable',
            'credential_url' => 'string|nullable',
        ]);

        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        if (!$request->issued_at) {
            $data['expired_at'] = null;
        }
        $certification = CvCertification::create($data);
        return $this->showOne($certification);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Certifications  $certifications
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'nullable|string',
            'organization' => 'nullable|string',
            'issued_at' => 'required|date',
            'expired_at' => 'nullable|after:issued_at',
            'credential_id' => 'nullable|nullable',
            'credential_url' => 'nullable|nullable',
        ]);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $data['issued_at'] = date('Y-m-d', strtotime($request->issued_at));
        $data['expired_at'] = !empty($request->expired_at) ? date('Y-m-d', strtotime($request->expired_at)) : null;
        $certifications = CvCertification::where('id', $id)->where('user_id', $user->id_kustomer)->first();

        if (!$certifications) {
            return $this->errorResponse('id not found', 404, 40401);
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
    public function destroy($id)
    { {
            $user = auth()->user();
            $certifications = CvCertification::where('id', $id)->where('user_id', $user->id_kustomer)->first();
            if (!$certifications) {
                return $this->errorResponse('id not found', 404, 40401);
            }
            $certifications->delete();

            return $this->showOne(null);
        }
    }
}
