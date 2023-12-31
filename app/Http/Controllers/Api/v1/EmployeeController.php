<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\CompanySalaryAmountTypeEnum;
use App\Enums\EmployeeType;
use App\Enums\SalaryTypeEnum;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Position;
use App\Models\Candidate;
use App\Models\EmployeeOneTimeShift;
use App\Models\EmployeeRecurringShift;
use App\Models\EmployeeSalaryType;
use App\Models\SalaryType;
use App\Models\Shift;
use App\Services\EmployeeSalaryTypeService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Enum\Laravel\Rules\EnumRule;

class EmployeeController extends Controller
{
    use ApiResponser;

    protected $employeeSalaryTypeService;

    public function __construct(EmployeeSalaryTypeService $employeeSalaryTypeService)
    {
        $this->employeeSalaryTypeService = $employeeSalaryTypeService;
    }

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
                $query->whereHas('candidate', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->keyword . '%');
                });
        })
            ->with('position', 'candidate')
            ->paginate($request->input('page_size', 10));

        return $this->showPaginate('employees', collect(EmployeeResource::collection($employees)), collect(($employees)));
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
            'type' => ['required', new EnumRule(EmployeeType::class)],
            'is_attendance_required' => 'required|boolean',
            'employee_salary_types' => 'required|array',
            'employee_salary_types.*.company_salary_type_id' => 'required|numeric|exists:company_salary_types,id',
            'employee_salary_types.*.amount' => 'required|numeric|gt:0',
            'employee_salary_types.*.amount_type' => ['required', new EnumRule(CompanySalaryAmountTypeEnum::class)],
            'one_time_shifts' => 'present|array',
            'one_time_shifts.*.shift_id' => 'required|exists:shifts,id',
            'one_time_shifts.*.date' => 'required|date_format:Y-m-d',
            'recurring_shifts' => 'present|array',
            'recurring_shifts.*.shift_id' => 'required|exists:shifts,id',
            'recurring_shifts.*.days' => 'required|array',
            'recurring_shifts.*.days.*' => 'required|numeric|between:0,6'
        ]);

        $position = Position::select('id', 'company_id', 'remaining_slot')->findOrFail($request->position_id);
        if ($position->remaining_slot === 0) return $this->errorResponse('There\'s no remaining slot for the specified position.', 422, 42201);

        $candidate = Candidate::select('id', 'user_id')->where('id', $request->candidate_id)->firstOrFail();
        $employees = Employee::where('candidate_id', $candidate->id)->get(['id', 'position_id']);

        if ($employees->first(function ($employee) use ($request) {
            return $employee->position_id === $request->position_id;
        })) return $this->errorResponse('Candidate is already assigned to the specified position.', 422, 42202);

        if ($employees->first(function ($employee) use ($position) {
            return $employee->company->id === $position->company_id;
        })) return $this->errorResponse('Candidate is already assigned to the company.', 422, 42203);

        $response = DB::transaction(function () use ($request, $employees, $candidate, $position) {
            $candidate->update(['status' => Candidate::ACCEPTED]);
            $position->decrement('remaining_slot');

            $employee = Employee::create([
                'candidate_id' => $candidate->id,
                'position_id' => $request->position_id,
                'type' => $request->type,
                'is_default' => $employees->isEmpty(),
                'joined_at' => $request->joined_at,
                'is_attendance_required' => $request->is_attendance_required
            ]);

            $employeeSalaryTypes = [];
            foreach ($request->employee_salary_types as $employeeSalaryType) {
                $employeeSalaryTypes[$employeeSalaryType['company_salary_type_id']] =  [
                    'amount' => $employeeSalaryType['amount'],
                    'amount_type' => $employeeSalaryType['amount_type'],
                ];
            }
            $employee->companySalaryTypes()->sync($employeeSalaryTypes);

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

        return $this->showOne(new EmployeeResource($employee));
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
        $request->validate([
            'employee_salary_types' => 'required|array',
            'employee_salary_types.*.company_salary_type_id' => 'required|numeric|exists:company_salary_types,id',
            'employee_salary_types.*.amount' => 'required|numeric|gt:0',
            'employee_salary_types.*.amount_type' => ['required', new EnumRule(CompanySalaryAmountTypeEnum::class)],
        ]);

        $employee = Employee::findOrFail($id);

        $employeeSalaryTypes = [];
        foreach ($request->employee_salary_types as $employeeSalaryType) {
            $employeeSalaryTypes[$employeeSalaryType['company_salary_type_id']] =  [
                'amount' => $employeeSalaryType['amount'],
                'amount_type' => $employeeSalaryType['amount_type'],
            ];
        }
        $employee->companySalaryTypes()->sync($employeeSalaryTypes);

        return $this->showOne(new EmployeeResource($employee));
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

    public function getEmployeesByCompany(Request $request, $companyId)
    {

        $page = $request->page ? $request->page : 1;
        $pageSize = $request->page_size ? $request->page_size : 10;
        $name = $request->keyword;

        $employees = Employee::whereHas('position', function ($positionQuery) use ($companyId, $name) {
            $positionQuery->whereHas('company', function ($companyQuery) use ($companyId) {
                $companyQuery->where('id', $companyId);
            });
        })->whereHas('candidate', function ($candidateQuery) use ($name) {
            $candidateQuery->where('name', 'like', '%' . $name . '%');
        })
            ->with([
                'candidate:id,name',
                'position:id,name'
            ])
            ->paginate(
                $pageSize,
                ['id', 'candidate_id', 'position_id'],
                'page',
                $page
            );

        return $this->showPagination('employees', $employees);
    }

    public function indexForReport(Request $request, $companyId)
    {
        // select employee based on date range
        // for attend, check from joined at
        // for resign, check from deleted_at
        $startedAt = $request->started_at;
        $endedAt = $request->ended_at;
        $companyId = $companyId;

        $newEmployeeCount = Employee::whereBetween('joined_at', [$startedAt, $endedAt])
            ->whereNull('deleted_at')
            ->count();
        $resignedEmployeeCount = Employee::whereBetween('deleted_at', [$startedAt, $endedAt])->count();
        $totalEmployeeCount = Employee::count();

        $data = [
            'new_employee_count' => $newEmployeeCount,
            'resigned_employee_count' => $resignedEmployeeCount,
            'total_employee_count' => $totalEmployeeCount
        ];

        return $this->showOne($data);
    }
}
