<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\EmployeeOneTimeShift;
use App\Models\EmployeeRecurringShift;
use Illuminate\Console\Command;

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
        // TDOD : Need to check if user have permission to leave
        $employeeRecurringShifts = EmployeeRecurringShift::where('day', today()->dayOfWeek)->get();
        foreach ($employeeRecurringShifts as $employeeRecurringShift) {
            if (Attendance::where('employee_id', $employeeRecurringShift->employee_id)
                ->where('shift_id', $employeeRecurringShift->shift_id)
                ->whereDate('date', today())
                ->isNotExist()
            ) {
                Attendance::create([
                    'employee_id' => $employeeRecurringShift->employee_id,
                    'shift_id' => $employeeRecurringShift->shift_id,
                    'date' => today()
                ]);
            }
        }
        $employeeOneTimeShifts = EmployeeOneTimeShift::whereDate('date', today())->get();
        foreach ($employeeOneTimeShifts as $employeeOneTimeShift) {
            if (Attendance::where('employee_id', $employeeRecurringShift->employee_id)
                ->where('shift_id', $employeeRecurringShift->shift_id)
                ->whereDate('date', today())
                ->isNotExist()
            ) {
                Attendance::create([
                    'employee_id' => $employeeOneTimeShift->employee_id,
                    'shift_id' => $employeeOneTimeShift->shift_id,
                    'date' => today()
                ]);
            }
        }
    }
}
