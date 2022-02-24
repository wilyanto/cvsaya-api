<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CvExperiences;
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
        $experiences = CvExperiences::where('user_id', $user->id_kustomer)->get();
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
            'position' => 'required|string',
            'employment_type' => 'required|in:full-time,part-time,self-employed,freelance,contract,internship,apprenticeship,seasonal',
            'location' => 'required|string',
            'start_at' => 'required|date',
            'until_at' => 'nullable|date|after:start_at',
            'description' => 'nullable|string',
        ]);
        $experience = new CvExperiences();
        $experience->user_id = $user->id_kustomer;
        $experience->position = $request->position;
        $experience->employment_type = $request->employment_type;
        $experience->location = $request->location;
        $experience->start_at = date('Y-m-d', strtotime($request->start_at));
        $experience->until_at = date('Y-m-d', strtotime($request->until_at));
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
    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'id' => 'required|integer',
            'position' => 'nullable|string',
            'employment_type' => 'nullable|in:full-time,part-time,self-employed,freelance,contract,internship,apprenticeship,seasonal',
            'location' => 'nullable|string',
            'start_at' => 'nullable|date',
            'until_at' => 'nullable|date|after:start_at',
            'description' => 'nullable|string',
        ]);

        $experience = CvExperiences::where('id', $request->id)->where('user_id', $user->id_kustomer)->first();
        if (!$experience) {
            return $this->errorResponse('Id not indentify in database', 422, 42200);
        }
        if ($request->position) {
            $experience->position = $request->position;
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
        $experience->save();

        return $this->showOne($experience);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Experiences  $experiences
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'id' => 'required|integer',
        ]);

        $experience = CvExperiences::where('id', $request->id)->where('user_id', $user->id_kustomer)->first();
        if(!$experience){
            return $this->errorResponse('id not found',404,40401);
        }
        $experience->delete();

        return $this->showOne(null);
    }
}
