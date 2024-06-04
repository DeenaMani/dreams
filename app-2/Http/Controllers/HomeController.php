<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\About;
use App\Models\Ourvalue;
use App\Models\Faq;
use App\Models\Instructor;
use App\Models\Whychoose;
use App\Models\CompetititeExams;
use App\Models\Liveclass;
use App\Models\Setting;
use Session;
use Hash;
use DB;

class HomeController extends Controller
{
    //

    public function index(Request $request)
    {


        // if(empty(Session::get('userToken'))) {
        //     $token = time().rand(10000000000,9000000000);
        //     Session::put('userToken',$token);

        // }
        // echo Session::get('userToken');   die;


        $banners = Banner::where('status',1)->get();
        $teachers = Instructor::where('status',1)->where('instructor_type',1)->get();
        $mentors = Instructor::where('status',1)->where('instructor_type',2)->get();
        $whychooses = Whychoose::where('status',1)->get();
        $about = About::first();
        $ourvalues = Ourvalue::all();
        //pr($banner);die;
        return view('frontend.index',compact('banners','mentors','teachers','whychooses','about','ourvalues'));
    }

    public function competitive(Request $request)
    {
        $title = "Competitive" ;
        $description = "Competitive" ;
        $competitives = CompetititeExams::where('status',1)->orderBy('created_at', 'desc')->get();
        return view('frontend.competitive',compact('competitives','title','description'));
    }

    public function competitiveDetails(Request $request,$slug)
    {
        $title = "Competitive" ;
        $description = "Competitive" ;
        $competitive = CompetititeExams::where('status',1)->where('slug',$slug)->first();
        $competitives = CompetititeExams::where('status',1)->orderBy('created_at', 'desc')->where('slug','!=',$slug)->get();
        return view('frontend.competitiveDetails',compact('competitive','competitives','title','description'));
    }

    public function schedules(Request $request)
    {
        $title = "Live Class" ;
        $description = "Live Class" ;
        $schedules = Liveclass::select('liveclasses.*','courses.course_name')
                            ->where('liveclasses.status',1)
                            ->where('liveclasses.class_date','>=',date('Y-m-d H:i:s'))
                            ->leftJoin('courses', function($join) {
                                $join->on('courses.id', '=', 'liveclasses.course_id');
                            })
                            ->get();
        //pr($schedules);die;
        return view('frontend.schedules',compact('schedules','title','description'));
    }
    

     public function contact(Request $request)
    {
        $title = "Contact Us" ;
        $description = "Contact Us" ;
        $setting = Setting::first();
        return view('frontend.contact',compact('title','description','setting'));
    }

     public function about(Request $request)
    {
        $title = "About Us" ;
        $about = About::first();
        $ourvalues = Ourvalue::all();
        $faqs = Faq::limit(6)->get();

        return view('frontend.about',compact('title','about','ourvalues','faqs'));
    }
    
     public function pages(Request $request)
    {
        $slug =  last(request()->segments());
        if($slug == "terms-conditions") $id = 1;
        if($slug == "privacy-policy") $id = 2;
        $content = DB::table('terms')->where('id',$id)->first();
        $title = $content->title ;
        $description = $content->title ;
        return view('frontend.terms',compact('title','description','content'));
    }

     public function faq(Request $request)
    {
        $title = "FAQ" ;
        $description = "FAQ" ;
        $contents = DB::table('faqs')->get();
        return view('frontend.faqs',compact('title','description','contents'));
    }

    public function bookSession(Request $request)
    {
        $title = "Contact Us" ;
        $description = "Contact Us" ;
        $setting = Setting::first();
        return view('frontend.bookSession',compact('title','description','setting'));
    }

}
