<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use ReflectionEnum;
use App\Models\Menu;
use Inertia\Inertia;
use App\Models\Category;
use App\Enums\TableStatus;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Events\ReservedTable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index(){
        $menus = Menu::orderBy('id','desc')->take(5)->get();
        $categories = Category::all();

      $reservation =  DB::table('reservations')
                        ->join('tables', 'reservations.table_id', '=', 'tables.id')
                        ->select('reservations.*', 'tables.name as table_name', 'tables.image as table_image')
                        ->whereDate('reservations.reservation_date', Carbon::now())
                        ->where('reservations.reservation_time', '>=', '09:00:00')
                        ->where('reservations.reservation_time', '<=', '20:00:00')
                        ->where('reservations.reservation_time', '>=', now()->timezone('Asia/Manila')->startOfHour()->format('H:i:s'))
                        ->orderBy('reservations.reservation_time')
                        ->get();


        return Inertia::render('Admin/Index',[
             'recentlyMenus' => $menus,
             'categories' => $categories,
             'todaysReservation' => $reservation
        ]);

       
    }

    
}
