<?php

namespace App\Services;

use App\Enums\EmployeeType;
use App\Enums\PayslipStatusEnum;
use App\Enums\SalaryTypeEnum;
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
use Spatie\QueryBuilder\QueryBuilder;

class PayslipService
{
    public function getAll($pageSize)
    {
        $employeePayslip = QueryBuilder::for(EmployeePayslip::class)
            ->allowedIncludes([
                'employee',
                'payrollPeriod',
                'payslipDetails.companySalaryType.salaryType',
                'payslipAdHocs.employeeAdHoc.companySalaryType.salaryType'
            ])
            ->allowedFilters([
                'payroll_period_id'
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
                'payslipAdHocs.employeeAdHoc.companySalaryType.salaryType'
            ])
            ->firstOrFail();

        return $employeePayslip;
    }

    public function createPayslip($payrollPeriodId)
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

                $adhocs = EmployeeAdHoc::whereBetween('date', [$startDate, $endDate])
                    ->where('employee_id', $employee->id)
                    ->get();

                foreach ($adhocs as $adhoc) {
                    EmployeePayslipAdHoc::create([
                        'employee_payslip_id' => $payslip->id,
                        'employee_ad_hoc_id' => $adhoc->id,
                    ]);
                }
            }
        });
    }

    public function showAllByCompanyId($request, $companyId)
    {
        $pageSize = $request->input('page_size', 10);
        $keyword = $request->input('keyword', '');
        $startDate = $request->input('start_date', '2022-07-12');
        $endDate = $request->input('end_date', '2022-07-12');
        $company = Company::findOrFail($companyId);
        // $employees = $company->employees()->whereHas('candidate', function ($query) use ($keyword) {
        //     $query->where('name', 'LIKE', '%' . $keyword . '%');
        // })->orderBy(
        //     Candidate::select('name')
        //         ->whereColumn('candidates.id', 'employees.candidate_id')
        // )->paginate($pageSize);

        $employees = Employee::where('id', 1)->get();
        // hari kerja
        // check jika ada cuti atau dll
        $data = [];
        foreach ($employees as $employee) {
            // TODO: change this from payslip period
            $actualWorkDayCount = 26;
            $isDailyEmployee = $employee->type == EmployeeType::daily();
            $isMonthlyEmployee = $employee->type == EmployeeType::monthly() && !$employee->is_attendance_required;
            $isMonthlyEmployeeAndAttendanceRequired = $employee->type == EmployeeType::monthly() && $employee->is_attendance_required;
            $employeeWorkDayCount = $employee->getAttendanceCount($startDate, $endDate);
            $employeeSalaryTypes = $employee->salaryTypes;
            $allowanceSalaryTypes = $employee->getAllowanceSalaryTypes();
            $deductionSalaryTypes = $employee->getDeductionSalaryTypes();

            foreach ($allowanceSalaryTypes as $allowanceSalaryType) {
                // gaji pokok
                if ($allowanceSalaryType->code == "A01") {
                    // TODO: codes
                    if ($isDailyEmployee) {
                        $totalAmount = $allowanceSalaryType->amount * $employeeWorkDayCount;
                    } else if ($isMonthlyEmployeeAndAttendanceRequired) {
                        $totalAmount = ($allowanceSalaryType->amount / $actualWorkDayCount) * $employeeWorkDayCount;
                    } else {
                        $totalAmount = $allowanceSalaryType->amount;
                    }
                }

                // tunjangan
                if ($allowanceSalaryType->code == "A02") {
                    if ($isDailyEmployee) {
                        $totalAmount = $allowanceSalaryType->amount * $employeeWorkDayCount;
                    } else if ($isMonthlyEmployeeAndAttendanceRequired) {
                        $totalAmount = ($allowanceSalaryType->amount / $actualWorkDayCount) * $employeeWorkDayCount;
                    } else {
                        $totalAmount = $allowanceSalaryType->amount;
                    }
                }
            }

            foreach ($deductionSalaryTypes as $deductionSalaryType) {
                //TODO: how to calc deduction
                // keterlambatan
                if ($allowanceSalaryType->code == "D01") {
                    $attendancePenalties = [];
                    $attendances = $employee->getAttendances($startDate, $endDate);
                    foreach ($attendances as $attendance) {
                        $totalAttendancePenalty = $attendance->getAttendancePenaltyTotal();
                        dd($totalAttendancePenalty);
                        $clockInPenalty = $attendance->clockInAttendanceDetail?->attendancePenalty;
                        $clockOutPenalty = $attendance->clockOutAttendanceDetail?->attendancePenalty;
                        $startBreakPenalty = $attendance->startBreakAttendanceDetail?->attendancePenalty;
                        $endBreakPenalty = $attendance->endBreakAttendanceDetail?->attendancePenalty;
                        $attendancePenalty = [
                            'date' => $attendance->date,
                            'clock_in_penalty' => $clockInPenalty,
                            'clock_out_penalty' => $clockOutPenalty,
                            'start_break_penalty' => $startBreakPenalty,
                            'end_break_penalty' => $endBreakPenalty,
                        ];
                        array_push($attendancePenalties, $attendancePenalty);
                    }
                }
            }
            // check if employee have leave permission or not
            $leavePermissions = $employee->getLeavePermissionsByDateRange($startDate, $endDate);
            $leavePermissionCount = $leavePermissions->count();

            $deductions = [];
            $attendances = $employee->getAttendances($startDate, $endDate);
            $attendanceCount = $attendances->count();
            $attendancePenalties = [];
            $absentCount = 0;
            foreach ($attendances as $attendance) {
                if (
                    !$attendance->clockInAttendanceDetail ||
                    !$this->isLeavePermission($leavePermissions, $attendance->date)
                ) {
                    $absentCount++;
                }
                $clockInPenalty = $attendance->clockInAttendanceDetail?->attendancePenalty;
                $clockOutPenalty = $attendance->clockOutAttendanceDetail?->attendancePenalty;
                $startBreakPenalty = $attendance->startBreakAttendanceDetail?->attendancePenalty;
                $endBreakPenalty = $attendance->endBreakAttendanceDetail?->attendancePenalty;
                $attendancePenalty = [
                    'date' => $attendance->date,
                    'clock_in_penalty' => $clockInPenalty,
                    'clock_out_penalty' => $clockOutPenalty,
                    'start_break_penalty' => $startBreakPenalty,
                    'end_break_penalty' => $endBreakPenalty,
                ];
                array_push($attendancePenalties, $attendancePenalty);
            }

            $attendanceCount = $attendanceCount - $leavePermissionCount - $absentCount;
            // check absent or not from attendance clock in
            $deductions = [
                'attendance_penalties' => $attendancePenalties
            ];

            $datum = [
                'employee' => $employee,
                'incomes' => $incomes,
                'deductions' => $deductions
            ];

            array_push($data, $datum);
        }

        dd(json_encode($data));
        // komponen utama :
        // Gaji Pokok
        // Tunjangan
        // Hari Kerja
        // insentif
        // potongan
        // total

        return $employees;
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
