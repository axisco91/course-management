<?php

namespace App\Console;

use App\Console\Commands\CheckAccesses;
use App\Console\Commands\CourseEndReminderMessage;
use App\Console\Commands\GreetingMessage;
use App\Console\Commands\updateCoursesStatus;
use App\Console\Commands\updateCoursesTracings;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        updateCoursesStatus::class,
        updateCoursesTracings::class,
        CheckAccesses::class,
        GreetingMessage::class,
        CourseEndReminderMessage::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $schedule->command('updateCoursesStatus')->dailyAt('00:00:00');
        $schedule->command('updateCoursesTracings')->everyThirtyMinutes();
        $schedule->command('checkAccesses')->everyMinute();
        $schedule->command('greetingMessage')->dailyAt('06:00');
        $schedule->command('courseEndReminderMessage')->dailyAt('06:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
