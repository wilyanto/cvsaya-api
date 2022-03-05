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
    public function index()
    {
        $user = auth()->user();

        $getCompanyData = Company::all();

        return $this->showAll($getCompanyData);
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
