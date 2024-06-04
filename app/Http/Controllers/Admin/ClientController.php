<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
/**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Client::all();
        
        return view ('admin.client.index',compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::all();
        return view ('admin.client.create',compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate ($request,[
            'first_name'    => 'required',
            'phone'         => 'required|numeric|unique:clients,phone',
            'email'         => 'required|email|unique:clients,email',
            'country_id'    => 'required',
            'state_id'      => 'required',
            'district'      => 'required',
            'place'         => 'required',
            'pincode'       => 'required'
        ]);

        $data = $request ->all();

        $client = new Client;
        $client = Client::create($data);

        return redirect('admin/client')->with('success',"Added Successfully");
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
        $client = Client::find($id);
        $countries = Country::all();
        $states = State::where('country_id',$client->country_id)->get();

        return view ('admin.client.edit',compact('client','id','countries','states'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate ($request, [
            'first_name'    => 'required',
            'phone'         => ['required','numeric',Rule::unique('clients')->ignore($id)],
            'email'         => ['required','email',Rule::unique('clients')->ignore($id)],
            'country_id'    => 'required',
            'state_id'      => 'required',
            'district'      => 'required',
            'place'         => 'required',
            'pincode'       => 'required'
         ]);
 
         $data = $request ->all();

         
         $client = Client::find($id);
         $client -> update($data);
 
         return redirect ('admin/client')->with('success',"updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client= Client::find($id);
        $client -> delete();

        return redirect ('admin/client')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $client = Client::find($id);
        $client->status =   $status ? $status : 0;
        $client->save();
        echo json_encode(array("status" => 1));
    }
}
