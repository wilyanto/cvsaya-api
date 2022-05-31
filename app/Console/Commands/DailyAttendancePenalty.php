<?php

namespace App\Console\Commands;

use App\Enums\AttendanceType;
use App\Models\Attendance;
use App\Models\AttendanceEmployee;
use App\Models\AttendancePenalty;
use App\Models\Employee;
use App\Models\Penalty;
use Carbon\Carbon;
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
                // no clock in
                $clockInAttendance = $todayAttendances->where('attendance_type', AttendanceType::clockIn())
                    ->where('shift_id', $employeeShift->id)
                    ->first();
                if (!$clockInAttendance) {
                    return;
                }

                // no clock out
                $clockOutAttendance = $todayAttendances->where('attendance_type', AttendanceType::clockOut())
                    ->where('shift_id', $employeeShift->id)
                    ->first();
                if (!$clockOutAttendance) {
                    $penalty = Penalty::where('attendance_type', AttendanceType::clockOut())
                        ->where('company_id', $companyId)
                        ->first();
                    $attendance = Attendance::create([
                        'attendance_type' => AttendanceType::clockOut(),
                        'attended_at' => null,
                        'scheduled_at' => Carbon::today()->addSeconds($employeeShift->secondsSinceMidnight()),
                        'attendance_qr_code_id' => null,
                        'image' => null,
                        'ip' => '127.0.0.1',
                        'longitude' => null,
                        'latitude' => null,
                        'verified_by' => null,
                        'verified_at' => null,
                        'shift_id' => $employeeShift->id
                    ]);
                    $attendance->employees()->attach($employee->id);
                    $attendanceEmployee = AttendanceEmployee::where('attendance_id', $attendance->id)->first();
                    AttendancePenalty::create([
                        'penalty_amount' => $penalty->amount,
                        'attendance_employee_id' => $attendanceEmployee->id,
                        'penalty_id' => $penalty->id,
                        'penalty_name' => $penalty->name,
                        'note' => 'Tidak Scan Pulang'
                    ]);
                }

                // no break
                $breakOutAttendance = $todayAttendances->where('attendance_type', AttendanceType::breakStartedAt())
                    ->where('shift_id', $employeeShift->id)
                    ->first();
                if (!$breakOutAttendance) {
                    $penalty = Penalty::where('attendance_type', AttendanceType::breakStartedAt())
                        ->where('company_id', $companyId)
                        ->first();
                    $attendance = Attendance::create([
                        'attendance_type' => AttendanceType::breakStartedAt(),
                        'attended_at' => null,
                        'scheduled_at' => Carbon::today()->addSeconds($employeeShift->secondsSinceMidnight()),
                        'attendance_qr_code_id' => null,
                        'image' => null,
                        'ip' => '127.0.0.1',
                        'longitude' => null,
                        'latitude' => null,
                        'verified_by' => null,
                        'verified_at' => null,
                        'shift_id' => $employeeShift->id
                    ]);
                    $attendance->employees()->attach($employee->id);
                    $attendanceEmployee = AttendanceEmployee::where('attendance_id', $attendance->id)->first();
                    AttendancePenalty::create([
                        'penalty_amount' => $penalty->amount,
                        'attendance_employee_id' => $attendanceEmployee->id,
                        'penalty_id' => $penalty->id,
                        'penalty_name' => $penalty->name,
                        'note' => 'Tidak Scan Istirahat Masuk'
                    ]);
                }

                $breakInAttendance = $todayAttendances->where('attendance_type', AttendanceType::breakEndedAt())
                    ->where('shift_id', $employeeShift->id)
                    ->first();
                if (!$breakInAttendance) {
                    $penalty = Penalty::where('attendance_type', AttendanceType::breakEndedAt())
                        ->where('company_id', $companyId)
                        ->first();
                    $attendance = Attendance::create([
                        'attendance_type' => AttendanceType::breakEndedAt(),
                        'attended_at' => null,
                        'scheduled_at' => Carbon::today()->addSeconds($employeeShift->secondsSinceMidnight()),
                        'attendance_qr_code_id' => null,
                        'image' => null,
                        'ip' => '127.0.0.1',
                        'longitude' => null,
                        'latitude' => null,
                        'verified_by' => null,
                        'verified_at' => null,
                        'shift_id' => $employeeShift->id
                    ]);
                    $attendance->employees()->attach($employee->id);
                    $attendanceEmployee = AttendanceEmployee::where('attendance_id', $attendance->id)->first();
                    AttendancePenalty::create([
                        'penalty_amount' => $penalty->amount,
                        'attendance_employee_id' => $attendanceEmployee->id,
                        'penalty_id' => $penalty->id,
                        'penalty_name' => $penalty->name,
                        'note' => 'Tidak Scan Istirahat Keluar'
                    ]);
                }
            }
        }

        return;
    }
}
