<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Table;
use App\Enums\TableStatus;
use Carbon\CarbonInterval;
use App\Models\Reservation;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReservationRequest;

class ReservationController extends Controller
{
    public function index(Request $request)
    {

        $search_query = $request->input('query');

        $tables = Table::where('status', TableStatus::AVAILABLE)->get();
        $reservations = Reservation::with('table')
            ->where('first_name', 'like', '%' . $search_query . '%')
            ->orWhere('last_name', 'like', '%' . $search_query . '%')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();


        return Inertia::render('Admin/Reservations/Index', [
            'reservations' => $reservations,
            'tables'   => $tables,
        ]);
    }


    public function store(ReservationRequest $request)
    {
        $validatedRequest = $request->all();

        $validatedRequest['table_id'] = $request->table_id['id'];
        $validatedRequest['reservation_time'] = $request->reservation_time['time'];
        $table = Table::findOrFail($validatedRequest['table_id']);

        if ($request->guest_number > $table->guest_number) {
            return back()->withErrors([
                'message' => 'Guest number should be less than or same guest number on the table '
            ]);
        }


        Reservation::create($validatedRequest);

        return to_route('admin.reservations.index');
    }


    public function availableTime(Request $request)
    {
        $date = Carbon::parse($request->query('reservation_date')); // Replace with the desired date
        /**
         * Soon Will Make a
         */
        // Get all the reserved times between 9 AM and 8 PM
        $reservedTimes = Reservation::where('table_id', '=',$request->query('table_id'))
            ->whereDate('reservation_date', $date)
            ->where('reservation_time', '>=', '9:00:00')
            ->where('reservation_time', '<=', '20:00:00')
            ->pluck('reservation_time')
            ->toArray();

        //Generate all the possible times between 9 AM and 8 PM
        $startTime = Carbon::parse('09:00:00');
        $endTime = Carbon::parse('20:00:00');
        $interval = CarbonInterval::minutes(60);
        $availableTimes = [];
        
        while ($startTime->lt($endTime)) {
            $isReserved = false;
            $isAlreadyPassed = false;
            $time = $startTime->format('H:i:s');

            if (in_array($time, $reservedTimes)) {
                $isReserved = true;
               
            }

            if($date->isSameDay(Carbon::now())){
                if($time < Carbon::now()){
                    $isAlreadyPassed = true;
                }
            }

            $availableTimes[] = [
                'time'=> $time,
                'isReserved'=> $isReserved,
                'isAlreadyPassed'=> $isAlreadyPassed
            ];


            $startTime->add($interval);
        }

        // Filter out times that have already passed
      
       
        
        return response()->json([
            "availableTimes" => $availableTimes,
        ]);
    }

    // private function validateTheTimeOfReservation($reservation_date ,  $table){

    //     $reservation_date_startTime = Carbon::parse($reservation_date);
    //     $reservation_date_endTime = $reservation_date_startTime->addHours(2);

    //     $hasReservationOnTable = $table->reservations()
    //     ->whereDate('reservation_date', '>=', $reservation_date_startTime)
    //     ->whereDate('reservation_date','<=', $reservation_date_endTime)
    //     ->count();  


    //     return $hasReservationOnTable;

    // }
}
