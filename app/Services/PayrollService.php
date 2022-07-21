<?php

namespace App\Services;

use App\Enums\EmployeeType;
use App\Enums\SalaryTypeEnum;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Employee;
use Spatie\QueryBuilder\QueryBuilder;

class PayrollService
{
    public function showAll($pageSize)
    {
        $employees = Employee::orderBy(
            Candidate::select('name')
                ->whereColumn('candidates.id', 'employees.candidate_id')
        )->paginate($pageSize);

        // not hardcodeed, dynamic, how to solve
        foreach ($employees as $employee) {
            foreach ($employee->salaryTypes as $salaryType) {
            }
        }
        return $employees;
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
            $incomes = [];
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

                array_push($incomes, [
                    'name' => $allowanceSalaryType->salaryType->name,
                    'amount' => $totalAmount,
                    'note' => '',
                ]);
            }

            foreach ($deductionSalaryTypes as $deductionSalaryType) {
                //TODO: how to calc deduction
                // keterlambatan
                if ($allowanceSalaryType->code == "D01") {
                    $attendancePenalties = [];
                    $attendances = $employee->getAttendances($startDate, $endDate);
                    foreach ($attendances as $attendance) {
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
