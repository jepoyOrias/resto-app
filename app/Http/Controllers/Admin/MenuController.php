<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\MenuRequest;
use Illuminate\Database\Eloquent\Builder;


class MenuController extends Controller
{
    public function index(Request $request){
            
        /** 
         *  Search Category and Menus Base on the $search_query parameter 
         * if there is no Search query it will render as a default category that paginates by 10 items per page
         * 
        */

        $search_query = $request->input('query');
         $menus = Menu::query()
                        ->with('categories')
                        ->whereHas('categories',function (Builder $query) use ($search_query) {
                            return $query->where('name', 'LIKE','%'.$search_query.'%');
                        })
                        ->orWhere('name','like','%'.$search_query.'%')
                        ->orderBy('id', 'desc')
                        ->paginate(10)
                        ->withQueryString();

        $categories = Category::all();

        return Inertia::render('Admin/Menus/Index' ,[
                'menus' => $menus,
                'categories' => $categories,
        ]);
    }


    public function store(MenuRequest $request){

        /*
            * Checking if threre is an image in Request 
            * Removing the image from storage if the image in Request Exists
            * Adding the new Image to the Storage and put the path on database
         */

        $image_path = '';
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('menus', 'public');
        }
        
        /**
       * Create  the menu item
       */

        $menu =  Menu::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image_path,
            'price' => $request->price
        ]);


        /**
         * Check the selected categories
         * If existing categories are their add it to the pivot table
         */

        if($request->has('selectedCategory')){
                foreach($request->selectedCategory as $category){
                    $menu->categories()->attach($category);
                }
        }
        
        return to_route('admin.menus.index');
    }


    public function update(Request $request, Menu $menu){

        /*
            * Checking if threre is an image in Request 
            * Removing the image from storage if the image in Request Exists
            * Adding the new Image to the Storage and put the path on database
         */
    
        $request->validate([
            'formItem.name' => 'required|unique:menus,name,' . $menu->id,
            'formItem.description' => 'required',
        ]);


     
        $image_path = $menu->image;
        if ($request->hasFile('image')) {
            Storage::delete($menu->image);
            $image_path = $request->file('image')->store('menus', 'public');
        }

        $menu->update([
            'name' => $request->formItem['name'],
            'description' => $request->formItem['description'],
            'image' => $image_path,
            'price' => $request->formItem['price'],
        ]);


        /**
         * Check the selected categories
         * If existing categories are their update the pivot table corresponding to the selectedCategory
         */
        

        if($request->formItem['selectedCategory']){
      
            $menu->categories()->sync($request->formItem['selectedCategory']);
               
        }
        

     

        return to_route('admin.menus.index');
        
    }


    public function destroy(Menu $menu){

        if(Storage::exists($menu->image))  Storage::delete($menu->image);
        $menu->delete();
        return to_route('admin.menus.index');
    }

    

}
