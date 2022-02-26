<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Positions;
use Illuminate\Http\Request;
use App\Models\Departments;
use App\Models\Level;
use App\Traits\ApiResponser;

class PositionsController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getListPosition = Positions::all();
        return $this->showAll($getListPosition);
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
            'name' => 'required|string',
            'department_id' => 'required|integer',
            'level_id' => 'required|integer',
            'parent_id' => 'nullable|integer',
            'company_id'=> 'nullable|integer',
        ]);

        $getDepartment = Departments::where('id',$request->department_id)->first();
        if(!$getDepartment){
            return $this->errorResponse('department_id not found',404,40401);
        }

        $getLevel = Level::where('id',$request->level_id)->first();
        if(!$getLevel){
            return $this->errorResponse('level_id not found',404,40401);
        }

        $create = Positions::create($request->all());


        return $this->showOne($create);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CvSayaPositions  $cvSayaPositions
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
       $user =  auth()->user();

       $list = Positions::where('parent_id',null)->get();
    //    dd($list);
       $data = [];
       foreach($list as $item => $object){
            $data[] = [
                'id' => $object->id,
                'name' => $object->name,
                'department_id' => $object->departments,
                'level' => $object->levels,
                'company_id'=> $object->departments->company_id,
                'children' => $object->getAllChildren(),
            ];
       }
       $data = collect($data);

       return $this->showAll($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CvSayaPositions  $cvSayaPositions
     * @return \Illuminate\Http\Response
     */
    public function edit(Positions $cvSayaPositions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CvSayaPositions  $cvSayaPositions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Positions $cvSayaPositions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CvSayaPositions  $cvSayaPositions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Positions $cvSayaPositions)
    {

    }
}
