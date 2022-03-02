<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CvEducations;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

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
        $educations = CvEducations::where('user_id',$user->id_kustomer)->get();

        return $this->showAll($educations);
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
            'degree' => 'required|string',
            'field_of_study' => 'required|string',
            'grade' => 'required|string',
            'start_at' => 'required|date',
            'until_at' => 'nullable|date|after_or_equal:start_at',
            'activity' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $data['start_at'] = date('Y-m-d',strtotime($data['start_at']));
        $data['until_at'] = date('Y-m-d',strtotime($data['until_at']));
        $educations = CvEducations::create($data);
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
    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'id'=>'required|integer',
            'school' => 'nullable|string',
            'degree' => 'nullable|string',
            'field_of_study' => 'nullable|string',
            'grade' => 'nullable|string',
            'start_at' => 'nullable|date',
            'until_at' => 'nullable|date|after_or_equal:start_at',
            'activity' => 'nullable|string',
            'description' => 'nullable|string',
        ]);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $data['start_at'] = date('Y-m-d',strtotime($data['start_at']));
        $data['until_at'] = date('Y-m-d',strtotime($data['until_at']));
        $educations = CvEducations::where('id',$request->id)->where('user_id',$user->id_kustomer)->first();
        if(!$educations){
            return $this->errorResponse('id not found',404,40401);
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
    public function destroy(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'id'=> 'required|integer',
        ]);
        $educations = CvEducations::where('id',$request->id)->where('user_id',$user->id_kustomer)->first();
        if(!$educations){
            return $this->errorResponse('id not found',404,40401);
        }
        $educations->delete();

        return $this->showOne(null);

    }
}
