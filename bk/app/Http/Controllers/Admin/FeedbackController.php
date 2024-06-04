<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        $result = Feedback::all();
        
        return view ('admin.feedback.index',compact('result'));
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
        
        $feedback = new Feedback;
        $feedback = Feedback::create($data);

        return redirect ('/contact')->with('success','Your message has been sent successfully');
    }

    public function show(string $id)
    {
        $result = Feedback::find($id);

        return view('admin.feedback.show',compact('result','id'));
        
    }

    public function destroy(string $id)
    {
        $feedback = Feedback::find($id);
        $feedback -> delete();
 
        return redirect ('admin/feedback')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $feedback = Feedback::find($id);
        $feedback->status =   $status ? $status : 0;
        $feedback->save();
        echo json_encode(array("status" => 1));
    }
}
