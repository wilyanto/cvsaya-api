<?php

namespace App\Console\Commands;

use App\Enums\AttendanceType;
use App\Models\AttendanceEmployee;
use App\Models\AttendancePenalty;
use App\Models\Employee;
use App\Models\Penalty;
use Illuminate\Console\Command;

class DailyAttendancePenalty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendancePenalty:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically insert attendance penalty if employee forgot to break or checkout';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // TODO: need to discuss either need to create attendance or not
        $employees = Employee::get();
        foreach ($employees as $employee) {
            $company = $employee->company;
            $companyId = $company->id;
            $employeeShifts = $employee->getShifts(today());
            foreach ($employeeShifts as $employeeShift) {
                $todayAttendances = $employeeShift->getTodayAttendances();

                // no clock out
                $clockOutAttendance = $todayAttendances->where('attendance_type', AttendanceType::clockOut())->first();
                if (!$clockOutAttendance) {
                    $penalty = Penalty::where('attendance_type', AttendanceType::clockOut())
                        ->where('company_id', $companyId)
                        ->first();
                    $attendanceEmployees = AttendanceEmployee::where('attendance_id', $clockOutAttendance->id)->get();
                    foreach ($attendanceEmployees as $attendanceEmployee) {
                        AttendancePenalty::create([
                            'penalty_amount' => $penalty->amount,
                            'attendance_employee_id' => $attendanceEmployee->id,
                            'penalty_id' => $penalty->id,
                            'penalty_name' => $penalty->name,
                            'note' => 'Tidak Scan Pulang'
                        ]);
                    }
                }

                // no break
                $breakAttendance = $todayAttendances->where('attendance_type', AttendanceType::breakStartedAt())
                    ->orWhere('attendance_type', AttendanceType::breakEndedAt())
                    ->first();
                if (!$breakAttendance) {
                    $penalty = Penalty::where('attendance_type', AttendanceType::breakTime())
                        ->where('company_id', $companyId)
                        ->first();
                    $attendanceEmployees = AttendanceEmployee::where('attendance_id', $clockOutAttendance->id)->get();
                    foreach ($attendanceEmployees as $attendanceEmployee) {
                        AttendancePenalty::create([
                            'penalty_amount' => $penalty->amount,
                            'attendance_employee_id' => $attendanceEmployee->id,
                            'penalty_id' => $penalty->id,
                            'penalty_name' => $penalty->name,
                            'note' => 'Tidak Scan Istirahat'
                        ]);
                    }
                }
            }
        }

        return;
    }
}
