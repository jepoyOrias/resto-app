<?php
namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryController extends Controller
{
    public function index(Request $request){
        
        /** 
         *  Search Category Base on the $search_query parameter 
         * if there is no Search query it will render as a default category that paginates by 10 items per page
         * 
        */

        $search_query = $request->input('query');
        $categories = Category::where('name','like','%'.$search_query.'%')
                                ->orderBy('id','desc')
                                ->paginate(10)
                                ->withQueryString();


        return Inertia::render('Admin/Categories/Index' ,[
                'categories' => $categories,
        ]);
    }


    public function store(CategoryRequest $request){


        /**
            * Checking if threre is an image in Request 
            * Adding the new Image to the Storage and put the path on database
        */
        $image_path = '';
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('categories', 'public');
        }

        /**
            * Create the Category Request here and save it to database
         */ 
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image_path
        ]);
        
        return to_route('admin.categories.index');
    }


    public function update(Request $request, Category $category){

        /**
            * Validating the request 
         */ 
        $request->validate([
            'formItem.name' => 'required|unique:categories,name,' . $category->id,
            'formItem.description' => 'required',
        ]);

        /*
            * Checking if threre is an image in Request 
            * Removing the image from storage if the image in Request Exists
            * Adding the new Image to the Storage and put the path on database
         */

        $image_path = $category->image;
        if ($request->hasFile('image')) {
            Storage::delete($category->image);
            $image_path = $request->file('image')->store('categories', 'public');
        }


        /**
         * Updating the fields in db with the model route bindings
         */
        $category->update([
            'name' => $request->formItem['name'],
            'description' => $request->formItem['description'],
            'image' => $image_path
        ]);

        return to_route('admin.categories.index');

    }


    public function destroy(Category $category){

        if(Storage::exists($category->image))  Storage::delete($category->image);
        $category->delete();
        return to_route('admin.categories.index');
    }



}
