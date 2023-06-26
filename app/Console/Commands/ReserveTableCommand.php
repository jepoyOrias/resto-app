<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Reservation;
use App\Events\ReservedTable;
use Illuminate\Console\Command;

class ReserveTableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reserved:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $request_date = now();
        $startTime = Carbon::createFromTime($request_date->hour,$request_date->minute,$request_date->second);
        $endTime = Carbon::createFromTime($request_date->addHours(1)->hour,$request_date->minute,$request_date->second);

       $reservation = Reservation::whereDate('reservation_date',$request_date)
        ->whereBetween('reservation_date',[$startTime, $endTime])
        ->get();

        event(new ReservedTable($reservation));
    }
}
