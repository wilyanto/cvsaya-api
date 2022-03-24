<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\CvSpeciality;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\CvCertifications;
use App\Models\CvSpecialityCertificate;
use Nette\Utils\Json;

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

        $specialities = CvSpeciality::where('user_id', $user->id_kustomer)->get();

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
        $specialities = CvSpeciality::create($data);

        return $this->showOne($specialities->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function updateDeleteCertificate(array $old , array $new,$speciality){
        $deletes = array_diff($old,$new);
        CvSpecialityCertificate::whereIn('certificate_id',$deletes)->where('speciality_id',$speciality->id)->delete();
        $adds = array_diff($new,$old);
        foreach($adds as $add){
            $certificate = new CvSpecialityCertificate();
            $certificate->certificate_id = $add;
            $certificate->speciality_id =$speciality->id;
            $certificate->save();
        }
    }

    public function updateCertificate(Request $request,$id)
    {
        $user = auth()->user();
        $request->validate([
            'list_certificate' => 'required',
        ]);
        $certificates = $request->list_certificate;
        // dd($request->input());

        $validateSpeciality = CvSpeciality::where('id', $request->id)->where('user_id', $user->id_kustomer)->firstOrFail();

        $havedCertificates = CvSpecialityCertificate::where('speciality_id',$request->id)->pluck('certificate_id')->toArray();
        // dd(var_dump($havedCertificates));
        $this->updateDeleteCertificate($havedCertificates,$certificates,$validateSpeciality);

        return $this->showOne($validateSpeciality);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Specialities  $specialities
     * @return \Illuminate\Http\Response
     */

    public function suggestion(Request $request)
    {
        $request->validate([
            'filter_by' => 'string|nullable',
            'total_suggestions' => 'integer|nullable'
        ]);
        $total = $request->total_suggestions;
        $filterBy = $request->filterBy;
        $specialities = CvSpeciality::where(function ($query) use ($filterBy){
            $query->where('name', 'LIKE', '%' . $filterBy . '%');
        })->select('name')->groupBy('name')->orderByRaw('COUNT(*) DESC')->limit($total)->get();

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
    public function update(Request $request,$id)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string',
        ]);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $specialities = CvSpeciality::where('user_id', $user->id_kustomer)->where('id', $id)->firstOrFail();
        $specialities->update($data);

        return $this->showOne($specialities);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Specialities  $specialities
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $user = auth()->user();

        $specialities = CvSpeciality::where('id', $request->id)->where('user_id', $user->id_kustomer)->first();
        if (!$specialities) {
            return $this->errorResponse('id not found', 404, 40401);
        }
        $specialities->delete();

        return $this->showOne(null);
    }

    public function destroyIntergrity(Request $request)
    {

    }
}
