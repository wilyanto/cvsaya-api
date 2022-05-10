<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\EmployeeType;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Candidate;
use App\Models\EmployeeOneTimeShift;
use App\Models\EmployeeRecurringShift;
use App\Models\EmployeeSalaryType;
use App\Models\SalaryType;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;

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

        $employees = Employee::where(function ($query) use ($request) {
            if ($request->company_id)
                $query->whereHas('company', function ($query) use ($request) {
                    $query->where('company_id', $request->company_id);
                });

            if ($request->position_id)
                $query->where('position_id', $request->position_id);

            if ($request->department_id)
                $query->whereHas('department', function ($query) use ($request) {
                    $query->where('department_id', $request->department_id);
                });

            if ($request->level_id)
                $query->whereHas('level', function ($query) use ($request) {
                    $query->where('level_id', $request->level_id);
                });

            if ($request->keyword)
                $query->whereHas('profileDetail', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%' . $request->keyword . '%')
                        ->orWhere('last_name', 'like', '%' . $request->keyword . '%');
                });
        })
            ->with('position', 'profileDetail')
            ->paginate($request->input('page_size', 10));

        return $this->showPagination('employees', $employees);
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
            'joined_at' => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'type' => ['required', new Enum(EmployeeType::class)],
            'salary_types' => 'required|array',
            'salary_types.*.id' => 'required|numeric|exists:salary_types,id',
            'salary_types.*.amount' => 'required|numeric|gt:0',
            'one_time_shifts' => 'required|array',
            'one_time_shifts.*.shift_id' => 'required|exists:shifts,id',
            'one_time_shifts.*.date' => 'required|date_format:Y-m-d',
            'recurring_shifts' => 'required|array',
            'recurring_shifts.*.shift_id' => 'required|exists:shifts,id',
            'recurring_shifts.*.days' => 'required|array',
            'recurring_shifts.*.days.*' => 'required|numeric|between:0,6'
        ]);

        $position = Position::select('id', 'company_id', 'remaining_slot')->findOrFail($request->position_id);

        if ($position->remaining_slot === 0) return $this->errorResponse('There\'s no remaining slot for the specified position.', 422, 42201);

        $candidate = Candidate::select('id', 'user_id')->where('id', $request->candidate_id)->whereIn('status', [Candidate::STANDBY, Candidate::CONSIDER, Candidate::ACCEPTED])->firstOrFail();
        $employees = Employee::where('user_id', $candidate->user_id)->get(['id', 'position_id']);

        if ($employees->first(function ($employee) use ($request) {
            return $employee->position_id === $request->position_id;
        })) return $this->errorResponse('Candidate is already assigned to the specified position.', 422, 42202);

        $salaryTypeIds = Arr::pluck($request->salary_types, 'id');
        $salaryTypes = SalaryType::where('company_id', $position->company_id)->whereIn('id', $salaryTypeIds)->get('id');
        if ($salaryTypes->count() !== count($request->salary_types)) return $this->errorResponse('One (or more) salary type(s) doesn\'t exist.', 422, 42203);

        $oneTimeShiftIds = Arr::pluck($request->one_time_shifts, 'shift_id');
        $oneTimeShifts = Shift::where('company_id', $position->company_id)->whereIn('id', $oneTimeShiftIds)->get('id');
        if ($oneTimeShifts->count() !== count($request->one_time_shifts)) return $this->errorResponse('One (or more) one time shift(s) doesn\'t exist.', 422, 42204);

        $recurringShiftIds = Arr::pluck($request->recurring_shifts, 'shift_id');
        $recurringShifts = Shift::where('company_id', $position->company_id)->whereIn('id', $recurringShiftIds)->get('id');
        if ($recurringShifts->count() !== count($request->recurring_shifts)) return $this->errorResponse('One (or more) recurring shift(s) doesn\'t exist.', 422, 42205);

        $response = DB::transaction(function () use ($request, $employees, $candidate, $position) {
            $candidate->update(['status' => Candidate::ACCEPTED]);
            $position->decrement('remaining_slot');

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
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            EmployeeSalaryType::insert($employeesSalaryTypes);

            $employeeOneTimeShifts = [];

            foreach ($request->one_time_shifts as $employeeOneTimeShift) {
                $employeeOneTimeShifts[] = [
                    'employee_id' => $employee->id,
                    'shift_id' => $employeeOneTimeShift['shift_id'],
                    'date' => $employeeOneTimeShift['date'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            EmployeeOneTimeShift::insert($employeeOneTimeShifts);

            $employeeRecurringShifts = [];

            foreach ($request->recurring_shifts as $employeeRecurringShift) {
                foreach ($employeeRecurringShift['days'] as $day) {
                    $employeeRecurringShifts[] = [
                        'employee_id' => $employee->id,
                        'shift_id' => $employeeRecurringShift['shift_id'],
                        'day' => $day,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            EmployeeRecurringShift::insert($employeeRecurringShifts);

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

        return $this->showOne(null, 204);
    }
}
