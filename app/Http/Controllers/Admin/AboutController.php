<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\About;

class AboutController extends Controller
{
/**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = About::all();
        
        return view ('admin.about.index',compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('admin.about.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate ($request,[
            'title'       => 'required',
            'image'       => 'required',
            'about_description' => 'required',
            'who_we_are'        => 'required',
            'what_we_do'        => 'required',
        ]);

        $data = $request ->all();

        if ($request->hasfile('image'))
        {  //check file present or not
            $images = $request->file('image'); //get the file
            $image = time().'.'.$images->getClientOriginalExtension();
            $destinationPath = public_path('/image/about'); //public path folder dir
            $images->move($destinationPath, $image);  //mve to destination you mentioned 
            $data['image']=$image;
        } 

        $about = new About;
        $about = About::create($data);

        return redirect('admin/about');
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
        $about = About::find($id);
        return view ('admin.about.edit',compact('about','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate ($request, [
            'title' => 'required',
            'about_description' => 'required',
            'who_we_are'        => 'required',
            'what_we_do'        => 'required',
         ]);
 
         $data = $request ->all();

         if ($request->hasfile('image'))
         {  //check file present or not
             $images = $request->file('image'); //get the file
             $image = time().'.'.$images->getClientOriginalExtension();
             $destinationPath = public_path('/image/about'); //public path folder dir
             $images->move($destinationPath, $image);  //mve to destination you mentioned 
             $data['image']=$image;
         } 

         
         $about =About::find($id);
         $about -> update($data);
 
         return redirect ('admin/about')->with('success',"updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $about=About::find($id);
        $about -> delete();

        return redirect ('admin/about')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $about = About::find($id);
        $about->status =   $status ? $status : 0;
        $about->save();
        echo json_encode(array("status" => 1));
    }
}
public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'otp' => 'required|integer',
        ]);

        $otpRecord = Otp::where('email', $request->email)
                        ->where('otp', $request->otp)
                        ->where('expires_at', '>', Carbon::now())
                        ->first();

        if ($otpRecord) {
            return response()->json(['message' => 'OTP verified successfully.']);
        }

        return response()->json(['message' => 'Invalid or expired OTP.'], 400);
    }

    @extends('layouts.app')

@section('title', 'OTP Verification')

@section('content')
    <form id="verifyForm">
        <input type="email" name="email" placeholder="Enter your email" required>
        <div id="verifyEmailError" class="error-message"></div>
        <input type="number" name="otp" placeholder="Enter OTP" required>
        <div id="otpError" class="error-message"></div>
        <button type="button" id="verifyOtpBtn">Verify OTP</button>
    </form>
@endsection

@section('scripts')
    <script>
        // Set the CSRF token directly in JavaScript
        var csrfToken = '{{ csrf_token() }}';

        $(document).ready(function() {
            function clearErrors() {
                $('#verifyEmailError').text('');
                $('#otpError').text('');
            }

            $('#verifyOtpBtn').click(function() {
                clearErrors();
                $.ajax({
                    url: '/verify-otp',
                    method: 'POST',
                    data: $('#verifyForm').serialize(), // Serialize the form data
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Set the CSRF token here
                    },
                    success: function(response) {
                        location.reload(); // Refresh the page on success
                    },
                    error: function(response) {
                        if (response.responseJSON.errors) {
                            if (response.responseJSON.errors.email) {
                                $('#verifyEmailError').text(response.responseJSON.errors.email[0]);
                            }
                            if (response.responseJSON.errors.otp) {
                                $('#otpError').text(response.responseJSON.errors.otp[0]);
                            }
                        } else {
                            $('#otpError').text(response.responseJSON.message);
                        }
                    }
                });
            });
        });
    </script>
@endsection
