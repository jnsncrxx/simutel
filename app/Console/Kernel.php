<?php

namespace App\Console;

use App\Models\Room_reservations;
use Illuminate\Console\Scheduling\Schedule;
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
        $schedule->call(function () {
            foreach (Room_reservations::all() as $reservation) {
                if($reservation->status == 'Confirmed' && date('Y-m-d', strtotime($reservation->check_in)) == date('Y-m-d'))
                    $reservation->status = 'Due In';
                if(date('Y-m-d', strtotime($reservation->check_out)) == date('Y-m-d') && $reservation->status == 'Checked-in')
                    $reservation->status = 'Due Out';
                if((date('Y-m-d', strtotime($reservation->check_in)) < date('Y-m-d') && $reservation->status == 'Due In') || (date('Y-m-d', strtotime($reservation->check_in)) < date('Y-m-d') && $reservation->status == 'Confirmed'))
                    $reservation->status = 'No Show';
                $reservation->save();
            }
        })->everyMinute();
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
