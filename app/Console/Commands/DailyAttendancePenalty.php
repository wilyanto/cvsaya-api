<?php

namespace App\Console\Commands;

use App\Enums\AttendanceType;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\AttendanceEmployee;
use App\Models\AttendancePenalty;
use App\Models\Employee;
use App\Models\EmployeeOneTimeShift;
use App\Models\Penalty;
use Carbon\Carbon;
use Hamcrest\Type\IsBoolean;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use MatanYadaev\EloquentSpatial\Objects\Point;

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
        $attendances = Attendance::where('date', today())->get();
        $today = today();

        foreach ($attendances as $attendance) {
            $isBreakInPenalty = true;
            $shift = $attendance?->shift;
            $company = $attendance?->employee?->company;

            if (!$attendance->clockInAttendanceDetail) {
                continue;
            }

            if (!$attendance->clockOutAttendanceDetail) {
                $shiftTime = new Carbon($shift->clock_out);

                $penalty = Penalty::where('attendance_type', AttendanceType::clockOut())
                    ->where('company_id', $company->id)
                    ->whereNull('lateness')
                    ->first();
                $attendanceDetail = AttendanceDetail::create([
                    'attendance_type' => AttendanceType::clockOut(),
                    'attended_at' => null,
                    'scheduled_at' => $today->copy()->addSeconds($shiftTime->secondsSinceMidnight()),
                    'attendance_qr_code_id' => null,
                    'image' => null,
                    'ip' => '127.0.0.1',
                    'location' => null,
                    'verified_by' => null,
                    'verified_at' => null,
                ]);
                AttendancePenalty::create([
                    'penalty_amount' => $penalty->amount,
                    'attendance_detail_id' => $attendanceDetail->id,
                    'penalty_id' => $penalty->id,
                    'penalty_name' => $penalty->name,
                    'note' => 'Tidak Scan Pulang'
                ]);
                $attendance->update(['clock_out_id' => $attendanceDetail->id]);
            }

            if (!$attendance->startBreakAttendanceDetail) {
                $shiftTime = new Carbon($shift->break_started_at);

                $penalty = Penalty::where('attendance_type', AttendanceType::breakTime())
                    ->where('company_id', $company->id)
                    ->whereNull('lateness')
                    ->first();
                $attendanceDetail = AttendanceDetail::create([
                    'attendance_type' => AttendanceType::startBreak(),
                    'attended_at' => null,
                    'scheduled_at' => $today->copy()->addSeconds($shiftTime->secondsSinceMidnight()),
                    'attendance_qr_code_id' => null,
                    'image' => null,
                    'ip' => '127.0.0.1',
                    'location' => null,
                    'verified_by' => null,
                    'verified_at' => null,
                ]);
                AttendancePenalty::create([
                    'penalty_amount' => $penalty->amount,
                    'attendance_detail_id' => $attendanceDetail->id,
                    'penalty_id' => $penalty->id,
                    'penalty_name' => $penalty->name,
                    'note' => 'Tidak Scan Istirahat Keluar'
                ]);
                $attendance->update(['start_break_id' => $attendanceDetail->id]);

                $isBreakInPenalty = false;
            }

            if (!$attendance->endBreakAttendanceDetail) {
                $shiftTime = new Carbon($shift->break_ended_at);
                $penalty = Penalty::where('attendance_type', AttendanceType::breakTime())
                    ->where('company_id', $company->id)
                    ->whereNull('lateness')
                    ->first();
                $attendanceDetail = AttendanceDetail::create([
                    'attendance_type' => AttendanceType::endBreak(),
                    'attended_at' => null,
                    'scheduled_at' => $today->copy()->addSeconds($shiftTime->secondsSinceMidnight()),
                    'attendance_qr_code_id' => null,
                    'image' => null,
                    'ip' => '127.0.0.1',
                    'location' => null,
                    'verified_by' => null,
                    'verified_at' => null,
                ]);
                if ($isBreakInPenalty) {
                    AttendancePenalty::create([
                        'penalty_amount' => $penalty->amount,
                        'attendance_detail_id' => $attendanceDetail->id,
                        'penalty_id' => $penalty->id,
                        'penalty_name' => $penalty->name,
                        'note' => 'Tidak Scan Istirahat Masuk'
                    ]);
                }
                $attendance->update(['end_break_id' => $attendanceDetail->id]);
            }
        }
    }
}
