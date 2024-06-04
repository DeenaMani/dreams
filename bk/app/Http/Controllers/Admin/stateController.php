<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\state;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = State::select('states.*','countries.country_name')
                   ->leftjoin('countries','countries.id','=','states.country_id')->get();

        return view ('admin.state.index',compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::all();
        return view ('admin.state.create',compact('countries'));
    }

    /**
     * state a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate ($request, [
            'state_name'        => 'required',
            'country_id'        => 'required',
        ]);
        

        $data = $request->all();

        $state = new State;
        $state = State::create($data);

        return redirect ('admin/state')->with('success',"Added Successfully");
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
        $state =  State::find($id);
        
        $countries = Country::all();

        return view ('admin.state.edit',compact('state','id','countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate ($request, [
            'state_name'        => 'required',
            'country_id'        => 'required',
        ]);
        

        $data = $request->all();

        $state = State::find($id);
        $state -> update($data);


        return redirect ('admin/state')->with('success',"Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $state= State::find($id);
        $state->delete();

        return redirect ('admin/state')->with('error',"Deleted Successfully");
    }

    public function status($id, $status)
    {
        //echo $id." ".$status;
        $state = State::find($id);
        $state->status =   $status ? $status : 0;
        $state->save();
        echo json_encode(array("status" => 1));
    }

    public function get_state($id) {

        $data = State::where('country_id', $id)->get();

        return response()->json($data);

    }


         
}
