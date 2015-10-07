<?php
// Containes all the sceduled jobs on kidup, mainly cron like jobs.

/**
 * Make sure to have to following cron script running (dev and live):
 // * * * * * php /vagrant/yii schedule/run --scheduleFile=@app/config/schedule.php 1>> /dev/null 2>&1
 // * * * * * php /var/www/current/yii schedule/run --scheduleFile=@app/config/schedule.php 1>> /dev/null 2>&1
 */
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

// Place here all of your cron jobs

// This command will execute ls command every five minutes
$schedule->exec('ls')->everyFiveMinutes();

// This command will execute migration command of your application every hour
$schedule->command('cron/minute')->everyMinute();
$schedule->command('cron/hour')->hourly();
$schedule->command('cron/hour')->daily();

// This command will call callback function every day at 10:00
//$schedule->call(function(\yii\console\Application $app) {
//    // Some code here...
//})->dailyAt('10:00');