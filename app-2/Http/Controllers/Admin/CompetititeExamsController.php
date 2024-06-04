<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompetititeExams;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompetititeExamsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = CompetititeExams::all();

        return view ('admin.competitite_exams.index',compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CompetititeExams::all();
        return view ('admin.competitite_exams.create',compact('categories'));
    }

    /**
     * competitite_exam a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate ($request, [
            'exam_name'  => 'required',
            'description'       => 'required',
        ]);

        $data = $request->all();

        $data['slug'] = Str::slug($request->input('exam_name'));

        if ($request->hasfile('image_name'))
        {  //check file present or not
            $images = $request->file('image_name'); //get the file
            $image = time().'.'.$images->getClientOriginalExtension();
            $destinationPath = public_path('/image/exams'); //public path folder dir
            $images->move($destinationPath, $image);  //mve to destination you mentioned 
            $data['image_name']=$image;
        } 

        $competitite_exam = new CompetititeExams();
        $competitite_exam = CompetititeExams::create($data);

        return redirect ('admin/competitite-exam')->with('success',"Added Successfully");
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
        $competitite_exam = CompetititeExams::find($id);

        return view ('admin.competitite_exams.edit',compact('competitite_exam','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate ($request, [
            'exam_name'  => 'required',
            'description'       => 'required',
        ]);
        $data = $request->all();
         if ($request->hasfile('image_name'))
        {  //check file present or not
            $images = $request->file('image_name'); //get the file
            $image = time().'.'.$images->getClientOriginalExtension();
           // echo $image;die;
            $destinationPath = public_path('/image/exams'); //public path folder dir
            $images->move($destinationPath, $image);  //mve to destination you mentioned 
            $data['image_name']=$image;
        } 


        

        $competitite_exam = CompetititeExams::find($id);
        $competitite_exam -> update($data);

        return redirect ('admin/competitite-exam')->with('success',"Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $competitite_exam= CompetititeExams::find($id);
        $competitite_exam->delete();

        return redirect ('admin/competitite-exam')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $competitite_exam = CompetititeExams::find($id);
        $competitite_exam->status =   $status ? $status : 0;
        $competitite_exam->save();
        echo json_encode(array("status" => 1));
    }
}
