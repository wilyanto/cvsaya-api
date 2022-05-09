<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CompanyController extends Controller
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
            'keyword' => [
                'string',
            ],
            'page' => 'nullable|numeric|gt:0',
            'page_size' => 'nullable|numeric|gt:0'
        ]);

        $page = $request->page ? $request->page  : 1;
        $pageSize = $request->page_size ? $request->page_size : 10;
        $keyword = $request->keyword;
        $companies = Company::where(function ($query) use ($keyword) {
            if ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }
        })->paginate(
            $pageSize,
            ['*'],
            'page',
            $page
        );

        $data = $companies->map(function ($item) {
            return $item;
        });

        return $this->showPagination('companies', $companies);
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
            'id' => 'string|unique:companies,id',
            'name' => 'string',
        ]);

        $create = Company::create($request->all());

        return $this->showOne($create);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $companies
     * @return \Illuminate\Http\Response
     */
    public function show(Company $companies)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $companies
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $companies)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $companies
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'string',
        ]);
        $company = Company::findOrFail($id);
        $company->name = $request->name;
        $company->save();

        return $this->showOne($company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $companies
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $companies)
    {
        //
    }
}
