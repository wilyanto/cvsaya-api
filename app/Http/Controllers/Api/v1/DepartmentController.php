<?php


namespace App\Http\Controllers\Api\v1;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;

class DepartmentController extends Controller
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
            'keyword' => [
                'string',
            ],
            'page' => 'nullable|numeric|gt:0',
            'page_size' => 'nullable|numeric|gt:0'
        ]);
        $page = $request->page ? $request->page  : 1;
        $pageSize = $request->page_size ? $request->page_size : 10;
        $companies = $request->companies;
        $keyword = $request->keyword;
        $departments = Department::where(function ($query) use ($companies, $keyword) {
            if ($companies) {
                $query->whereIn('company_id', $companies);
            }
            if ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }
        })->paginate(
            $pageSize,
            ['*'],
            'page',
            $page
        );
        $data = $departments->map(function ($item) {
            return $item->toArrayIndex();
        });
        return $this->showPaginate('departments', collect($data), collect($departments));
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

        return $this->showOne($create->toArrayIndex());
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
    public function show($id)
    {
        $data = Department::findOrFail($id);
        return $this->showOne($data->toArrayIndex());
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string|nullable',
            'company_id' => 'string|nullable',
        ]);
        $find = Department::findOrFail($id);
        $find->update($request->all());

        return $this->showOne($find->toArrayIndex());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CvSayaDepartments  $cvSayaDepartments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        $request->validate([
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $find = Department::findOrFail($id);
        if ($request->department_id == $id) {
            return $this->errorResponse('department_id and id cannot be same', 422, 42201);
        }
        if ($request->department_id) {
            Position::where('department_id', $id)->update([
                'department_id' => $request->department_id,
            ]);
        } else {
            Position::where('department_id', $id)->update([
                'department_id' => null,
            ]);
        }
        $find->delete();

        return $this->showOne(null);
    }
}
