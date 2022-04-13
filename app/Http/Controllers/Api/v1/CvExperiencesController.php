<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CvExperience;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\Document;
use App\Models\CandidatePosition;

class CvExperiencesController extends Controller
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
        // dump($user);
        $data = [];
        $experiences = CvExperience::where('user_id', $user->id_kustomer)
            ->orderBy('started_at', 'DESC')
            ->orderByRaw("CASE WHEN ended_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('ended_at', 'DESC')
            ->get();
        return $this->showAll($experiences);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'position' => 'required',
            'employment_type_id' => 'exists:App\Models\EmploymentType,id|required',
            'company_name' => 'required|string',
            'company_location' => 'nullable|string',
            'started_at' => 'required|date',
            'ended_at' => 'nullable|date',
            'jobdesc' => 'nullable|string',
            'resign_reason' => 'string|min:20|required',
            'reference' => 'nullable|string',
            'previous_salary' => 'integer|required',
            'payslip' => 'nullable','exists:App\Models\Document,id',
        ]);
        $documents = null;
        if($request->payslip){
            $documents = Document::where('id',$request->payslip)->firstOrFail();
        }
        $experience = new CvExperience();
        $experience->user_id = $user->id_kustomer;
        $positionCollection = json_decode($request->position);
        $position = CandidatePosition::where('id', $positionCollection->id)->orWhere('name', $positionCollection->name)->first();
        if (!$position) {
            $position = new CandidatePosition();
            $position->name = $positionCollection->name;
            $position->inserted_by = $user->id_kustomer;
            $position->save();
        }
        $experience->position_id = $position->id;
        $experience->company_name = $request->company_name;
        $experience->employment_type_id = $request->employment_type_id;
        $experience->company_location = $request->company_location;
        $experience->started_at = date('Y-m-d', strtotime($request->started_at));
        if($request->ended_at){
            $experience->ended_at = date('Y-m-d', strtotime($request->ended_at));
        }
        $experience->jobdesc =  $request->jobdesc;
        $experience->reference = $request->reference;
        $experience->previous_salary = $request->previous_salary;
        $experience->resign_reason = $request->resign_reason;
        $experience->payslip = $documents == null ? null : $documents->id;
        $experience->save();
        return $this->showOne($experience);
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
     * @param  \App\Models\Experiences  $experiences
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Experiences  $experiences
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Experiences  $experiences
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $request->validate([
            'position' => 'required',
            'employment_type' => 'exists:App\Models\EmploymentType,id|nullable',
            'company_name' => 'nullable|string',
            'company_location' => 'nullable|string',
            'started_at' => 'nullable|date',
            'ended_at' => 'nullable|date',
            'jobdesc' => 'nullable|string',
            'resign_reason' => 'string|min:50|nullable',
            'reference' => 'nullable|string',
            'previous_salary' => 'integer|required',
            'payslip' => 'nullable','exists:App\Models\Document,id',
        ]);
        $experience = CvExperience::where('id', $id)->where('user_id', $user->id_kustomer)->firstOrFail();
        if ($request->position) {
            $position = $request->position;
            $position = CandidatePosition::where('id', $position['id'])->orWhere('name', $position['name'])->first();
            if (!$position) {
                $position = new CandidatePosition();
                $position->name = $position->name;
                $position->inserted_by = $user->id_kustomer;
                $position->save();
            }
            $experience->position_id = $position->id;
        }
        if ($request->employment_type) {
            $experience->employment_type = $request->employment_type;
        }
        if ($request->company_name) {
            $experience->company_name = $request->company_name;
        }
        if ($request->company_location) {
            $experience->company_location = $request->company_location;
        }

        if (strtotime($experience->ended_at) > strtotime($request->started_at)) {
            $experience->started_at = date('Y-m-d', strtotime($request->started_at));
        } else {
            return $this->errorResponse('The start at must be a date before saved until at', 422, 42200);
        }
        if($request->ended_at){
            if (strtotime($experience->started_at) < strtotime($request->ended_at)) {
                $experience->ended_at = date('Y-m-d', strtotime($request->ended_at));
            } else {
                return $this->errorResponse('The until at must be a date after saved start at', 422, 42200);
            }
        }else{
            $experience->ended_at = null;
        }
        if ($request->jobdesc) {
            $experience->jobdesc =  $request->jobdesc;
        }
        if ($request->payslip) {
            $documents = Document::where('id',$request->payslip)->firstOrFail();
            $experience->payslip = $documents->id;
        }
        if ($request->reference) {
            $experience->reference = $request->reference;
        }
        if ($request->previous_salary) {
            $experience->previous_salary = $request->previous_salary;
        }
        if ($request->resign_reason) {
            $experience->resign_reason = $request->resign_reason;
        }
        $experience->save();

        return $this->showOne($experience);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Experiences  $experiences
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = auth()->user();

        $experience = CvExperience::where('id', $id)->where('user_id', $user->id_kustomer)->firstOrFail();
        if (!$experience) {
            return $this->errorResponse('id not found', 404, 40401);
        }
        $experience->delete();

        return $this->showOne(null);
    }
}
