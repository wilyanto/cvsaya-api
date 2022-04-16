<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CvExperienceRequests;
use App\Models\CvExperience;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\Document;
use App\Models\CandidatePosition;
use Illuminate\Validation\Rule;

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

    public function add(CvExperienceRequests $request)
    {
        $validated = $request->validated();

        $user = auth()->user();

        $document = null;
        if ($request->payslip) {
            $documents = Document::where('id', $request->payslip)->firstOrFail();
            $document = $documents->id;
        }
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $positionObjects = json_decode($request->position);
        $position = CandidatePosition::where('id', $positionObjects->id)->orWhere('name', $positionObjects->name)->first();
        if (!$position) {
            $position = CandidatePosition::create([
                'name' => $positionObjects->name,
                'inserted_by' => $user->id_kustomer,
            ]);
        }
        $data['position_id'] = $position->id;
        $data['started_at'] = date('Y-m-d', strtotime($request->started_at));
        $data['ended_at'] = $request->ended_at ? date('Y-m-d', strtotime($request->ended_at)) : null;
        $data['payslip'] = $document;
        unset($data['position']);
        $experience = CvExperience::create($data);
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

    public function update(CvExperienceRequests $request, $id)
    {
        $validated = $request->validated();

        $user = auth()->user();
        $experience = CvExperience::where('id', $id)->where('user_id', $user->id_kustomer)->firstOrFail();
        $positionObjects = $request->position;
        $position = CandidatePosition::where('id', $positionObjects['id'])->orWhere('name', $positionObjects['name'])->first();
        if (!$position) {
            $position = CandidatePosition::create([
                'name' => $positionObjects['name'],
                'inserted_by' => $user->id_kustomer,
            ]);
        }
        $data = $request->all();
        $data['position_id'] = $position->id;
        $data['user_id'] = $user->id_kustomer;
        unset($data['position']);
        if ($request->started_at) {
            if (strtotime($experience->ended_at) > strtotime($request->started_at) || $experience->ended_at == null) {
                $data['started_at'] = date('Y-m-d', strtotime($request->started_at));
            } else {
                return $this->errorResponse('The start at must be a date before saved until at', 422, 42200);
            }
        }
        if ($request->filled('ended_at')) {
            if (strtotime($experience->started_at) < strtotime($request->ended_at)) {
                $experience->ended_at  = date('Y-m-d', strtotime($request->ended_at));
            } else {
                return $this->errorResponse('The until at must be a date after saved start at', 422, 42200);
            }
        } else {
            $experience->ended_at = null;
        }
        $experience = $experience->fill($data);
        if ($experience->isDirty()) {
            $experience->update([$data]);
        }
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
