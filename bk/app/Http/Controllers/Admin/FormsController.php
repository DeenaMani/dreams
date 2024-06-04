<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Forms;
use Illuminate\Http\Request;

class FormsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = Forms::all();
        return view ('admin.forms.index',compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.forms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate($request, [
            'form_title' => 'required',
            'form_description' => 'required',
        ]);

        $data = $request->all();

        $form = new Forms;
        $form = Forms::create($data);

        return redirect('admin/form')->with('success',"Added Successfully");
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
        $form = Forms::find($id);
        return view ('admin.forms.edit',compact('form','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate($request, [
            'form_title' => 'required',
            'form_description' => 'required',
        ]);

        $data = $request->all();

        $form = Forms::find($id);
        $form -> update($data);

        return redirect('admin/form')->with('success',"Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $form=Forms::find($id);
        $form -> delete();

        return redirect('admin/form')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $forms = Forms::find($id);
        $forms->status =   $status ? $status : 0;
        $forms->save();
        echo json_encode(array("status" => 1));
    }
}
