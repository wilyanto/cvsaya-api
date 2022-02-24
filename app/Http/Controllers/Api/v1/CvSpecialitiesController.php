<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\CvSpecialities;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\CvCertifications;
use App\Models\CvSpecialityCertificates;

class CvSpecialitiesController extends Controller
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

        $specialities = CvSpecialities::where('user_id',$user->id_kustomer)->get();

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
        $specialities = CvSpecialities::create($data);

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
            'certificate_id' => 'required|integer',
        ]);

        $validateCertificate = CvCertifications::where('id',$request->certificate_id)->where('user_id',$user->id_kustomer)->first();
        if(!$validateCertificate){
            return $this->errorResponse('certificate_id not indentify in database',404,40401);
        }

        $validateSpeciality = CvSpecialities::where('id',$request->id)->where('user_id',$user->id_kustomer)->first();
        if(!$validateSpeciality){
            return $this->errorResponse('Id not indentify in database',404,40402);
        }
        $specialityCertificate =CvSpecialityCertificates::where('certificate_id',$request->certificate_id)->where('speciality_id',$request->id)->first();
        if($specialityCertificate){
            return $this->errorResponse('Certificate already used',409,40901);
        }


        $specialityCertificate = new CvSpecialityCertificates();
        $specialityCertificate->certificate_id = $request->certificate_id;
        $specialityCertificate->speciality_id = $request->id;
        $specialityCertificate->save();
        return $this->showOne($validateSpeciality);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Specialities  $specialities
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'filter_by' => 'string|nullable',
        ]);
        // dump($request->filter_by);
        $data=  '%'.$request->filter_by.'%';
        // $specialities = CvSpecialities::where('name',     'LIKE', '%' . 'as' . '%')->get();
        $specialities = CvSpecialities::where('name','LIKE', '%'.$request->filter_by.'%')->withTrashed()->get();
        $specialities = collect($specialities)->pluck('name');
        if($request->filter_by){
            $specialities->push($request->filter_by);
        }
        $specialities = $specialities->unique();
        // $specialities = collect($specialities)->unique('name');

        // dd($specialities);
        return $this->showAll($specialities);

    }

    public function showTopTenList(Request $request){
       $user = auth()->user();

       $specialities = CvSpecialities::select('name')->groupBy('name')->orderByRaw('COUNT(*) DESC')->limit(10)->get();

       $specialities = collect($specialities)->pluck('name');
    //    dd($specialities);

       return $this->showAll($specialities);

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
        $specialities = CvSpecialities::where('user_id',$user->id_kustomer)->where('id',$request->id)->first();
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
    public function destroy(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'id' => 'required|integer',
        ]);

        $specialities = CvSpecialities::where('id', $request->id)->where('user_id', $user->id_kustomer)->first();
        if(!$specialities){
            return $this->errorResponse('id not found',404,40401);
        }
        $specialities->delete();

        return $this->showOne(null);
    }

    public function destroyIntergrity(Request $request){
        $user = auth()->user();
        $request->validate([
            'id' => 'required|integer',
            'certificate_id' => 'required|integer',
        ]);


        $validateCertificate = CvCertifications::where('id',$request->certificate_id)->where('user_id',$user->id_kustomer)->first();
        if(!$validateCertificate){
            return $this->errorResponse('certificate_id not indentify in database',404,40401);
        }

        $validateSpeciality = CvSpecialities::where('id',$request->id)->where('user_id',$user->id_kustomer)->first();
        if(!$validateSpeciality){
            return $this->errorResponse('Id not indentify in database',404,40402);
        }
        $specialityCertificate =CvSpecialityCertificates::where('certificate_id',$request->certificate_id)->where('speciality_id',$request->id)->first();
        if(!$specialityCertificate){
            return $this->errorResponse('Intergity not found',404,40403);
        }
        $specialityCertificate->delete();

        return $this->showOne(null);
    }
}
