<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CvEducation;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\Degree;

class CvEducationController extends Controller
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
        $educations = CvEducation::where('user_id', $user->id_kustomer)
            ->orderBy('started_at', 'DESC')
            ->orderByRaw("CASE WHEN ended_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('ended_at', 'DESC')
            ->get();

        return $this->showAll($educations);
    }


    public function degreeList()
    {
        $degrees = Degree::whereIn('id', [1,2,3,4,5,6])->get();

        return $this->showAll($degrees);
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
            'instance' => 'required|string',
            'degree_id' => 'exists:degrees,id|required',
            'field_of_study' => 'required|string',
            'grade' => 'required|string',
            'started_at' => 'required|date',
            'ended_at' => 'nullable|date|after:started_at',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $data['started_at'] = date('Y-m-d', strtotime($data['started_at']));
        if ($request->ended_at == null) {
            $data['ended_at'] = date('Y-m-d', strtotime($data['ended_at']));
        } else {
            $data['ended_at'] = null;
        }
        $educations = CvEducation::create($data);
        return $this->showOne($educations);
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
     * @param  \App\Models\Educations  $educations
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Educations  $educations
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Educations  $educations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        // dump($request->input());
        $request->validate([
            'instance' => 'nullable|string',
            'degree_id' => 'exists:degrees,id|required',
            'field_of_study' => 'nullable|string',
            'grade' => 'nullable|string',
            'started_at' => 'nullable|date',
            'ended_at' => 'nullable|date|after:started_at',
            'description' => 'nullable|string',
        ]);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        if ($request->started_at != null) {
            $data['started_at'] = date('Y-m-d', strtotime($data['started_at']));
        } else {
            $data['started_at'] = null;
        }
        if ($request->ended_at != null) {
            $data['ended_at'] = date('Y-m-d', strtotime($data['ended_at']));
        } else {
            $data['ended_at'] = null;
        }
        $educations = CvEducation::where('id', $id)->where('user_id', $user->id_kustomer)->first();
        if (!$educations) {
            return $this->errorResponse('id not found', 404, 40401);
        }

        $educations->update($data);

        return $this->showOne($educations);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Educations  $educations
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $educations = CvEducation::where('id', $id)->where('user_id', $user->id_kustomer)->first();
        if (!$educations) {
            return $this->errorResponse('id not found', 404, 40401);
        }
        $educations->delete();

        return $this->showOne(null);
    }
}
