<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Resource;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Topic::select('topics.*','categories.category_name','courses.course_name')
                  ->leftjoin('courses', 'courses.id', '=' , 'topics.course_id')
                  ->leftJoin('categories', 'categories.id', '=', 'courses.category_id')
                  ->get();

        return view ('admin.topic.index', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $courses = Course::select('courses.*','categories.category_name')
                    ->leftJoin('categories', 'categories.id', '=', 'courses.category_id')
                    ->orderBy('courses.course_name','asc')
                   // ->groupBy('courses.id')
                    ->get();

        return view ('admin.topic.create',compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'course_id'        => 'required',
            'topic'            => 'required',
          //  'duration'         => 'required',
         'resource_type'    => 'required'
        ]);

        $data = $request->all();

        if ($request->hasfile('pdf'))
        {  //check file present or not
            $images = $request->file('pdf'); //get the file
            $image = time().'.'.$images->getClientOriginalExtension();
            $destinationPath = public_path('/pdf/topic'); //public path folder dir
            $images->move($destinationPath, $image);  //move to destination you mentioned 
            $data['pdf']=$image;
        } 

        $data['type'] = $data['resource_type'];
        unset($data['resource_type']);

        $topic = new Topic;
        $topic = Topic::create($data);

        return redirect ('admin/topic')->with('success','Added Successfully');
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

        $courses = Course::select('courses.*','categories.category_name')
                    ->leftJoin('categories', 'categories.id', '=', 'courses.category_id')
                    ->orderBy('courses.course_name','asc')
                   // ->groupBy('courses.id')
                    ->get();
        $result = Topic::find($id);
        return view ('admin.topic.edit',compact('courses','result'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request,[
           // 'category_id'      => 'required',
            'course_id'        => 'required',
            'topic'            => 'required',
            //'duration'         => 'required',
            'resource_type'    => 'required',
        ]);

        $data = $request->all();

        if ($request->hasfile('pdf'))
        {  //check file present or not
            $images = $request->file('pdf'); //get the file
            $image = time().'.'.$images->getClientOriginalExtension();
            $destinationPath = public_path('/pdf/topic'); //public path folder dir
            $images->move($destinationPath, $image);  //move to destination you mentioned 
            $data['pdf']=$image;
        } 

        $data['type'] = $data['resource_type'];
        unset($data['resource_type']);

        $topic = Topic::find($id);
        $topic -> update($data);

        return redirect ('admin/topic')->with('success','Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $topic = Topic::find($id);
        $topic->delete();

        return redirect ('admin/topic')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $topic = Topic::find($id);
        $topic->status =   $status ? $status : 0;
        $topic->save();
        echo json_encode(array("status" => 1));
    }

    public function gettopic($id) {

        $data = Topic::where('course_id', $id)->get();

        $topics = $data->map(function ($topic) {
            return [
                'id' => $topic->id,
                'topic_name' => $topic->topic,
            ];
        });
      
        return response()->json($topics);

    }

    public function addresource($id) {
        $results = Resource::where('resources.topic_id',$id)->get();
       // pr($results);die;
        $topic = Topic::where('topics.id',$id)->first();
        return view ('admin.topic.resource',compact('results','id','topic'));
    }

     public function resourceStatus($id,$status)
    {
        //echo $id." ".$status;
        $topic = Resource::find($id);
        $topic->is_free =   $status ? $status : 0;
        $topic->save();
        echo json_encode(array("status" => 1));
    }

    public function resource_get()
    {
        $resources = Resource::all();
        return response()->json($resources);
    }

    public function resource_store(Request $request)
    {
        // Validate request data
        $request->validate([
            'title'      => 'required',
            'video_link' => 'nullable|url',
            'pdf'        => 'nullable|file|mimes:pdf',
        ]);

        $data = $request->all();

        if ($request->hasfile('pdf'))
        {  //check file present or not
            $images = $request->file('pdf'); //get the file
            $image = time().'.'.$images->getClientOriginalExtension();
            $destinationPath = public_path('/pdf/topic'); //public path folder dir
            $images->move($destinationPath, $image);  //move to destination you mentioned 
            $data['pdf']=$image;
        } 

        $resource = new Resource;
        $resource = Resource::create($data);

        return back()->with('success','Added Successfully');
    }

    public function delete($id)
    {
            $resource = Resource::find($id);
            if (!$resource) {
                return  back()->with('error',"Resource Error delete");
            }

            $resource->delete();

            return  back()->with('error', 'Resource deleted successfully');
    }

    public function resource_edit($id) {

        $data = Resource::find($id);   
        return response()->json($data);

    }

    public function resource_update(Request $request, $id) {

        $request->validate([
            'title'      => 'required',
            'video_link' => 'nullable|url',
            'pdf'        => 'nullable|file|mimes:pdf',
        ]);

        $data = $request->all();

        if ($request->hasfile('pdf'))
        {  //check file present or not
            $images = $request->file('pdf'); //get the file
            $image = time().'.'.$images->getClientOriginalExtension();
            $destinationPath = public_path('/pdf/topic'); //public path folder dir
            $images->move($destinationPath, $image);  //move to destination you mentioned 
            $data['pdf']=$image;
        } 

        $resource = new Resource;
        $resource -> update($data);

        return  back()->with('success', 'Resource Updated successfully');

    }

}
