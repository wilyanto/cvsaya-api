<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeBankAccountStoreRequest;
use App\Http\Requests\EmployeeBankAccountUpdateRequest;
use App\Http\Resources\EmployeeBankAccountResource;
use App\Services\EmployeeBankAccountService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class EmployeeBankAccountController extends Controller
{
    use ApiResponser;

    protected $employeeBankAccountService;

    public function __construct(EmployeeBankAccountService $employeeBankAccountService)
    {
        $this->employeeBankAccountService = $employeeBankAccountService;
    }

    public function index()
    {
        $employeeBankAccounts = $this->employeeBankAccountService->getAll();

        return $this->showAll(collect(EmployeeBankAccountResource::collection($employeeBankAccounts)));
    }

    public function store(EmployeeBankAccountStoreRequest $request)
    {
        $employeeBankAccount = $this->employeeBankAccountService->createEmployeeBankAccount($request);

        return $this->showOne(new EmployeeBankAccountResource($employeeBankAccount));
    }

    public function show($id)
    {
        $employeeBankAccount = $this->employeeBankAccountService->getById($id);

        return $this->showOne(new EmployeeBankAccountResource($employeeBankAccount));
    }

    public function update(EmployeeBankAccountUpdateRequest $request, $id)
    {
        $employeeBankAccount = $this->employeeBankAccountService->updateEmployeeBankAccount($request, $id);

        return $this->showOne(new EmployeeBankAccountResource($employeeBankAccount));
    }

    public function destroy($id)
    {
        $message = $this->employeeBankAccountService->deleteById($id);

        return response()->json(null, 204);
    }

    public function showByEmployeeId(Request $request)
    {
        $request->validate(['employee_id' => $request->employee_id]);
        $employeeId = $request->employee_id;
        $employeeBankAccount = $this->employeeBankAccountService->getByEmployeeId($employeeId);

        return $this->showOne(new EmployeeBankAccountResource($employeeBankAccount));
    }
}
