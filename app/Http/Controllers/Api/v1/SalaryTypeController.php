<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\SalaryType;
use App\Traits\ApiResponser;

class SalaryTypeController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $salaryTypes = SalaryType::all();

        $data = $salaryTypes->map(function ($item) {
            return $item->toArrayDefault();
        });

    return $this->showAll($data);
    }

    public function show($id)
    {
        $salaryType = SalaryType::findOrFail($id);

        return $this->showOne($salaryType->toArrayDefault());
    }

    public function store(Request $request)
    {
        $rule = [
            'name' => 'required|string',
            'company_id' => 'required|exists:companies,id',
        ];

        $request->validate($rule);

        $salaryType = SalaryType::create($request->all());

        return $this->showOne($salaryType->toArrayDefault());
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

        return $this->showOne($salaryType->toArrayDefault());
    }
}
