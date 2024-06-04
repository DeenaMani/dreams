<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\State;
use App\Models\StudentAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $states   = State::all();
        return view ('admin.student.create',compact('students','states'));
    }

    /**
     * student a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate ($request, [
            'first_name'          => 'required',
            'last_name'           => 'required',
            'email'               => 'required|email|unique:students,email',
            'phone'               => 'required|unique:students,phone',
            'password'            => 'required|min:5',
            'confirm_password'    => 'required|same:password'
        ]);

        $data = $request->all();

        $data['password'] = Hash::make($request->password);

        $data = $request->except('confirm_password');
        
        $student = new Student;
        $student = Student::create($data);

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

        $states  = State::all(); 

        return view ('admin.student.edit',compact('student','id','states'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $this -> validate ($request, [
            'first_name'          => 'required',
            'last_name'           => 'required',
            'email'            => ['required','email',Rule::unique('instructors')->ignore($id)],
            'phone'            => ['required',Rule::unique('instructors')->ignore($id)],
        ]);

        $data = $request->all();
        
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
