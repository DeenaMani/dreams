<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Financial;
use Illuminate\Validation\Rule;

class FinancialController extends Controller
{
/**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = Financial::all();
        
        return view ('admin.financial.index',compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('admin.financial.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate ($request,[
            'year'         => 'required|numeric|digits:4|unique:financials,year',
            'total_amount' => 'required|numeric',
        ]);

        $data = $request ->all();

        $financial = new Financial;
        $financial = Financial::create($data);

        return redirect('admin/financial')->with('success',"Added Successfully");
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
        $financial = Financial::find($id);
        return view ('admin.financial.edit',compact('financial','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate ($request, [
            'year'         => ['required','numeric','digits:4',Rule::unique('financials')->ignore($id)],
            'total_amount' => 'required|numeric',
         ]);
 
         $data = $request ->all();

         
         $financial =Financial::find($id);
         $financial -> update($data);
 
         return redirect ('admin/financial')->with('success',"updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $financial=Financial::find($id);
        $financial -> delete();

        return redirect ('admin/financial')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $financial = Financial::find($id);
        $financial->status =   $status ? $status : 0;
        $financial->save();
        echo json_encode(array("status" => 1));
    }
}
