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
use Illuminate\Database\Eloquent\Builder;

class AdminController extends Controller
{
    public function index(Request $request){
        $categoryId = $request->input('category_id');
         $menus = Menu::with('categories')->orderBy('name','asc')->take(6)->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $currentTime = now()->timezone('Asia/Manila')->startOfHour()->format('H:i:s');

      $reservation = Reservation::with('table:id,name,image')
                    ->whereDate('reservation_date',Carbon::now()->startOfDay())
                    ->where('reservation_time', '>=', '9:00:00')
                    ->where('reservation_time', '<=', '20:00:00')
                    ->where('reservation_time', '>=' ,$currentTime)
                    ->orderBy('reservation_time')
                    ->get()->toArray();


        return Inertia::render('Admin/Index',[
             'recentlyMenus' => $menus,
             'categories' => $categories,
             'todaysReservation' => $reservation,
             'sort'=> $categoryId,
        ]);
    }

    
}
