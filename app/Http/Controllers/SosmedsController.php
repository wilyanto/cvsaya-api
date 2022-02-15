<?php

namespace App\Http\Controllers;

use App\Models\Sosmeds;
use Illuminate\Http\Request;
use  App\Http\Controllers\Controller;
use App\Traits\ApiResponser;


class CvSayaSosmedsController extends Controller
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

        $sosmeds = Sosmeds::where('user_id',$user->id_kustomer)->get();

        return $this->showAll($sosmeds);
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
            'name'=>'required|string',
            'value'=>'required|string'
        ]);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        // dd($data);
        $hobbies = Sosmeds::create($data);

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
     * @param  \App\Models\Sosmeds  $sosmeds
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sosmeds  $sosmeds
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
     * @param  \App\Models\Sosmeds  $sosmeds
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'id'=>'required|integer',
            'name'=>'required|string',
            'value'=>'required|string'
        ]);

        $hobbies = Sosmeds::where('user_id',$user->id_kustomer)->where('id',$request->id)->first();
        if(!$hobbies){
            return $this->errorResponse('id not found',404,40401);
        }
        $hobbies->update($request);

        return $this->showOne($hobbies);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sosmeds  $sosmeds
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'id' => 'required|integer',
        ]);

        $hobbies = Sosmeds::where('user_id',$user->id_kustomer)->where('id',$request->id)->first();
        $hobbies->delete();

        return $this->showOne(null);

    }
}
