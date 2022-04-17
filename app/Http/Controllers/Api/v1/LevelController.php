<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Level;
use App\Models\Position;
use  App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class LevelController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'company_id'=>'string|nullable'
        ]);

        if(!$request->company_id){
            $data = Level::all();
        }else{
            $data = Level::where('company_id',$request->company_id)->get();
        };
        return $this->showAll($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'string|required',
            'company_id' => 'string|required',
        ]);

        $create = Level::create($request->all());

        return $this->showOne($create);
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
     * @param  \App\Models\CvSayaLevel  $cvSayaLevel
     * @return \Illuminate\Http\Response
     */
    public function show(Level $cvSayaLevel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CvSayaLevel  $cvSayaLevel
     * @return \Illuminate\Http\Response
     */
    public function edit(Level $cvSayaLevel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CvSayaLevel  $cvSayaLevel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'string|nullable',
            'company_id' => 'string|nullable',
        ]);

        $find = Level::where('id',$id)->firstOrFail();
        $find->update($request->all());

        return $this->showOne($find);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CvSayaLevel  $cvSayaLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $user = auth()->user();
        $hobbies = Level::where('id', $request->id)->first();
        if(!$hobbies){
            return $this->errorResponse('id not found',404,40401);
        }else{
            $usingLevel =  Position::where('level_id',$request->id)->count();
            if($usingLevel){
                return $this->errorResponse('Level still been use',409,40901);
            }
        }
        $hobbies->delete();

        return $this->showOne(null);
    }
}
