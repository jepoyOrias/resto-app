<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Table;
use App\Models\Reservation;
use App\Events\ReservedTable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        $currentTime = now()->timezone('Asia/Manila')->startOfHour()->format('H:i:s');

                        
       $reservations =  Reservation::with('table:id,name,image')
                        ->whereDate('reservation_date',$request_date)
                        ->where('reservation_time', '>=', '9:00:00')
                        ->where('reservation_time', '<=', '20:00:00')
                        ->where('reservation_time', '>=' ,$currentTime)
                        ->orderBy('reservation_time')
                        ->get()->toArray();
                        
        event(new ReservedTable($reservations));
    }
}
