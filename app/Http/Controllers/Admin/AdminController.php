<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TableStatus;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Inertia\Inertia;
use ReflectionEnum;

class AdminController extends Controller
{
    public function index(){
        $menus = Menu::orderBy('id','desc')->take(5)->get();
        $categories = Category::all();
      
        return Inertia::render('Admin/Index',[
             'recentlyMenus' => $menus,
             'categories' => $categories
        ]);
    }

    
}
