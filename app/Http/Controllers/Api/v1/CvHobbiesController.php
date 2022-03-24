<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\CvHobby;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  App\Http\Controllers\Controller;
use App\Traits\ApiResponser;


class CvHobbiesController extends Controller
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

        $hobbies = CvHobby::where('user_id', $user->id_kustomer)->get();

        return $this->showAll($hobbies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string'
        ]);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $hobbies = CvHobby::create($data);

        return $this->showOne($hobbies);
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
     * @param  \App\Models\Hobbies  $hobbies
     * @return \Illuminate\Http\Response
     */
    public function suggestion(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'filter_by' => 'string|nullable',
            'total_suggestions' => 'integer|nullable'
        ]);
        $total = $request->total_suggestions;
        $filterBy = $request->filterBy;
        $specialities = CvHobby::where(function ($query) use ($filterBy) {
            if($filterBy){
                $query->where('name', 'LIKE', '%' . $filterBy . '%');
            }
        })->select('name')->groupBy('name')->orderByRaw('COUNT(*) DESC')->limit($total)->get();

        $specialities = collect($specialities)->pluck('name');
        //    dd($specialities);

        return $this->showAll($specialities);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hobbies  $hobbies
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
     * @param  \App\Models\Hobbies  $hobbies
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string'
        ]);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $hobbies = CvHobby::where('user_id', $user->id_kustomer)->where('id', $id)->first();
        if (!$hobbies) {
            return $this->errorResponse('id not found', 409, 40901);
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
        $user = auth()->user();
        $hobbies = CvHobby::where('id', $id)->where('user_id', $user->id_kustomer)->first();
        if (!$hobbies) {
            return $this->errorResponse('id not found', 404, 40401);
        }
        $hobbies->delete();

        return $this->showOne(null);
    }
}
