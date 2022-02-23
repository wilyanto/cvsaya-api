<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CvExpectedSalaries;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class CvExpectedSalariesController extends Controller
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

        $expectedSalaries = CvExpectedSalaries::where('user_id',$user->id_kustomer)->first();
        if(!$expectedSalaries){
            return $this->errorResponse('Expected Salaries Not found',404,40401);
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
            'expected_position' => 'exists:App\Models\Positions,id|required',
            'expected_amount' => 'integer|required',
            'reason_position' => 'string|required|min:50',
            'reasons' => 'string|required|min:50',
        ]);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        // dd($data);

        $expectedSalaries = CvExpectedSalaries::where('user_id',$user->id_kustomer)->first();
        if(!$expectedSalaries){
            $expectedSalaries = CvExpectedSalaries::create($data);

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
    public function show(CvExpectedSalaries $expectedSalaries)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpectedSalaries  $expectedSalaries
     * @return \Illuminate\Http\Response
     */
    public function edit(CvExpectedSalaries $expectedSalaries)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpectedSalaries  $expectedSalaries
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CvExpectedSalaries $expectedSalaries)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpectedSalaries  $expectedSalaries
     * @return \Illuminate\Http\Response
     */
    public function destroy(CvExpectedSalaries $expectedSalaries)
    {
        //
    }
}
