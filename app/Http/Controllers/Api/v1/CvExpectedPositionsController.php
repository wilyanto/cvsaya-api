<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CvExpectedPositions;
use App\Models\CandidatePositions;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class CvExpectedPositionsController extends Controller
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

        $expectedSalaries = CvExpectedPositions::where('user_id',$user->id_kustomer)->first();
        if(!$expectedSalaries){
            return $this->errorResponse('Expected Positions Not found',404,40401);
        }
        return $this->showOne($expectedSalaries);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'expected_position' => 'required',
            'expected_amount' => 'integer|required',
            'reason_position' => 'string|required|min:50',
            'reasons' => 'string|required|min:50',
        ]);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        // dd($data);
        $data['expected_position'] = json_decode($request->expected_position);
        // dump($data);
        $position = CandidatePositions::where('id',$data['expected_position']->id)->orWhere('name',$data['expected_position']->name)->first();
        if(!$position){
            $position = new CandidatePositions();
            $position->name = $data['expected_position']->name;
            $position->inserted_by = $user->id_kustomer;
            $position->save();
        }
        $data['expected_position'] = $position->id;
        $expectedSalaries = CvExpectedPositions::where('user_id',$user->id_kustomer)->first();
        if(!$expectedSalaries){
            $expectedSalaries = CvExpectedPositions::create($data);

            return $this->showOne($expectedSalaries);
        }
        $expectedSalaries->update($data);

        return $this->showOne($expectedSalaries);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExpectedSalaries  $expectedSalaries
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'string|nullable',
            'is_verfied' => 'nullable|boolean'
        ]);
        $name = $request->name;
        $isVerfied = $request->is_verfied;

        $specialities = CandidatePositions::where(function ($qurey) use ($name,$isVerfied){
            if($name != null){
                $qurey->where('name','LIKE', '%'.$name.'%');
            }
            if($isVerfied == false){
                $qurey->whereNull('validated_at');
            }elseif($isVerfied == true){
                $qurey->whereNotNull('validated_at');
            }
        })->get();

        // $specialities = CandidatePositions::where('name','LIKE', '%'.$request->filter_by.'%')->whereNotNull('validated_at')->get();

        return $this->showAll($specialities);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpectedSalaries  $expectedSalaries
     * @return \Illuminate\Http\Response
     */
    public function updateVerfied(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'id' => 'integer|required'
        ]);

        $validate = CandidatePositions::where('id',$request->id)->first();
        if(!$validate){

        }

        if(!$validate->validated_at){
            $validate->validated_at = date('Y-m-d h:i:s',time());
        }else{
            $validate->validated_at = null;
        }
        $validate->save();


        return $this->showOne($validate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpectedSalaries  $expectedSalaries
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CvExpectedPositions $expectedSalaries)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpectedSalaries  $expectedSalaries
     * @return \Illuminate\Http\Response
     */
    public function destroy(CvExpectedPositions $expectedSalaries)
    {
        //
    }
}
