<?php

namespace App\Http\Controllers;

use App\Models\Specialities;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use  App\Http\Controllers\Controller;
use App\Models\Certifications;
use App\Models\SpecialityCertificates;

class CvSayaSpecialitiesController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $specialities = Specialities::where('user_id',$user->id_kustomer)->get();

        return $this->showAll(collect($specialities->toArray()));

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
        ]);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $specialities = Specialities::create($data);

        return $this->showOne($specialities->toArray());
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
            'id' => 'required|integer',
            'certificate_id_old' => 'required|integer',
            'certificate_id_new' => 'nullable|integer',
        ]);

        $validateCertificate = Certifications::where('id',$request->certificate_id_old)->where('user_id',$user->id_kustomer)->first();
        if(!$validateCertificate){
            return $this->errorResponse('certificate_id_old not indentify in database',422,42200);
        }

        $validateSpeciality = Specialities::where('id',$request->id)->where('user_id',$user->id_kustomer)->first();
        if(!$validateSpeciality){
            return $this->errorResponse('Id not indentify in database',422,42200);
        }


        $specialityCertificate = SpecialityCertificates::where('speciality_id',$request->id)->where('certificate_id',$request->certificate_id_old)->first();
        if($specialityCertificate){
            $validateCertificate = Certifications::where('id',$request->certificate_id_new)->where('user_id',$user->id_kustomer)->first();
            if(!$validateCertificate){
                return $this->errorResponse('certificate_id_new not indentify in database',422,42200);
            }

            $specialityCertificate->certificate_id = $request->certificate_id_new;
        }else{
            $specialityCertificate = new SpecialityCertificates();
            $specialityCertificate->certificate_id = $request->certificate_id_old;
            $specialityCertificate->speciality_id = $request->id;
        }

        $specialityCertificate->save();
        return $this->showOne($validateSpeciality);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Specialities  $specialities
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Specialities  $specialities
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Specialities  $specialities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string',
        ]);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $specialities = Specialities::where('user_id',$user->id_kustomer)->where('id',$request->id)->first();
        if(!$specialities){
            return $this->errorResponse('id not found',409,40901);
        }
        $specialities->update($data);

        return $this->showOne($specialities);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Specialities  $specialities
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {

    }
}
