<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\EmployeeType;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Candidate;
use App\Models\EmployeeSalaryType;
use App\Models\SalaryType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class EmployeeController extends Controller
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
            'page' => 'nullable|numeric|gt:0',
            'page_size' => 'nullable|numeric|gt:0',
            'position_id' => 'nullable|exists:positions,id',
            'company_id' => 'nullable|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'level_id' => 'nullable|exists:levels,id',
            'keyword' => 'nullable|string',
        ]);

        $page = $request->page ? $request->page  : 1;
        $company = $request->company_id;
        $position = $request->position_id;
        $department = $request->department_id;
        $level = $request->level_id;
        $keyword = $request->keyword;

        $pageSize = $request->page_size ? $request->page_size : 10;

        $employees = Employee::where(function ($query) use ($company, $position, $department, $level, $keyword) {
            if ($company) {
                $query->whereHas('company', function ($secondQuery) use ($company) {
                    $secondQuery->where('company_id', $company);
                });
            }
            if ($position) {
                $query->where('position_id', $position);
            }
            if ($department) {
                $query->whereHas('department', function ($secondQuery) use ($department) {
                    $secondQuery->where('department_id', $department);
                });
            }
            if ($level) {
                $query->whereHas('level', function ($secondQuery) use ($level) {
                    $secondQuery->where('level_id', $level);
                });
            }
            if ($keyword) {
                $query->whereHas('profileDetail', function ($secondQuery) use ($keyword) {
                    $secondQuery->where('first_name', 'like', '%' . $keyword . '%')->orWhere('last_name', 'like', '%' . $keyword . '%');
                });
            }
        })->paginate(
            $pageSize,
            ['*'],
            'page',
            $page
        );
        $data = $employees->map(function ($item) {
            return $item->toArrayEmployee();
        });

        return $this->showPaginate('employees', collect($data), collect($employees));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge([
            'type' => Str::lower($request->type)
        ]);
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'position_id' => 'required|exists:positions,id',
            'company_id' => 'required|exists:companies,id',
            'joined_at' => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'type' => ['required', Rule::in([EmployeeType::Daily->value, EmployeeType::Monthly->value])],
            'salary_types.*.id' => 'required|numeric|exists:salary_types,id',
            'salary_types.*.amount' => 'required|numeric|gt:0'
        ]);

        $position = Position::findOrFail($request->position_id);

        if ($position->remaining_slot === 0) return $this->errorResponse('There\'s no remaining slot for the specified position.', 422, 42201);

        $candidate = Candidate::where('id', $request->candidate_id)->whereIn('status', [Candidate::STANDBY, Candidate::CONSIDER, Candidate::ACCEPTED])->firstOrFail();
        $employees = Employee::where('user_id', $candidate->user_id)
            ->get();

        if ($employees->first(function ($employee) use ($request) {
            return $employee->position_id === $request->position_id;
        })) return $this->errorResponse('Candidate is already assigned to the specified position.', 422, 42202);

        $salaryTypesId = Arr::pluck($request->salary_types, 'id');
        $salaryTypes = SalaryType::where('company_id', $request->company_id)->whereIn('id', $salaryTypesId)->get();
        if ($salaryTypes->count() !== count($request->salary_types)) return $this->errorResponse('One (or more) salary type(s) doesn\'t exist.', 422, 42203);

        $candidate->status = Candidate::ACCEPTED;
        $position->remaining_slot -= 1;

        $response = DB::transaction(function () use ($request, $employees, $candidate, $position) {
            $candidate->save();
            $position->save();

            $employee = Employee::create([
                'user_id' => $candidate->user_id,
                'position_id' => $request->position_id,
                'type' => $request->type,
                'is_default' => $employees->isEmpty(),
                'joined_at' => $request->joined_at
            ]);

            $employeesSalaryTypes = [];

            foreach ($request->salary_types as $salaryType) {
                $employeesSalaryTypes[] = [
                    'employee_id' => $employee->id,
                    'salary_type_id' => $salaryType['id'],
                    'amount' => $salaryType['amount'],
                ];
            }

            EmployeeSalaryType::insert($employeesSalaryTypes);

            return $this->showOne($employee->toArrayEmployee());
        });

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeDetails  $employeeDetails
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::findOrFail($id);

        return $this->showOne($employee->toArrayEmployee());
    }

    public function showSalaryOnly($id)
    {
        $employee = Employee::findOrFail($id);

        return $this->showOne($employee->typeOfSalary());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeDetails  $employeeDetails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rule = [
            'position_id' => 'required|exists:positions,id',
            'employment_type_id' => 'required|exists:employment_types,id',
            'joined_at' => 'required|date_format:Y-m-d\TH:i:s.v\Z|nullable',
        ];

        $request->validate($rule);

        $employee = Employee::findOrFail($id);

        $employee =  $employee->fill($request->all());
        if ($employee->isDirty()) {
            $employee->update($request->all());
        }
        $employee->restore();

        return $this->showOne($employee->toArrayEmployee());
    }

    public function updateSalary(Request $request, $id)
    {
        $additionalRuple = [
            'id' => 'required|exists:salary_types,id',
            'amount' => 'required|integer',
        ];
        $request->validate([
            'salary_types' => 'required|array',
        ]);
        $salaryTypes = $request->salary_types;
        $employee = Employee::findOrFail($id);
        $salaryTypesId = [];
        foreach ($salaryTypes as $salaryType) {
            $salaryTypesId[] = $salaryType['id'];
            SalaryType::findOrFail($salaryType['id']);
            $employeeSalary = EmployeeSalaryType::where('salary_type_id', $salaryType['id'])
                ->where('employee_id', $employee->id)
                ->first();
            if ($employeeSalary) {
                $employeeSalary->amount = $salaryType['amount'];
                $employeeSalary->save();
            }
        }
        $this->updateDeleteSalaries($salaryTypesId, $employee, $salaryTypes);

        $employee = $employee->refresh();

        return $this->showOne($employee->typeOfSalary());
    }

    public static function updateDeleteSalaries(array $newSalaryTypesId, Employee $employee, array $salaryTypes)
    {
        $salaryTypes = collect($salaryTypes);
        $oldSalaryTypesId = EmployeeSalaryType::where('employee_id', $employee->id)->pluck('id');
        $deletes = array_diff($oldSalaryTypesId->toArray(), $newSalaryTypesId);
        EmployeeSalaryType::whereIn('salary_type_id', $deletes)->where('employee_id', $employee->id)->delete();
        $adds = array_diff($newSalaryTypesId, $oldSalaryTypesId->toArray());
        foreach ($adds as $add) {
            $salaryType = $salaryTypes->firstWhere('id', $add);
            $newSalaryTypesId = EmployeeSalaryType::where('employee_id', $employee->id)
                ->withTrashed()
                ->where('salary_type_id', $add)
                ->first();
            if ($newSalaryTypesId) {
                $newSalaryTypesId->restore();
                $newSalaryTypesId->amount = $salaryType['amount'];
                $newSalaryTypesId->save();
            } else {
                EmployeeSalaryType::create([
                    'employee_id' => $employee->id,
                    'salary_type_id' => $salaryType['id'],
                    'amount' => $salaryType['amount'],
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeDetails  $employeeDetails
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        $employee->delete();

        return $this->showOne(null);
    }
}