<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CvExpectedPosition;
use App\Models\CandidatePosition;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Redis;

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

        $expectedSalaries = CvExpectedPosition::where('user_id', $user->id_kustomer)->firstOrFail();

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
    public function storeOrUpdate(Request $request)
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
        $position = CandidatePosition::where('id', $data['expected_position']->id)->orWhere('name', $data['expected_position']->name)->first();
        if (!$position) {
            $position = new CandidatePosition();
            $position->name = $data['expected_position']->name;
            $position->inserted_by = $user->id_kustomer;
            $position->save();
        }
        $data['expected_position'] = $position->id;
        $expectedSalaries = CvExpectedPosition::where('user_id', $user->id_kustomer)->first();
        if (!$expectedSalaries) {
            $expectedSalaries = CvExpectedPosition::create($data);

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
    public function getListCandidatePositions(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'string|nullable',
            'is_verified' => 'nullable|boolean'
        ]);
        $name = $request->name;
        $isVerfied = $request->is_verified;
        // dd($request->all());
        $specialities = CandidatePosition::where(function ($qurey) use ($name, $isVerfied) {
            if ($name != null) {
                $qurey->where('name', 'LIKE', '%' . $name . '%');
            }
            if (isset($isVerfied)) {
                if ($isVerfied) {
                    $qurey->whereNotNull('validated_at');
                } else {
                    $qurey->whereNull('validated_at');
                }
            }
        })->get();

        // $specialities = CandidatePositions::where('name','LIKE', '%'.$request->filter_by.'%')->whereNotNull('validated_at')->get();

        return $this->showAll($specialities);
    }

    public function createCandidatePositions(Request $request)
    {

        $user = auth()->user();

        $request->validate([
            'name' => 'string',
        ]);

        $data = $request->all();
        $data['inserted_by'] = $user->id_kustomer;
        $position = CandidatePosition::create($data);

        return $this->showOne($position);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpectedSalaries  $expectedSalaries
     * @return \Illuminate\Http\Response
     */
    public function updateVerfiedCandidatePositions(Request $request, $id)
    {
        $user = auth()->user();
        $validate = CandidatePosition::where('id', $request->id)->firstOrFail();

        if (!$validate->validated_at) {
            $validate->validated_at = date('Y-m-d h:i:s', time());
        } else {
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
    public function update(Request $request, CvExpectedPosition $expectedSalaries)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpectedSalaries  $expectedSalaries
     * @return \Illuminate\Http\Response
     */
    public function destroy(CvExpectedPosition $expectedSalaries)
    {
        //
    }
}
