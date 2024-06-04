<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FreeSession;
use App\Models\State;

class FreeSessionController extends Controller
{
    public function index()
    {
        $result = FreeSession::select('freesessions.*','states.state_name')
        ->leftJoin('states', 'states.id', '=', 'freesessions.state_id')
        ->get();

        return view ('admin.freesession.index',compact('result'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name'      => 'required',
            'user_email'      => 'required',
            'user_phone'      => 'required',
            'message'         => 'required', 
        ]);
       
        $data = $request ->all();       
        
        $freesession = new FreeSession;
        $freesession = FreeSession::create($data);

        return redirect ('/contact')->with('success','Your message has been sent successfully');
    }

    public function show(string $id)
    {
        $result = FreeSession::find($id);

        $state = State::where('id', $result->state_id)->first();

        return view('admin.freesession.show',compact('result','id','state'));
        
    }

    public function destroy(string $id)
    {
        $freesession = FreeSession::find($id    );
        $freesession -> delete();
 
        return redirect ('admin/freesession')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $freesession = FreeSession::find($id);
        $freesession->status =   $status ? $status : 0;
        $freesession->save();
        echo json_encode(array("status" => 1));
    }
}
