<?php

namespace Ovic\Framework;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class FrameworkSchedule extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule( Schedule $schedule )
    {
        $schedule->call(function () {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
        })->monthly();
    }
}
