<?php

namespace App\Services;

use App\Enums\EmployeeType;
use App\Enums\PayslipStatusEnum;
use App\Enums\SalaryTypeEnum;
use App\Http\Common\Filter\FilterPayslipSearch;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeAdHoc;
use App\Models\EmployeePayslip;
use App\Models\EmployeePayslipAdhoc;
use App\Models\EmployeePayslipAllowance;
use App\Models\EmployeePayslipDeduction;
use App\Models\EmployeePayslipDetail;
use App\Models\PayrollPeriod;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PayslipService
{
    protected $employeeAdHocService;

    public function __construct(EmployeeAdHocService $employeeAdHocService)
    {
        $this->employeeAdHocService = $employeeAdHocService;
    }

    public function getAll($pageSize)
    {
        $employeePayslip = QueryBuilder::for(EmployeePayslip::class)
            ->allowedIncludes([
                'employee',
                'payrollPeriod',
                'payslipDetails.companySalaryType.salaryType',
                'payslipAdHocs.companySalaryType.salaryType'
            ])
            ->allowedFilters([
                'payroll_period_id',
                AllowedFilter::custom('search', new FilterPayslipSearch),
            ])
            ->paginate($pageSize);

        return $employeePayslip;
    }


    public function getById($id)
    {
        $query = EmployeePayslip::where('id', $id);
        $employeePayslip = QueryBuilder::for($query)
            ->allowedIncludes([
                'employee',
                'payrollPeriod',
                'payslipDetails.companySalaryType.salaryType',
                'payslipAdHocs.companySalaryType.salaryType'
            ])
            ->firstOrFail();

        return $employeePayslip;
    }

    public function getByEmployeeId($employeeId, $pageSize)
    {
        $query = EmployeePayslip::where('employee_id', $employeeId);
        $employeePayslips = QueryBuilder::for($query)
            ->allowedIncludes([
                'employee',
                'payrollPeriod',
                'payslipDetails.companySalaryType.salaryType',
                'payslipAdHocs.companySalaryType.salaryType'
            ])
            ->latest()
            ->paginate($pageSize);

        return $employeePayslips;
    }

    public function createPayslip($data)
    {
        $payslip = EmployeePayslip::create([
            'employee_id' => $data->employee_id,
            'payroll_period_id' => $data->payroll_period_id,
            'status' => PayslipStatusEnum::unpaid(),
        ]);

        $payslipDetails = $data->payslip_details;
        foreach ($payslipDetails as $payslipDetail) {
            $payslipDetail = (object) $payslipDetail;
            EmployeePayslipDetail::create([
                'employee_payslip_id' => $payslip->id,
                'company_salary_type_id' => $payslipDetail->company_salary_type_id,
                'name' => $payslipDetail->name,
                'amount' => $payslipDetail->amount,
                'note' => $payslipDetail->note
            ]);
        }

        $employeePayslipAdHocs = $data->employee_payslip_ad_hocs;
        if ($employeePayslipAdHocs) {
            foreach ($employeePayslipAdHocs as $employeePayslipAdHoc) {
                $employeePayslipAdHoc = (object) $employeePayslipAdHoc;
                EmployeePayslipAdHoc::create([
                    'employee_payslip_id' => $payslip->id,
                    'company_salary_type_id' => $employeePayslipAdHoc->company_salary_type_id,
                    'name' => $employeePayslipAdHoc->name,
                    'amount' => $employeePayslipAdHoc->amount,
                    'note' => $employeePayslipAdHoc->note,
                    'date' =>  $employeePayslipAdHoc->date
                ]);
            }
        }

        return $payslip;
    }

    public function updatePayslip($data, $id)
    {
        $payslip = $this->getById($id);
        $payslip->payslipDetails()->delete();
        $payslip->payslipAdHocs()->delete();

        $payslip->update([
            'employee_id' => $data->employee_id,
            'payroll_period_id' => $data->payroll_period_id,
        ]);

        $payslipDetails = $data->payslip_details;
        foreach ($payslipDetails as $payslipDetail) {
            $payslipDetail = (object) $payslipDetail;
            EmployeePayslipDetail::create([
                'employee_payslip_id' => $payslip->id,
                'company_salary_type_id' => $payslipDetail->company_salary_type_id,
                'name' => $payslipDetail->name,
                'amount' => $payslipDetail->amount,
                'note' => $payslipDetail->note
            ]);
        }

        $employeePayslipAdHocs = $data->employee_payslip_ad_hocs;
        if ($employeePayslipAdHocs) {
            foreach ($employeePayslipAdHocs as $employeePayslipAdHoc) {
                $employeePayslipAdHoc = (object) $employeePayslipAdHoc;
                EmployeePayslipAdHoc::create([
                    'employee_payslip_id' => $payslip->id,
                    'company_salary_type_id' => $employeePayslipAdHoc->company_salary_type_id,
                    'name' => $employeePayslipAdHoc->name,
                    'amount' => $employeePayslipAdHoc->amount,
                    'note' => $employeePayslipAdHoc->note,
                    'date' =>  $employeePayslipAdHoc->date
                ]);
            }
        }

        return $payslip;
    }

    public function generatePayslip($id, $generatedBy)
    {
        $payslip = $this->getById($id);

        $payslip->update([
            'generated_at' => now(),
            'generated_by' => $generatedBy
        ]);

        return $payslip;
    }

    public function payPayslip($id, $paidBy)
    {
        $payslip = $this->getById($id);

        $payslip->update([
            'status' => PayslipStatusEnum::paid(),
            'paid_at' => now(),
            'paid_by' => $paidBy
        ]);

        return $payslip;
    }

    public function generateAndPayPayslip($id, $employeeId)
    {
        $payslip = $this->getById($id);

        $payslip->update([
            'status' => PayslipStatusEnum::paid(),
            'paid_at' => now(),
            'paid_by' => $employeeId,
            'generated_at' => now(),
            'generated_by' => $employeeId
        ]);

        return $payslip;
    }

    public function generatePayslips($payrollPeriodId)
    {
        $payrollPeriod = PayrollPeriod::findOrFail($payrollPeriodId);
        $company = Company::findOrFail($payrollPeriod->company_id);
        $employees = $company->employees;

        DB::transaction(function () use ($employees, $payrollPeriod) {
            foreach ($employees as $employee) {
                $startDate = $payrollPeriod->started_at;
                $endDate = $payrollPeriod->ended_at;
                $actualWorkDayCount = $payrollPeriod->working_day_count;

                $isDailyEmployee = $employee->type == EmployeeType::daily();
                $isMonthlyEmployee = $employee->type == EmployeeType::monthly() && !$employee->is_attendance_required;
                $isMonthlyEmployeeAndAttendanceRequired = $employee->type == EmployeeType::monthly() && $employee->is_attendance_required;
                $employeeWorkDayCount = $employee->getAttendanceCount($startDate, $endDate);

                $payslip = EmployeePayslip::create([
                    'employee_id' => $employee->id,
                    'status' => PayslipStatusEnum::unpaid(),
                    'payroll_period_id' => $payrollPeriod->id,
                ]);

                $allowanceEmployeeSalaryTypes = $employee->getAllowanceSalaryTypes();
                foreach ($allowanceEmployeeSalaryTypes as $allowanceEmployeeSalaryType) {
                    // gaji pokok
                    $totalAmount = 0;
                    // dd($allowanceEmployeeSalaryType->toJson(JSON_PRETTY_PRINT));
                    if ($allowanceEmployeeSalaryType->companySalaryType->salaryType->code == "A01") {
                        // TODO: codes
                        if ($isDailyEmployee) {
                            $totalAmount = $allowanceEmployeeSalaryType->amount * $employeeWorkDayCount;
                        } else if ($isMonthlyEmployeeAndAttendanceRequired) {
                            $totalAmount = ($allowanceEmployeeSalaryType->amount / $actualWorkDayCount) * $employeeWorkDayCount;
                        } else {
                            $totalAmount = $allowanceEmployeeSalaryType->amount;
                        }
                    }

                    // tunjangan transport
                    if ($allowanceEmployeeSalaryType->companySalaryType->salaryType->code == "A02") {
                        if ($isDailyEmployee) {
                            $totalAmount = $allowanceEmployeeSalaryType->amount * $employeeWorkDayCount;
                        } else if ($isMonthlyEmployeeAndAttendanceRequired) {
                            $totalAmount = ($allowanceEmployeeSalaryType->amount / $actualWorkDayCount) * $employeeWorkDayCount;
                        } else {
                            $totalAmount = $allowanceEmployeeSalaryType->amount;
                        }
                    }

                    // tunjangan makan
                    if ($allowanceEmployeeSalaryType->companySalaryType->salaryType->code == "A03") {
                        if ($isDailyEmployee) {
                            $totalAmount = $allowanceEmployeeSalaryType->amount * $employeeWorkDayCount;
                        } else if ($isMonthlyEmployeeAndAttendanceRequired) {
                            $totalAmount = ($allowanceEmployeeSalaryType->amount / $actualWorkDayCount) * $employeeWorkDayCount;
                        } else {
                            $totalAmount = $allowanceEmployeeSalaryType->amount;
                        }
                    }

                    EmployeePayslipDetail::create([
                        'employee_payslip_id' => $payslip->id,
                        'company_salary_type_id' => $allowanceEmployeeSalaryType->company_salary_type_id,
                        'name' => $allowanceEmployeeSalaryType->companySalaryType->salaryType->name,
                        'amount' => $totalAmount,
                        'note' => '',
                    ]);
                }

                $deductionEmployeeSalaryTypes = $employee->getDeductionSalaryTypes();
                foreach ($deductionEmployeeSalaryTypes as $deductionEmployeeSalaryType) {
                    $totalAmount = 0;
                    // keterlambatan
                    if ($deductionEmployeeSalaryType->companySalaryType->salaryType->code == "D01") {
                        $attendances = $employee->getAttendances($startDate, $endDate);
                        foreach ($attendances as $attendance) {
                            $totalAmount = 0;

                            if ($attendance->clockInAttendanceDetail && $attendance->clockInAttendanceDetail->attendancePenalty) {
                                $totalAmount += $attendance->clockInAttendanceDetail->attendancePenalty->penalty_amount;
                            }

                            if ($attendance->clockOutAttendanceDetail && $attendance->clockOutAttendanceDetail->attendancePenalty) {
                                $totalAmount += $attendance->clockOutAttendanceDetail->attendancePenalty->penalty_amount;
                            }

                            if ($attendance->startBreakAttendanceDetail && $attendance->startBreakAttendanceDetail->attendancePenalty) {
                                $totalAmount += $attendance->startBreakAttendanceDetail->attendancePenalty->penalty_amount;
                            }

                            if ($attendance->endBreakAttendanceDetail && $attendance->endBreakAttendanceDetail->attendancePenalty) {
                                $totalAmount += $attendance->endBreakAttendanceDetail->attendancePenalty->penalty_amount;
                            }
                        }
                    }
                    EmployeePayslipDetail::create([
                        'employee_payslip_id' => $payslip->id,
                        'company_salary_type_id' => $deductionEmployeeSalaryType->company_salary_type_id,
                        'name' => $deductionEmployeeSalaryType->companySalaryType->salaryType->name,
                        'amount' => $totalAmount,
                        'note' => '',
                    ]);
                }

                // uncomment this line if need to implement automatic ad hoc assignment
                // when payroll period is created
                // $adhocs = EmployeeAdHoc::whereBetween('date', [$startDate, $endDate])
                //     ->where('employee_id', $employee->id)
                //     ->get();

                // foreach ($adhocs as $adhoc) {
                //     EmployeePayslipAdHoc::create([
                //         'employee_payslip_id' => $payslip->id,
                //         'employee_ad_hoc_id' => $adhoc->id,
                //     ]);
                // }
            }
        });
    }

    public function isLeavePermission($leavePermissions, $date)
    {
        return $leavePermissions->where(function ($query) use ($date) {
            $query->whereDate('started_at', '>=', $date)
                ->whereDate('ended_at', '<=', $date);
        })->orWhere(function ($query) use ($date) {
            $query->whereDate('started_at', '>=', $date)
                ->whereDate('started_at', '<=', $date);
        })->orWhere(function ($query) use ($date) {
            $query->whereDate('ended_at', '>=', $date)
                ->whereDate('ended_at', '<=', $date);
        })->exists();
    }
}
