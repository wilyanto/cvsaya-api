<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CvExperience;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

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
            ->orderBy('start_at', 'DESC')
            ->orderByRaw("CASE WHEN until_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('until_at', 'DESC')
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
            'position_id' => 'required|exists:App\Models\CandidatePosition,id',
            'employment_type_id' => 'exists:App\Models\EmploymentType,id|required',
            'location' => 'required|string',
            'start_at' => 'required|date',
            'until_at' => 'nullable|date|after:start_at',
            'description' => 'nullable|string',
            'reason_resign' => 'string|min:20',
            'reference' => 'nullable|string',
            'previous_salary' => 'integer|required',
            'slip_salary_img' => 'nullable', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i',
        ]);
        $experience = new CvExperience();
        $experience->user_id = $user->id_kustomer;
        $experience->position_id = $request->position_id;
        $experience->employment_type_id = $request->employment_type_id;
        $experience->location = $request->location;
        $experience->slip_salary_img = $request->slip_salary_img;
        $experience->start_at = date('Y-m-d', strtotime($request->start_at));
        $experience->until_at = date('Y-m-d', strtotime($request->until_at));
        $experience->reference = $request->reference;
        $experience->previous_salary = $request->previous_salary;
        $experience->reason_resign = $request->reason_resign;
        $experience->slip_salary_img = $request->slip_salary_img;
        // dd($experience);
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
            'position_id' => 'nullable|exists:App\Models\CandidatePosition,id',
            'employment_type' => 'exists:App\Models\EmploymentType,id|nullable',
            'location' => 'nullable|string',
            'start_at' => 'nullable|date',
            'until_at' => 'nullable|date|after:start_at',
            'description' => 'nullable|string',
            'reason_resign' => 'string|min:50',
            'reference' => 'nullable|string',
            'previous_salary' => 'integer|required',
            'slip_salary_img' => 'nullable', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i',
        ]);

        $experience = CvExperience::where('id', $id)->where('user_id', $user->id_kustomer)->firstOrFail();
        if ($request->position_id) {
            $experience->position_id = $request->position_id;
        }
        if ($request->employment_type) {
            $experience->employment_type = $request->employment_type;
        }
        if ($request->location) {
            $experience->location = $request->location;
        }

        if (strtotime($experience->until_at) > strtotime($request->start_at)) {
            $experience->start_at = date('Y-m-d', strtotime($request->start_at));
        } else {
            return $this->errorResponse('The start at must be a date before saved until at', 422, 42200);
        }
        if (strtotime($experience->start_at) < strtotime($request->until_at)) {
            $experience->until_at = date('Y-m-d', strtotime($request->until_at));
        } else {
            return $this->errorResponse('The until at must be a date after saved start at', 422, 42200);
        }
        if ($request->slip_salary_img) {
            $experience->slip_salary_img = $request->slip_salary_img;
        }
        if ($request->reference) {
            $experience->reference = $request->reference;
        }
        if ($request->previous_salary) {
            $experience->previous_salary = $request->previous_salary;
        }
        if ($request->reason_resign) {
            $experience->reason_resign = $request->reason_resign;
        }
        if ($request->slip_salary_img) {
            $experience->slip_salary_img = $request->slip_salary_img;
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
