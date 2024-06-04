<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Category::all();

        return view ('admin.category.index',compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view ('admin.category.create',compact('categories'));
    }

    /**
     * Category a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate ($request, [
            'category_name'  => 'required',
        ]);

        $data = $request->all();

        if ($request->hasfile('image'))
         {  //check file present or not
             $images = $request->file('image'); //get the file
             $image = time().'.'.$images->getClientOriginalExtension();
             $destinationPath = public_path('/image/category'); //public path folder dir
             $images->move($destinationPath, $image);  //mve to destination you mentioned 
             $data['image']=$image;
         } 

         $data['category_slug'] = Str::slug($request->input('category_name'));

        $category = new Category;
        $category = Category::create($data);

        return redirect ('admin/category')->with('success',"Added Successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);

        return view ('admin.category.edit',compact('category','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate ($request, [
            'category_name'  => 'required',
        ]);

        $data = $request->all();

        if ($request->hasfile('image'))
         {  //check file present or not
             $images = $request->file('image'); //get the file
             $image = time().'.'.$images->getClientOriginalExtension();
             $destinationPath = public_path('/image/category'); //public path folder dir
             $images->move($destinationPath, $image);  //move to destination you mentioned 
             $data['image']=$image;
         } 

         $data['category_slug'] = Str::slug($request->input('category_name'));

        
        $category = Category::find($id);
        $category -> update($data);

        return redirect ('admin/category')->with('success',"Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category= Category::find($id);
        $category->delete();

        return redirect ('admin/category')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $category = Category::find($id);
        $category->status =   $status ? $status : 0;
        $category->save();
        echo json_encode(array("status" => 1));
    }
}
