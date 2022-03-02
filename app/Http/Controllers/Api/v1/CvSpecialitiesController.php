<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\CvSpecialities;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\CvCertifications;
use App\Models\CvSpecialityCertificates;
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

        $specialities = CvSpecialities::where('user_id', $user->id_kustomer)->get();

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

    public function updateDeleteCertificate(array $old , array $new,$speciality){
        $deletes = array_diff($old,$new);
        CvSpecialityCertificates::whereIn('certificate_id',$deletes)->where('speciality_id',$speciality->id)->delete();
        $adds = array_diff($new,$old);
        foreach($adds as $add){
            $certificate = new CvSpecialityCertificates();
            $certificate->certificate_id = $add;
            $certificate->speciality_id =$speciality->id;
            $certificate->save();
        }
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'id' => 'required',
            'list_certificate' => 'required',
        ]);
        $certificates = json_decode($request->list_certificate);
        // dd($request->input());

        $validateSpeciality = CvSpecialities::where('id', $request->id)->where('user_id', $user->id_kustomer)->first();
        if (!$validateSpeciality) {
            return $this->errorResponse('Id not indentify in database', 404, 40402);
        }


        $havedCertificates = CvSpecialityCertificates::where('speciality_id',$request->id)->pluck('certificate_id')->toArray();
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
    public function show(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'filter_by' => 'string|nullable',
        ]);
        $specialities = CvSpecialities::where('name', 'LIKE', '%' . $request->filter_by . '%')->withTrashed()->get();
        $specialities = collect($specialities)->pluck('name');
        if ($request->filter_by) {
            $specialities->push($request->filter_by);
        }
        $specialities = $specialities->unique();

        return $this->showAll($specialities);
    }

    public function showTopTenList(Request $request)
    {
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
        $specialities = CvSpecialities::where('user_id', $user->id_kustomer)->where('id', $request->id)->first();
        if (!$specialities) {
            return $this->errorResponse('id not found', 409, 40901);
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
