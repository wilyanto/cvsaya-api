<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\CandidateStatusCron;
use App\Console\Commands\DailyAttendancePenalty;
use App\Console\Commands\DailyAttendanceSeeder;
use App\Console\Commands\DailyCandidateReminder;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(CandidateStatusCron::class)->dailyAt('08:00');
        $schedule->command(DailyAttendancePenalty::class)->dailyAt('23:59');
        $schedule->command(DailyAttendanceSeeder::class)->dailyAt('00:01');
        $schedule->command(DailyCandidateReminder::class)->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
