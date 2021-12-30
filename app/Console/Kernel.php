<?php

namespace App\Console;

use App\Jobs\CancelInActivePromotionsJob;
use App\Jobs\DailyBonusPlanFeaturesJob;
use App\Jobs\PopulateGeneralStatsJob;
use App\Jobs\PopulatePopularWarehouseJob;
use App\Jobs\PopulateTrendingCategoryJob;
use App\Jobs\PopulateTrendingProductJob;
use App\Jobs\StockLevelAlertJob;
use App\Jobs\UpdateTopSellerBadge;
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
        'App\Console\Commands\SendChatReminder'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('notification:chatreminder')->everyMinute();//->hourly();
        $schedule->job(new PopulateTrendingCategoryJob)->daily();
        $schedule->job(new PopulateTrendingProductJob)->daily();
        $schedule->job(new PopulatePopularWarehouseJob)->daily();
        $schedule->job(new PopulateGeneralStatsJob)->daily();
        $schedule->job(new DailyBonusPlanFeaturesJob)->dailyAt('00:00');
        $schedule->job(new UpdateTopSellerBadge)->dailyAt('00:00');
        $schedule->job(new StockLevelAlertJob)->dailyAt('00:00');
        $schedule->job(new CancelInActivePromotionsJob)->daily();
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
