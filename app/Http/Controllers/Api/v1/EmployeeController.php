<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Candidate;
use App\Models\EmployeeSalaryType;
use App\Models\SalaryType;

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
            'page' => 'required|numeric|gt:0',
            'page_size' => 'required|numeric|gt:0',
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
                $query->whereHas('profileDetail',function($secondQuery)use ($keyword){
                    $secondQuery->where('first_name','like','%'.$keyword.'%')->orWhere('last_name','like','%'.$keyword.'%');
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rule = [
            'candidate_id' => 'required|exists:candidates,id',
            'position_id' => 'required|exists:positions,id',
            'employment_type_id' => 'required|exists:employment_types,id',
            'joined_at' => 'required|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'salary_types' => 'required|array',
        ];

        $request->validate($rule);

        $candidate = Candidate::where('id', $request->candidate_id)->where('status', 5)->firstOrFail();
        $employee = Employee::where('user_id', $candidate->user_id)
            ->where('position_id', $request->position_id)
            ->first();
        if (!$employee) {
            $position = Position::findOrFail($request->position_id);
            if ($position->remaining_slot > 0) {
                $employeeArray = $request->all();
                unset($employeeArray['candidate_id']);
                unset($employeeArray['salary_types']);
                $employeeArray['user_id'] = $candidate->user_id;

                $employee = Employee::create($employeeArray);
                $newSalaryTypes = [];
                $salaryTypes = $request->salary_types;
                foreach ($salaryTypes as $salaryType) {
                    $salaryType = collect($salaryType);
                    SalaryType::findOrFail($salaryType['id']);
                    $newSalaryTypeIds[] = $salaryType['id'];
                    $newSalaryTypes[] = [
                        'employee_id' => $employee->id,
                        'salary_type_id' => $salaryType['id'],
                        'amount' => $salaryType['amount'],
                    ];
                }
                $candidate->status = Candidate::ACCEPTED;
                $candidate->save();
                EmployeeSalaryType::insert($newSalaryTypes);
                $position->remaining_slot -= 1;
                $position->save();
                return $this->showOne($employee->toArrayEmployee());
            } else {
                return $this->errorResponse('The selected Position is full, please select another Position', 422, 42201);
            }
        } else {
            return $this->errorResponse('Candidate already pick this Position, please choose another one', 422, 42202);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeDetails  $employeeDetails
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employeeDetail = Employee::findOrFail($id);

        return $this->showOne($employeeDetail->toArrayEmployee());
    }

    public function showSalaryOnly($id)
    {
        $employeeDetail = Employee::findOrFail($id);

        return $this->showOne($employeeDetail->typeOfSalary());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeDetails  $employeeDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employees)
    {
        //
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
