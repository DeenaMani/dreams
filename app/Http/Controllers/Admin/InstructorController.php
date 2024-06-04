<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Instructor::all();

        return view ('admin.instructor.index',compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $instructors = Instructor::all();
        return view ('admin.instructor.create',compact('instructors'));
    }

    /**
     * instructor a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate ($request, [
            'instructor_type'  => 'required',
            'instructor_name'  => 'required',
            'instructor_image' => 'required',
            'subject'          => 'required',
            'email'            => 'required|email|unique:instructors,email',
            'phone'            => 'required|unique:instructors,phone',
        ],[

            'instructor_type.required'  => 'The teacher type field is required.',
            'instructor_name.required'  => 'The teacher name field is required.',
            'instructor_image.required' => 'The teacher image field is required.',

        ]);

        $data = $request->all();

        if ($request->hasfile('instructor_image'))
         {  //check file present or not
             $images = $request->file('instructor_image'); //get the file
             $image = time().'.'.$images->getClientOriginalExtension();
             $destinationPath = public_path('/image/instructor'); //public path folder dir
             $images->move($destinationPath, $image);  //mve to destination you mentioned 
             $data['instructor_image']=$image;
         } 
    

        $instructor = new Instructor;
        $instructor = Instructor::create($data);

        return redirect ('admin/instructor')->with('success',"Added Successfully");
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
        $instructor = Instructor::find($id);

        return view ('admin.instructor.edit',compact('instructor','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $this -> validate ($request, [
            'instructor_type'  => 'required',
            'instructor_name'  => 'required',
            'subject'          => 'required',
            'email'            => ['required','email',Rule::unique('instructors')->ignore($id)],
            'phone'            => ['required',Rule::unique('instructors')->ignore($id)],
        ],[

            'instructor_type.required'  => 'The teacher type field is required.',
            'instructor_name.required'  => 'The teacher name field is required.',
            'instructor_image.required' => 'The teacher image field is required.',

        ]);

        $data = $request->all();

        if ($request->hasfile('instructor_image'))
         {  //check file present or not
             $images = $request->file('instructor_image'); //get the file
             $image = time().'.'.$images->getClientOriginalExtension();
             $destinationPath = public_path('/image/instructor'); //public path folder dir
             $images->move($destinationPath, $image);  //move to destination you mentioned 
             $data['instructor_image']=$image;
         } 

        
        $instructor = Instructor::find($id);
        $instructor -> update($data);

        return redirect ('admin/instructor')->with('success',"Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $instructor= Instructor::find($id);
        $instructor->delete();

        return redirect ('admin/instructor')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $instructor = Instructor::find($id);
        $instructor->status =   $status ? $status : 0;
        $instructor->save();
        echo json_encode(array("status" => 1));
    }
}
