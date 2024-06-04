<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Liveclass;
use App\Models\Topic;
use Illuminate\Http\Request;

class LiveclassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Liveclass::get();

        return view ('admin.liveclass.index',compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view ('admin.liveclass.create',compact('categories'));
    }

    /**
     * liveclass a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate ($request, [
            'category_id'   => 'required',
            'course_id'     => 'required',
            'topic'      => 'required',
            'exam_type'     => 'required',
            'meeting_link'  => 'required|url',
            'date'          => 'required',
            'time'          => 'required',
        ]);

        $data = $request->all();

        // $data['date_time'] = $request->date . ' ' . $request->time;

        $liveclass = new Liveclass;
        $liveclass = Liveclass::create($data);

        return redirect ('admin/live-class')->with('success',"Added Successfully");
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
        $liveclass = Liveclass::find($id);
        $categories = Category::all();

        $category_id = $liveclass->category_id; 

        $courses = Course::where('category_id', $category_id)->get();

        // $category_id = json_decode($category_id, true);
        
        // $courses = Course::whereJsonContains('category_id',(string) $category_id)->get();

        return view ('admin.liveclass.edit',compact('liveclass','id','categories','courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate ($request, [
            'category_id'   => 'required',
            'course_id'     => 'required',
            'topic_id'      => 'required',
            'exam_type'     => 'required',
            'meeting_link'  => 'required|url',
            'date'          => 'required',
            'time'          => 'required',
        ]);

        $data = $request->all();

        // $data['date_time'] = $request->date . ' ' . $request->time;
       
        $liveclass = Liveclass::find($id);
        $liveclass -> update($data);

        return redirect ('admin/live-class')->with('success',"Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $liveclass= Liveclass::find($id);
        $liveclass->delete();

        return redirect ('admin/live-class')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $liveclass = Liveclass::find($id);
        $liveclass->status =   $status ? $status : 0;
        $liveclass->save();
        echo json_encode(array("status" => 1));
    }
}
