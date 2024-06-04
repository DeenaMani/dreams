<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Course;
use Session;
use Hash;

class CoursesController extends Controller
{

    public function index(Request $request)
    {
        $title ="Courses";
        $description ="Courses";
        $results = Category::where('status',1)->with('course')->get();
        return view('frontend.courses.index',compact('title','description','results'));
    }

    public function courseDetails(Request $request,$slug,$slug2="")
    {
        
        $category = Category::where('status',1)->where('category_slug',$slug)->first();
        if($category){
            $title = $category->category_name; 
            $description = $category->category_name;
            $courses = Course::where('category_id',$category->id)->where('status',1)->get();
            return view('frontend.courses.categoryDetails',compact('title','description','courses','category','slug2'));             
        }
        else{
            
        }
        
    }


    public function resource(Request $request)
    {
        
        $title ="Courses";
        $description ="Courses";
        $categorys = Category::where('status',1)->get();
        return view('frontend.courses.resource',compact('title','description','categorys'));  
    }

    
}
