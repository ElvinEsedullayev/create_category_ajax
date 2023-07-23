<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use DataTables;
class CategoryController extends Controller
{
        public function index(Request $request)
    {
        $categories = Category::select('id','name','type');
        if($request->ajax()){
            return DataTables::of($categories)
            ->addColumn('action',function($row){
                return '<a href="javascript:void(0)" class="btn-sm btn btn-info editButton" data-id="'.$row->id.'">Edit</a> 
                <a href="javascript:void(0)" class="btn-sm btn btn-danger delButton" data-id="'.$row->id.'">Del</a>
                ';
                 
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        //return view('category');
    }
    public function create(Request $request)
    {
        // $categories = Category::all();
        // if($request->ajax()){
        //     return DataTable::of($categories)->make(true);
        // }
        return view('category');
    }
    public function store(Request $request)
    {
        if($request->category_id != null){
            $category = Category::find($request->category_id);
            if(! $category){
                abort(404);
            }else{
                $category->update([
                    'name' => $request->name,
                    'type' => $request->type
                ]);
                return response()->json(['success' => 'Category has been updated successfully!'],201);
            }
        }else{
            $request->validate([
            'name' => 'required|min:2|max:30',
            'type' => 'required'
         ]);
         Category::create([
            'name' => $request->name,
            'type' => $request->type
         ]);
         return response()->json(['success' => 'Category has been added successfully!'],201);
        }

    }

    public function edit($id)
    {
        $category = Category::find($id);
        if(! $category){
            abort(404);
        }
        return $category;
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if(! $category){
            abort(404);
        }
        $category->delete();
        return response()->json([
            'success' => 'Category has been deleted successfully'
        ],201);
    }
}
