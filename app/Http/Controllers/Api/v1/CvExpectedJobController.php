<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\CvExpectedJob;
use App\Models\CandidatePosition;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class CvExpectedJobController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndexByDefault()
    {
        return $this->getIndexByID(null);
    }

    public function getIndexByID($id)
    {
        $user = auth()->user();
        if (!$id) {
            $id = $user->id_kustomer;
        }

        $expectedSalaries = CvExpectedJob::where('user_id', $id)->firstOrFail();

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
            'expected_salary' => 'integer|required',
            'position_reason' => 'string|required|min:50',
            'salary_reason' => 'string|required|min:50',
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
            $position->save();
        }
        $data['expected_position'] = $position->id;
        $expectedSalaries = CvExpectedJob::where('user_id', $user->id_kustomer)->first();
        if (!$expectedSalaries) {
            $expectedSalaries = CvExpectedJob::create($data);

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
    public function getListCandidatePositionsWithPaginate(Request $request)
    {
        $request->validate([
            'keyword' => 'string|nullable',
            'is_verified' => 'nullable|boolean',
            'page' => 'nullable|numeric|gt:0',
            'page_size' => 'nullable|numeric|gt:0'
        ]);
        $keyword = $request->keyword;
        $isVerified = $request->is_verified;
        $page = $request->page ? $request->page  : 1;
        $pageSize = $request->page_size ? $request->page_size : 10;
        $specialities = CandidatePosition::where(function ($query) use ($keyword, $isVerified) {
            if ($keyword != null) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            }
            if (isset($isVerified)) {
                if ($isVerified) {
                    $query->whereNotNull('validated_at');
                } else {
                    $query->whereNull('validated_at');
                }
            }
        })->paginate(
            $perpage = $pageSize,
            $columns =  ['*'],
            $pageName = 'page',
            $pageBody = $page
        );
        $result = $specialities->map(function ($item, $key) {
            return $item;
        });

        return $this->showPaginate('candidates_positions',collect($result), collect($specialities));
    }

    public function getListCandidatePositions(Request $request)
    {
        $request->validate([
            'keyword' => 'string|nullable',
            'is_verified' => 'nullable|boolean'
        ]);
        $keyword = $request->keyword;
        $isVerified = $request->is_verified;
        $specialities = CandidatePosition::where(function ($query) use ($keyword, $isVerified) {
            if ($keyword != null) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            }
            if (isset($isVerified)) {
                if ($isVerified) {
                    $query->whereNotNull('validated_at');
                } else {
                    $query->whereNull('validated_at');
                }
            }
        })->get();

        return $this->showAll($specialities);
    }

    public function createCandidatePositions(Request $request)
    {

        $request->validate([
            'name' => 'string',
        ]);

        $data = $request->all();
        $data['validated_at'] = true;
        $position = CandidatePosition::create($data);

        return $this->showOne($position);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpectedSalaries  $expectedSalaries
     * @return \Illuminate\Http\Response
     */
    public function verifiedCandidatePositions($id)
    {
        $validate = CandidatePosition::where('id', $id)->firstOrFail();
        $validate->validated_at = date('Y-m-d h:i:s', time());
        $validate->save();

        return $this->showOne($validate);
    }

    public function deleteVerifiedCandidatePositions($id)
    {
        $validate = CandidatePosition::where('id', $id)->firstOrFail();
        $validate->validated_at = null;
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string',
        ]);

        $data = $request->all();
        $position = CandidatePosition::where('id', $id)->update($data);

        return $this->showOne($position);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpectedSalaries  $expectedSalaries
     * @return \Illuminate\Http\Response
     */
    public function destroy(CvExpectedJob $expectedSalaries)
    {
        //
    }
}
