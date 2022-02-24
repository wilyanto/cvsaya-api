<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\CvHobbies;
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

        $hobbies = CvHobbies::where('user_id', $user->id_kustomer)->get();

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
        $hobbies = CvHobbies::create($data);

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
    public function show()
    {
        //
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
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string'
        ]);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $hobbies = CvHobbies::where('user_id', $user->id_kustomer)->where('id', $request->id)->first();
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
    public function destroy(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'id' => 'required|integer',
        ]);
        $hobbies = CvHobbies::where('id', $request->id)->where('user_id', $user->id_kustomer)->first();
        if(!$hobbies){
            return $this->errorResponse('id not found',404,40401);
        }
        $hobbies->delete();

        return $this->showOne(null);
    }
}
