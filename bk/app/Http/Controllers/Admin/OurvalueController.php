<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ourvalue;
use Illuminate\Http\Request;

class OurvalueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Ourvalue::all();

        return view ('admin.ourvalues.index',compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('admin.ourvalues.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate($request,[
            'title' => 'required',
            'image' => 'required',
            'description' => 'required'
        ]);

        $data = $request->all();

        if ($request->hasfile('image'))
        {  //check file present or not
            $images = $request->file('image'); //get the file
            $image = time().'.'.$images->getClientOriginalExtension();
            $destinationPath = public_path('/image/our-values'); //public path folder dir
            $images->move($destinationPath, $image);  //mve to destination you mentioned 
            $data['image']=$image;
        } 

        $ourvalues = new Ourvalue;
        $ourvalues = Ourvalue::create($data);

        return redirect ('admin/our-values');

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
        $result = Ourvalue::find($id);

        return view ('admin.ourvalues.edit',compact('result','id'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate($request,[
            'title' => 'required',
            'description' => 'required'
        ]);

        $data = $request->all();

        if ($request->hasfile('image'))
        {  //check file present or not
            $images = $request->file('image'); //get the file
            $image = time().'.'.$images->getClientOriginalExtension();
            $destinationPath = public_path('/image/our-values'); //public path folder dir
            $images->move($destinationPath, $image);  //mve to destination you mentioned 
            $data['image']=$image;
        } 

        $ourvalues = Ourvalue::find($id);
        $ourvalues -> update($data);

        return redirect ('admin/our-values')->with('sucess','Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ourvalues = Ourvalue::find($id);
        $ourvalues -> delete();

        return view ('admin/our-values')->with('error','Deleted Successfully');

    }
}
