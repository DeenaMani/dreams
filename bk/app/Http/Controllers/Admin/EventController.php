<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
/**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = Event::all();
        
        return view ('admin.event.index',compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('admin.event.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate ($request,[
            'title'         => 'required',
            'description'   => 'required',
            'type'          => 'required',
            'date'          => 'required',
            'meeting_link'  => 'required|url',
        ]);

        $data = $request ->all();

        $event = new Event;
        $event = Event::create($data);

        return redirect('admin/event')->with('success',"Added Successfully");
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
        $event = Event::find($id);
        return view ('admin.event.edit',compact('event','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate ($request, [
            'title'         => 'required',
            'description'   => 'required',
            'type'          => 'required',
            'date'          => 'required',
            'meeting_link'  => 'required|url',
         ]);
 
         $data = $request ->all();

         
         $event = Event::find($id);
         $event -> update($data);
 
         return redirect ('admin/event')->with('success',"updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event= Event::find($id);
        $event -> delete();

        return redirect ('admin/event')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $event = Event::find($id);
        $event->status =   $status ? $status : 0;
        $event->save();
        echo json_encode(array("status" => 1));
    }
}
