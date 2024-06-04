<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAddress;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Student::all();

        return view ('admin.student.index',compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::all();
        return view ('admin.student.create',compact('students'));
    }

    /**
     * student a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate ($request, [
            'first_name'    => 'required',
            'last_name'     => 'required',
            // 'student_image' => 'required',
            'email'         => 'required|email|unique:students,email',
            'phone'         => 'required|unique:students,phone',
        ]);

        $data = $request->all();

        if ($request->hasfile('student_image'))
         {  //check file present or not
             $images = $request->file('student_image'); //get the file
             $image = time().'.'.$images->getClientOriginalExtension();
             $destinationPath = public_path('/image/student'); //public path folder dir
             $images->move($destinationPath, $image);  //mve to destination you mentioned 
             $data['student_image']=$image;
         } 
   
            $student['student_image'] = $data['student_image'];
            $student['first_name']    = $data['first_name'];
            $student['last_name']     = $data['last_name'];
            $student['phone']         = $data['phone'];
            $student['email']         = $data['email'];

            $student = new student;
            $student = Student::create($student);

            $address['student_id']     = $student->id;
            $address['address_lane1']  = $data['address_lane1'];
            $address['address_lane2']  = $data['address_lane2'];
            $address['pincode']        = $data['post_code'];
            $address['city']           = $data['city'];
            $address['state']          = $data['state'];

            $student_address = new StudentAddress;
            $student_address = StudentAddress::create($address);

        return redirect ('admin/student')->with('success',"Added Successfully");
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
        $student = Student::find($id);

        return view ('admin.student.edit',compact('student','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $this -> validate ($request, [
            'first_name'    => 'required',
            'last_name'     => 'required',
            // 'student_image' => 'required',
            'email'            => ['required','email',Rule::unique('students')->ignore($id)],
            'phone'            => ['required',Rule::unique('students')->ignore($id)],
        ]);

        $data = $request->all();

        if ($request->hasfile('student_image'))
         {  //check file present or not
             $images = $request->file('student_image'); //get the file
             $image = time().'.'.$images->getClientOriginalExtension();
             $destinationPath = public_path('/image/student'); //public path folder dir
             $images->move($destinationPath, $image);  //move to destination you mentioned 
             $data['student_image']=$image;
         } 

        
        $student = Student::find($id);
        $student -> update($data);

        return redirect ('admin/student')->with('success',"Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student= Student::find($id);
        $student->delete();

        return redirect ('admin/student')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $student = Student::find($id);
        $student->status =   $status ? $status : 0;
        $student->save();
        echo json_encode(array("status" => 1));
    }
}
