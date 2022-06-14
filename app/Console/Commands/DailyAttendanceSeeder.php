<?php

namespace App\Console\Commands;

use App\Enums\LeavePermissionStatusType;
use App\Models\Attendance;
use App\Models\EmployeeOneTimeShift;
use App\Models\EmployeeRecurringShift;
use App\Models\LeavePermission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyAttendanceSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendanceSeeder:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed attendance data everyday at midnight';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $employeeRecurringShifts = EmployeeRecurringShift::where('day', today()->dayOfWeek)->get();
        foreach ($employeeRecurringShifts as $employeeRecurringShift) {
            if (LeavePermission::query()
                ->where('employee_id', $employeeRecurringShift->employee_id)
                ->where('status', LeavePermissionStatusType::accepted())
                ->whereDate('started_at', '<=', today())
                ->whereDate('ended_at', '>=', today())
                ->exists()
            ) {
                return;
            }

            if (Attendance::query()
                ->where('employee_id', $employeeRecurringShift->employee_id)
                ->where('shift_id', $employeeRecurringShift->shift_id)
                ->whereDate('date', today())
                ->exists()
            ) {
                return;
            }

            Attendance::create([
                'employee_id' => $employeeRecurringShift->employee_id,
                'shift_id' => $employeeRecurringShift->shift_id,
                'date' => today()
            ]);
        }

        $employeeOneTimeShifts = EmployeeOneTimeShift::whereDate('date', today())->get();
        foreach ($employeeOneTimeShifts as $employeeOneTimeShift) {
            if (LeavePermission::query()
                ->where('employee_id', $employeeRecurringShift->employee_id)
                ->where('status', LeavePermissionStatusType::accepted())
                ->whereDate('started_at', '<=', today())
                ->whereDate('ended_at', '>=', today())
                ->exist()
            ) {
                return;
            }

            if (Attendance::query()
                ->where('employee_id', $employeeRecurringShift->employee_id)
                ->where('shift_id', $employeeRecurringShift->shift_id)
                ->whereDate('date', today())
                ->exists()
            ) {
                return;
            }

            Attendance::create([
                'employee_id' => $employeeOneTimeShift->employee_id,
                'shift_id' => $employeeOneTimeShift->shift_id,
                'date' => today()
            ]);
        }
    }
}
