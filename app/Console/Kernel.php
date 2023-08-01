<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel.
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\NetworkCron::class,
        Commands\WebServerCron::class,
        Commands\DatabaseCron::class,
        Commands\ApiCron::class,
        
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {   
        $schedule->command('cron:network')
            ->everyMinute();
        $schedule->command('monitor:check-uptime')
            ->everyMinute();
        $schedule->command('monitor:check-certificate')
            ->everyMinute();
        $schedule->command('cron:webserver')
            ->everyMinute();
        $schedule->command('cron:database')
            ->everyMinute();
        $schedule->command('cron:api')
            ->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        include base_path('routes/console.php');
    }
}
