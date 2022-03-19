<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CvEducation;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\Degree;

class CvEducationsController extends Controller
{
    use ApiResponser;

    protected $connection = 'mysql2';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $educations = CvEducation::where('user_id', $user->id_kustomer)
            ->orderBy('start_at', 'DESC')
            ->orderByRaw("CASE WHEN until_at IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('until_at', 'DESC')
            ->get();

        return $this->showAll($educations);
    }


    public function degreeList(){
        $degrees = Degree::all();

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
            'school' => 'required|string',
            'degree_id' => 'exists:degree,id|required',
            'field_of_study' => 'required|string',
            'grade' => 'required|string',
            'start_at' => 'required|date',
            'until_at' => 'nullable|date|after_or_equal:start_at',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $data['start_at'] = date('Y-m-d', strtotime($data['start_at']));
        if ($request->until_at == null) {
            $data['until_at'] = date('Y-m-d', strtotime($data['until_at']));
        } else {
            $data['until_at'] = null;
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
            'school' => 'nullable|string',
            'degree_id' => 'exists:degree,id|required',
            'field_of_study' => 'nullable|string',
            'grade' => 'nullable|string',
            'start_at' => 'nullable|date',
            'until_at' => 'nullable|date|after_or_equal:start_at',
            'description' => 'nullable|string',
        ]);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        if ($request->start_at != null) {
            $data['start_at'] = date('Y-m-d', strtotime($data['start_at']));
        } else {
            $data['start_at'] = null;
        }
        if ($request->until_at != null) {
            $data['until_at'] = date('Y-m-d', strtotime($data['until_at']));
        } else {
            $data['until_at'] = null;
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
