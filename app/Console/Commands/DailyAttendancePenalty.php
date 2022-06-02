<?php

namespace App\Console\Commands;

use App\Enums\AttendanceType;
use App\Models\Attendance;
use App\Models\AttendanceEmployee;
use App\Models\AttendancePenalty;
use App\Models\Employee;
use App\Models\EmployeeOneTimeShift;
use App\Models\Penalty;
use Carbon\Carbon;
use Hamcrest\Type\IsBoolean;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        // how to handle if attendance more than 1 shift (more than 1 clock_in, clock_out, etc)
        $employees = Employee::get();
        $today = today();
        foreach ($employees as $employee) {
            Log::info($employees);
            $company = $employee->company;
            $companyId = $company->id;
            $employeeShifts = $employee->getShifts($today);
            foreach ($employeeShifts as $employeeShift) {
                // one time shift no penalty?
                if ($employeeShift instanceof EmployeeOneTimeShift) {
                    return;
                }
                $isBreakInPenalty = true;
                $todayAttendances = $employeeShift->getAttendances($today);

                // no clock in
                $clockInAttendance = $todayAttendances->where('attendance_type', AttendanceType::clockIn())
                    ->where('shift_id', $employeeShift->shift_id)
                    ->where('employee_id', $employee->id)
                    ->whereDate('scheduled_at', $today)
                    ->first();
                if (!$clockInAttendance) {
                    return;
                }

                // no clock out
                $clockOutAttendance = $todayAttendances->where('attendance_type', AttendanceType::clockOut())
                    ->where('shift_id', $employeeShift->shift_id)
                    ->where('employee_id', $employee->id)
                    ->whereDate('scheduled_at', $today)
                    ->first();
                if (!$clockOutAttendance) {
                    $shiftTime = new Carbon($employeeShift->shift->clock_out);
                    $penalty = Penalty::where('attendance_type', AttendanceType::clockOut())
                        ->where('company_id', $companyId)
                        ->whereNull('lateness')
                        ->first();
                    $attendance = Attendance::create([
                        'attendance_type' => AttendanceType::clockOut(),
                        'attended_at' => null,
                        'scheduled_at' => $today->copy()->addSeconds($shiftTime->secondsSinceMidnight()),
                        'attendance_qr_code_id' => null,
                        'image' => null,
                        'ip' => '127.0.0.1',
                        'longitude' => null,
                        'latitude' => null,
                        'verified_by' => null,
                        'verified_at' => null,
                        'shift_id' => $employeeShift->shift_id,
                        'employee_id' => $employee->id
                    ]);
                    AttendancePenalty::create([
                        'penalty_amount' => $penalty->amount,
                        'attendance_id' => $attendance->id,
                        'penalty_id' => $penalty->id,
                        'penalty_name' => $penalty->name,
                        'note' => 'Tidak Scan Pulang'
                    ]);
                }

                // no break
                $breakOutAttendance = $todayAttendances->where('attendance_type', AttendanceType::breakStartedAt())
                    ->where('shift_id', $employeeShift->shift_id)
                    ->where('employee_id', $employee->id)
                    ->whereDate('scheduled_at', $today)
                    ->first();
                if (!$breakOutAttendance) {
                    $shiftTime = new Carbon($employeeShift->shift->break_started_at);
                    $penalty = Penalty::where('attendance_type', AttendanceType::breakTime())
                        ->where('company_id', $companyId)
                        ->whereNull('lateness')
                        ->first();
                    $attendance = Attendance::create([
                        'attendance_type' => AttendanceType::breakStartedAt(),
                        'attended_at' => null,
                        'scheduled_at' => $today->copy()->addSeconds($shiftTime->secondsSinceMidnight()),
                        'attendance_qr_code_id' => null,
                        'image' => null,
                        'ip' => '127.0.0.1',
                        'longitude' => null,
                        'latitude' => null,
                        'verified_by' => null,
                        'verified_at' => null,
                        'shift_id' => $employeeShift->shift_id,
                        'employee_id' => $employee->id
                    ]);
                    AttendancePenalty::create([
                        'penalty_amount' => $penalty->amount,
                        'attendance_id' => $attendance->id,
                        'penalty_id' => $penalty->id,
                        'penalty_name' => $penalty->name,
                        'note' => 'Tidak Scan Istirahat Keluar'
                    ]);
                    $isBreakInPenalty = false;
                }

                $breakInAttendance = $todayAttendances->where('attendance_type', AttendanceType::breakEndedAt())
                    ->where('shift_id', $employeeShift->shift_id)
                    ->where('employee_id', $employee->id)
                    ->whereDate('scheduled_at', $today)
                    ->first();
                if (!$breakInAttendance) {
                    $shiftTime = new Carbon($employeeShift->shift->break_ended_at);
                    $penalty = Penalty::where('attendance_type', AttendanceType::breakTime())
                        ->where('company_id', $companyId)
                        ->whereNull('lateness')
                        ->first();
                    $attendance = Attendance::create([
                        'attendance_type' => AttendanceType::breakEndedAt(),
                        'attended_at' => null,
                        'scheduled_at' => $today->copy()->addSeconds($shiftTime->secondsSinceMidnight()),
                        'attendance_qr_code_id' => null,
                        'image' => null,
                        'ip' => '127.0.0.1',
                        'longitude' => null,
                        'latitude' => null,
                        'verified_by' => null,
                        'verified_at' => null,
                        'shift_id' => $employeeShift->shift_id,
                        'employee_id' => $employee->id
                    ]);
                    if ($isBreakInPenalty) {
                        AttendancePenalty::create([
                            'penalty_amount' => $penalty->amount,
                            'attendance_id' => $attendance->id,
                            'penalty_id' => $penalty->id,
                            'penalty_name' => $penalty->name,
                            'note' => 'Tidak Scan Istirahat Masuk'
                        ]);
                    }
                }
            }
        }
        return;
    }
}
