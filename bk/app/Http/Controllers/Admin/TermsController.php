<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Terms;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Terms::all();

        return view ('admin.terms.index',compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $terms = Terms::all();
        return view ('admin.terms.create',compact('terms'));
    }

    /**
     * terms a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate ($request, [
            'title'            => 'required',
            'full_description' => 'required',
        ]);

        $data = $request->all();
    

        $terms = new Terms;
        $terms = Terms::create($data);

        return redirect ('admin/terms')->with('success',"Added Successfully");
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
        $terms = Terms::find($id);

        return view ('admin.terms.edit',compact('terms','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate ($request, [
            'title'            => 'required',
            'full_description' => 'required',
        ]);

        $data = $request->all();

        $terms = Terms::find($id);
        $terms -> update($data);

        return redirect ('admin/terms')->with('success',"Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $terms= Terms::find($id);
        $terms->delete();

        return redirect ('admin/terms')->with('error',"Deleted Successfully");
    }
}
