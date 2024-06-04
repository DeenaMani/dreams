<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Resource::all();

        return view ('admin.resource.index',compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('admin.resource.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate($request, [
            'category' => 'required',
            'course'   => 'required',
            'topic'    => 'required',
        ]);

        $data = $request -> all();

        $resources = new Resource;
        $resources = Resource::create($data);

        return redirect ('admin/resource')->with('sucess','Add Successfully');

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
        $result = Resource::find($id);

        return view ('admin.resources.edit',compact('result','id'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $this -> validate($request, [
            'category' => 'required',
            'course'   => 'required',
            'topic'    => 'required',
        ]);

        $data = $request -> all();

        $resources = Resource::find($id);
        $resources -> update($data);

        return redirect ('admin/resource')->with('success','Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $ourvalues = Resource::find($id);
        $ourvalues -> delete();

        return view ('admin/our-values')->with('error','Deleted Successfully');

    }
}
