<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Level;
use App\Traits\ApiResponser;

class PositionController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getListPosition = Position::all();
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
            'priority'=> 'nullable|string',
            'parent_id' => 'nullable|integer',
            'remaining_slot'=> 'nullable|string',
            'company_id'=> 'nullable|string',
        ]);

        $getDepartment = Department::where('id',$request->department_id)->first();
        if(!$getDepartment){
            return $this->errorResponse('department_id not found',404,40401);
        }

        $getLevel = Level::where('id',$request->level_id)->first();
        if(!$getLevel){
            return $this->errorResponse('level_id not found',404,40401);
        }

        $create = Position::create($request->all());


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

       $list = Position::where('parent_id',null)->get();
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
    public function edit(Position $cvSayaPositions)
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
    public function update(Request $request, Position $cvSayaPositions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CvSayaPositions  $cvSayaPositions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $cvSayaPositions)
    {

    }
}
