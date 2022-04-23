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
    public function index(Request $request)
    {
        $request->validate([
            'companies' => [
                'array',
                'nullable'
            ],
            'departments' => [
                'array',
                'nullable'
            ],
            'levels' => [
                'array',
                'nullable'
            ],

            'keyword' => [
                'string',
            ],
            'page' => 'required|numeric|gt:0',
            'page_size' => 'required|numeric|gt:0'
        ]);
        $page = $request->page ? $request->page  : 1;
        $pageSize = $request->page_size ? $request->page_size : 10;
        $companies = $request->companies;
        $departments = $request->departments;
        $keyword = $request->keyword;
        $levels = $request->levels;
        $positions = Position::where(function ($query) use ($companies, $departments, $levels, $keyword) {
            if ($companies) {
                $query->whereIn('company_id', $companies);
            }
            if ($departments) {
                $query->whereIn('department_id', $departments);
            }
            if ($levels) {
                $query->whereIn('level_id', $levels);
            }
            if ($keyword) {
                $query->where('name', 'like', $keyword);
            }
        })->paginate(
            $pageSize,
            ['*'],
            'page',
            $page
        );
        $data = $positions->map(function ($item) {
            return $item->toArrayDefault();
        });
        return $this->showPaginate('positions', collect($data), collect($positions));
    }

    public function show($id)
    {
        $getPosition = Position::findOrFail($id);

        return $this->showOne($getPosition->toArrayDefault());
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
        $request->validate([
            'name' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'level_id' => 'required|exists:levels,id',
            'parent_id' => 'nullable|exists:positions,parent_id',
            'remaining_slot' => 'nullable|integer',
            'company_id' => 'nullable|exists:companies,id',
            'min_salary' => 'nullable|integer',
            'max_salary' => 'nullable|integer',
        ]);

        $create = Position::create($request->all());

        return $this->showOne($create->toArrayDefault());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CvSayaPositions  $cvSayaPositions
     * @return \Illuminate\Http\Response
     */
    // public function show(Request $request)
    // {
    //     $user =  auth()->user();

    //     $list = Position::where('parent_id', null)->get();
    //     //    dd($list);
    //     $data = [];
    //     foreach ($list as $item => $object) {
    //         $data[] = [
    //             'id' => $object->id,
    //             'name' => $object->name,
    //             'department_id' => $object->departments,
    //             'level' => $object->levels,
    //             'company_id' => $object->departments->company_id,
    //             'children' => $object->getAllChildren(),
    //         ];
    //     }
    //     $data = collect($data);

    //     return $this->showAll($data);
    // }

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
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'level_id' => 'required|exists:levels,id',
            'parent_id' => 'nullable|exists:positions,parent_id',
            'remaining_slot' => 'nullable|integer',
            'company_id' => 'nullable|exists:companies,id',
            'min_salary' => 'nullable|integer',
            'max_salary' => 'nullable|integer',
        ]);
        $request = $request->all();
        $update = Position::findOrFail($id);
        $update->update([
            $request
        ]);
        $update = $update->refresh();
        return $this->showOne($update);
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
