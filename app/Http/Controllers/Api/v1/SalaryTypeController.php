<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalaryTypeResource;
use App\Models\SalaryType;
use App\Traits\ApiResponser;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SalaryTypeController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $salaryTypes = QueryBuilder::for(SalaryType::class)
            ->allowedFilters([
                AllowedFilter::exact('company_id'),
                AllowedFilter::exact('is_adhocable')
            ])->get();

        return $this->showAll(collect(SalaryTypeResource::collection($salaryTypes)));
    }

    public function show($id)
    {
        $salaryType = SalaryType::findOrFail($id);

        return $this->showOne(new SalaryTypeResource(($salaryType)));
    }

    public function store(Request $request)
    {
        $rule = [
            'name' => 'required|string',
            'company_id' => 'required|exists:companies,id',
        ];

        $request->validate($rule);

        $salaryType = SalaryType::create($request->all());

        return $this->showOne(new SalaryTypeResource(($salaryType)));
    }

    public function update(Request $request, $id)
    {
        $rule = [
            'name' => 'required|string',
            'company_id' => 'required|exists:companies,id',
        ];

        $request->validate($rule);

        $salaryType = SalaryType::findOrFail($id);
        $salaryType->update($request->all());
        $salaryType->refresh();

        return $this->showOne(new SalaryTypeResource(($salaryType)));
    }
}
