<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\CvHobby;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Traits\ApiResponser;


class CvHobbyController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $hobbies = CvHobby::where('candidate_id', $candidate->id)->get();

        return $this->showAll($hobbies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);
        $data = $request->all();
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $data['candidate_id'] = $candidate->id;
        $hobbies = CvHobby::create($data);

        return $this->showOne($hobbies);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hobbies  $hobbies
     * @return \Illuminate\Http\Response
     */
    public function suggestion(Request $request)
    {
        $request->validate([
            'keyword' => 'nullable|string',
            'limit' => 'nullable|integer'
        ]);
        $limit = $request->limit;
        $keyword = $request->keyword;
        $specialities = CvHobby::where(function ($query) use ($keyword) {
            if ($keyword) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            }
        })->select('name')->groupBy('name')->orderByRaw('COUNT(*) DESC')->limit($limit)->get();

        $specialities = collect($specialities)->pluck('name');

        return $this->showAll($specialities);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hobbies  $hobbies
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string'
        ]);
        $data = $request->all();
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $data['candidate_id'] = $candidate->id;
        $hobbies = CvHobby::where('candidate_id', $candidate->id)
            ->where('id', $id)->first();
        if (!$hobbies) {
            return $this->errorResponse('Hobby not found', 409, 40901);
        }
        $hobbies->update($data);

        return $this->showOne($hobbies);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hobbies  $hobbies
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $hobbies = CvHobby::where('id', $id)->where('candidate_id', $candidate->id)->first();
        if (!$hobbies) {
            return $this->errorResponse('Hobby not found', 404, 40401);
        }
        $hobbies->delete();

        return $this->showOne(null);
    }
}
