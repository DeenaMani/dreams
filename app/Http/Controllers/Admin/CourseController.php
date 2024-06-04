<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Course::select('courses.*','categories.category_name')
                  ->leftJoin('categories', 'categories.id', '=', 'courses.category_id')
                  ->get();

        // $categories = collect([]);
        // foreach ($results as $course) {
        //     $categoryNames = Category::whereIn('id', json_decode($course->category_id))->pluck('category_name')->toArray();
        //     $categories->push(['course_id' => $course->id, 'category_names' => $categoryNames]);
        // }

        // $categories = [];
        // foreach ($results as $course) {
        //     $categoryIds = json_decode($course->category_id ?? '[]');
        //     $categories[$course->id] = Category::whereIn('id', $categoryIds)->get();
        // }

        return view ('admin.course.index', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        $instructors = Instructor::get();

        return view ('admin.course.create',compact('categories','instructors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'category_id'         => 'required',
            'course_name'         => 'required',
            'course_image'        => 'required',
            'validity'            => 'required',
            'instructor_id'       => 'required',
            'language'            => 'required',
            'description'         => 'required',
        ]);

        $data = $request->all();

        if ($request->hasfile('course_image'))
        {  //check file present or not
            $images = $request->file('course_image'); //get the file
            $image = time().'.'.$images->getClientOriginalExtension();
            $destinationPath = public_path('/image/course'); //public path folder dir
            $images->move($destinationPath, $image);  //move to destination you mentioned 
            $data['course_image']=$image;
        } 

        $data['instructor_id'] = json_encode($request->input('instructor_id'));
        // $data['category_id'] = json_encode($request->input('category_id'));
        $data['slug'] = Str::slug($request->input('course_name'));

        $course = new Course;
        $course = Course::create($data);

        return redirect ('admin/course')->with('success','Added Successfully');
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
        $course = Course::find($id);
        $instructors = Instructor::get();
        $categories = Category::get();

        return view ('admin.course.edit', compact('course','id','categories','instructors'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request,[
            'category_id'         => 'required',
            'course_name'         => 'required',
            'instructor_id'       => 'required',
            'language'            => 'required',
            'description'         => 'required',
        ]);

        $data = $request->all();

        if ($request->hasfile('course_image'))
        {  //check file present or not
            $images = $request->file('course_image'); //get the file
            $image = time().'.'.$images->getClientOriginalExtension();
            $destinationPath = public_path('/image/course'); //public path folder dir
            $images->move($destinationPath, $image);  //move to destination you mentioned 
            $data['course_image']=$image;
        } 

        
        
        $data['instructor_id'] = json_encode($request->input('instructor_id'));
        // $data['category_id'] = json_encode($request->input('category_id'));
        $data['slug'] = Str::slug($request->input('course_name'));

        $course = Course::find($id);
        $course -> update($data);

        return redirect ('admin/course')->with('success','Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course= Course::find($id);
        $course->delete();

        return redirect ('admin/course')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $course = Course::find($id);
        $course->status =   $status ? $status : 0;
        $course->save();
        echo json_encode(array("status" => 1));
    }

    public function getcourse($id) {

        $data = Course::where('category_id', $id)->get();

       
      
        return response()->json($data);

    }


    
    // public function getcourse(Request $request)
    // {
    //     $data = $request->all();
    // $categoryId = $data['category_ids'];


    // if (!empty($categoryId)) {
    //     $courses = Course::whereJsonContains('category_id', $categoryId)->get();

    //     return response()->json($courses);
    // } else {
    //     return response()->json([]);
    // }

    // }
}
