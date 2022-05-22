<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\CvSpeciality;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CvSpecialityCertificate;

class CvSpecialityController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $specialities = CvSpeciality::where('candidate_id', $candidate->id)->get();

        return $this->showAll($specialities);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $data = $request->all();
        $data['candidate_id'] = $candidate->id;
        $specialities = CvSpeciality::create($data);

        return $this->showOne($specialities->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Specialities  $specialities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $data = $request->all();
        $data['candidate_id'] = $candidate->id;
        $speciality = CvSpeciality::where('id', $id)
            ->where('candidate_id', $candidate->id)
            ->firstOrFail();
        $speciality->update($data);

        return $this->showOne($speciality);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Specialities  $specialities
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $specialities = CvSpeciality::where('id', $request->id)->where('candidate_id', $candidate->id)->first();
        if (!$specialities) {
            return $this->errorResponse('Speciality not found', 404, 40401);
        }
        $specialities->delete();

        return $this->showOne(null);
    }

    public function updateDeleteCertificate(array $old, array $new, $speciality)
    {
        $deletes = array_diff($old, $new);
        $value = CvSpecialityCertificate::whereIn('certificate_id', $deletes)->where('speciality_id', $speciality->id)->delete();
        $adds = array_diff($new, $old);
        foreach ($adds as $add) {
            $certificate = new CvSpecialityCertificate();
            $certificate->certificate_id = $add;
            $certificate->speciality_id = $speciality->id;
            $certificate->save();
        }
        return $value;
    }

    public function updateCertificate(Request $request, $id)
    {
        $request->validate([
            'certificates' => 'array',
        ]);
        $certificates = $request->certificates;

        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $validateSpeciality = CvSpeciality::where('id', $request->id)
            ->where('candidate_id', $candidate->id)
            ->firstOrFail();

        $havedCertificates = CvSpecialityCertificate::where('speciality_id', $request->id)->pluck('certificate_id')->toArray();
        $this->updateDeleteCertificate($havedCertificates, $certificates, $validateSpeciality);
        $validateSpeciality = $validateSpeciality->fresh();
        return $this->showOne($validateSpeciality);
    }

    public function suggestion(Request $request)
    {
        $request->validate([
            'keyword' => 'string|nullable',
            'limit' => 'integer|nullable'
        ]);
        $limit = $request->limit;
        $keyword = $request->keyword;
        $specialities = CvSpeciality::where(function ($query) use ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        })->select('name')->groupBy('name')->orderByRaw('COUNT(*) DESC')->limit($limit)->get();

        $specialities = collect($specialities)->pluck('name');

        return $this->showAll($specialities);
    }
}
