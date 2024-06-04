<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\About;
use App\Models\Ourvalue;
use App\Models\Faq;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\Course;
use App\Models\Whychoose;
use App\Models\CompetititeExams;
use App\Models\CompetititeExamPdf;
use App\Models\ContactForm;
use App\Models\Enquiry;
use App\Models\Feedback;
use App\Models\FreeSession;
use App\Models\Liveclass;
use App\Models\Setting;
use App\Models\State;
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
        $competitives = CompetititeExams::where('status',1)->where('exam_type',1)->orderBy('created_at', 'desc')->get();
        $others = CompetititeExams::where('status',1)->where('exam_type',2)->orderBy('created_at', 'desc')->get();
        return view('frontend.competitive',compact('competitives','title','description','others'));
    }

    public function competitiveDetails(Request $request,$slug)
    {
        $title = "Competitive" ;
        $description = "Competitive" ;
        $competitive = CompetititeExams::where('status',1)->where('slug',$slug)->first();
        $competitives = CompetititeExams::where('status',1)->orderBy('created_at', 'desc')->where('slug','!=',$slug)->get();
        $pdfs = CompetititeExamPdf::where('competitite_exams_id',$competitive->id)->get();
        return view('frontend.competitiveDetails',compact('competitive','competitives','title','description','pdfs'));
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
        $student  = student::count();
        $course   = Course::count();
        $teacher  = Instructor::count();
        $title = "About Us" ;
        $about = About::first();
        $ourvalues = Ourvalue::all();
        $faqs = Faq::limit(6)->get();

        return view('frontend.about',compact('title','about','ourvalues','faqs','student','course','teacher'));
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
        $setting = Setting::first();
        $states   = State::all();
        return view('frontend.bookSession',compact('setting','states'));
    }

    public function contactpost(Request $request)
    {
        $this -> validate ($request, [
            'first_name'  => 'required',
            'last_name'   => 'required',
            'email'       => 'required',
            'mobile'      => 'required',
            'message'     => 'required',
        ]);
        

        $data = $request->all();

        $contact = new ContactForm();
        $contact = ContactForm::create($data);

        return redirect()->back()->with('success',"Submitted Successfully");
    }

    public function freesession(Request $request)
    {
        $this -> validate ($request, [
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required',
            'mobile'     => 'required',
            'state_id'   => 'required',
            'state_id'   => 'required',
        ]);
        

        $data = $request->all();

        $freesession = new FreeSession();
        $freesession = FreeSession::create($data);

        return redirect()->back()->with('success',"Submitted Successfully");
    }

    public function feedback(Request $request)
    {
        $this -> validate ($request, [
            'first_name'   => 'required',
            'last_name'   => 'required',
            'email'   => 'required',
            'mobile'   => 'required',
            'message'   => 'required',
        ]);
        

        $data = $request->all();

        $contact = new Feedback();
        $contact = Feedback::create($data);

        return redirect()->back()->with('success',"Submitted Successfully");
    }

    public function enquiry(Request $request)
    {
        $this -> validate ($request, [
            'first_name'   => 'required',
            'last_name'   => 'required',
            'email'   => 'required',
            'mobile'   => 'required',
            'message'   => 'required',
        ]);
        

        $data = $request->all();

        $contact = new Enquiry();
        $contact = Enquiry::create($data);

        return redirect()->back()->with('success',"Submitted Successfully");
    }

}
