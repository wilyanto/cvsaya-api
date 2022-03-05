<?php


namespace App\Http\Controllers\Api\v1;

use App\Models\Department;
use App\Models\Position;
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
            'company_id'=>'string|nullable'
        ]);

        if(!$request->company_id){
            $data = Department::all();
        }else{
            $data = Department::where('company_id',$request->company_id)->get();
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

        $create = Department::create($request->all());

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
    public function show(Department $cvSayaDepartments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CvSayaDepartments  $cvSayaDepartments
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $cvSayaDepartments)
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
    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'string|nullable',
            'company_id' => 'string|nullable',
        ]);
        $find = Department::where('id',$id)->firstOrFail();
        $find->update($request->all());

        return $this->showOne($find);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CvSayaDepartments  $cvSayaDepartments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {

        $find = Department::where('id',$request->id)->first();
        if(!$find){
            return $this->errorResponse('id not found',404,40401);
        }else{
            $usingLevel =  Position::where('level_id',$request->id)->count();
            if($usingLevel){
                return $this->errorResponse('Departement still been use',409,40901);
            }
        }
        $find->delete();

        return $this->showOne($find);
    }
}
