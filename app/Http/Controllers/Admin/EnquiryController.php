<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enquiry;
use App\Models\State;

class EnquiryController extends Controller
{
    public function index()
    {
        $result = Enquiry::all();
        
        return view ('admin.enquiry.index',compact('result'));
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
        
        $enquiry = new Enquiry;
        $enquiry = Enquiry::create($data);

        return redirect ('/contact')->with('success','Your message has been sent successfully');
    }

    public function show(string $id)
    {
        $result = Enquiry::find($id);
        $state = State::where('id', $result->state_id)->first();


        return view('admin.enquiry.show',compact('result','id','state'));
        
    }

    public function destroy(string $id)
    {
        $enquiry = Enquiry::find($id);
        $enquiry -> delete();
 
        return redirect ('admin/enquiry')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $enquiry = Enquiry::find($id);
        $enquiry->status =   $status ? $status : 0;
        $enquiry->save();
        echo json_encode(array("status" => 1));
    }
}
