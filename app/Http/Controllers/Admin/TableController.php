<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Table;
use App\Enums\TableStatus;
use App\Enums\TableLocation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\TableRequest;

class TableController extends Controller
{
    
    public function index(Request $request,){
        
        /** 
         *  Search TAble Base on the $search_query parameter 
         * if there is no Search query it will render as a default category that paginates by 10 items per page
         * 
        */
        $tableStatus = TableStatus::cases();
        $tableLocation = TableLocation::cases();
        $search_query = $request->input('query');
        $tables = Table::where('name','like','%'.$search_query.'%')
                                ->orderBy('id','desc')
                                ->paginate(10)
                                ->withQueryString();


        return Inertia::render('Admin/Tables/Index' ,[
                'table' => $tables,
                'tableStatus' => $tableStatus,
                'tableLocation' => $tableLocation
        ]);
    }


    public function store(TableRequest $request){
        $image_path = '';
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('tables', 'public');
        }

        Table::create([
            'name' => $request->name,
            'guest_number' => $request->guest_number,
            'location' => $request->location,
            'status' => $request->status,
            'image' => $image_path
        ]);

        return to_route('admin.tables.index');
        
    }

    public function update(Request $request,Table $table){

        $request->validate([
            'formItem.name' => 'required|unique:tables,name,' . $table->id,
            'formItem.guest_number' => 'required',
            'formItem.location' => 'required',
            'formItem.status' => 'required'
        ]);

        $image_path = $table->image;
        if ($request->hasFile('image')) {
            Storage::delete($table->image);
            $image_path = $request->file('image')->store('tables', 'public');
        }

        $table->update([
            'name' => $request->formItem['name'],
            'guest_number' => $request->formItem['guest_number'],
            'location' => $request->formItem['location'],
            'status' => $request->formItem['status'],
            'image' => $image_path
        ]);

        return to_route('admin.tables.index');
        
    }



    public function destroy(Table $table){
        if(Storage::exists($table->image))  Storage::delete($table->image);
        $table->delete();
        return to_route('admin.tables.index');
    }
}
