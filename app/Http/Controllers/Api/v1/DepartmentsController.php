<?php


namespace App\Http\Controllers\Api\v1;

use App\Models\Departments;
use App\Models\Positions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;

class DepartmentsController extends Controller
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
            'company_id'=>'integer|nullable'
        ]);

        if(!$request->company_id){
            $data = Departments::all();
        }else{
            $data = Departments::where('company_id',$request->company_id)->get();
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
            'company_id' => 'integer|required',
        ]);

        $create = Departments::create($request->all());

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
     * @param  \App\Models\CvSayaDepartments  $cvSayaDepartments
     * @return \Illuminate\Http\Response
     */
    public function show(Departments $cvSayaDepartments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CvSayaDepartments  $cvSayaDepartments
     * @return \Illuminate\Http\Response
     */
    public function edit(Departments $cvSayaDepartments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CvSayaDepartments  $cvSayaDepartments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'integer|required',
            'name' => 'string|nullable',
            'company_id' => 'integer|nullable',
        ]);

        $find = Departments::where('id',$request->id)->first();
        if(!$find){
            return $this->errorResponse('id not found',404,40401);
        }
        $find->update($request->all());

        return $this->showOne($find);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CvSayaDepartments  $cvSayaDepartments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'integer|required',
        ]);

        $find = Departments::where('id',$request->id)->first();
        if(!$find){
            return $this->errorResponse('id not found',404,40401);
        }else{
            $usingLevel =  Positions::where('level_id',$request->id)->count();
            if($usingLevel){
                return $this->errorResponse('Departement still been use',409,40901);
            }
        }
        $find->delete();

        return $this->showOne($find);
    }
}
