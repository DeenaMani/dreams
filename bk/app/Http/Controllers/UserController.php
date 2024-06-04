<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Student;
use App\Models\StudentLoginHistory;
use App\Models\BookingData;
use Session;
use Hash;
use DB;
use Mail;


class UserController extends Controller
{

    public function login(Request $request)
    {
        if(session('email') != "") { return redirect('dashboard');}
        $title = "Login" ;
        $description = "Login" ;
        return view('frontend.user.login',compact('title','description'));
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $email = $request->email;
        $password = $request->password;
        // echo $password = Hash::make($request->password); die;
        if($email && $password){
             $user = Student::where(function($query) use ($email){  
                     $query->where('email', $email)    
                        ->orWhere('phone', $email);   
                 })
                 ->first();
                // pr($user);
                
                if($user){
                    if(Hash::check(  $password,$user->password)){
                        $loginDetails = array(
                                           'ip_address' => getClientIps(),
                                           'login_at'   => date("Y-m-d H:i:s"),
                                           'student_id' => $user->id
                        );
                        StudentLoginHistory::insertGetId($loginDetails) ;

                        $request->session()->put('email', $user->email);
                        $request->session()->put('name', $user->first_name." ".$user->last_name);

                       return redirect('dashboard')->with('success', 'Login Success.');;
                    }
                    else{
                         return redirect('login')->with('error', 'Invalid Password.') ;
                    }
                    
                }
                else{
                    return redirect('login')->with('error', 'Email does not exits.');
                }
        }
        return redirect('login')->with('error', 'Login details are not valid.');
    }


    public function register(Request $request)
    {
        if(session('email') != "") { return redirect('dashboard');}
        $title = "Register" ;
        $description = "Register" ;
        return view('frontend.user.register',compact('title','description'));
    }

    public function forgetPassword(Request $request)
    {
        if(session('email') != "") { return redirect('dashboard');}
        $title = "Forget Password" ;
        $description = "Forget Password" ;
        return view('frontend.user.forgetPassword',compact('title','description'));
    }

    public function forgetPasswordPost(Request $request)
    {
        $request->validate([
            'email' => 'required|string|max:255',
        ]);
        $email = $request->email;
        $user = Student::where('email', $email) ->first();
        if($user){
                $new_password = Str::random(6);
                $html =  view('email.forgetPassword')->render();
                $html = str_replace("{%%NEWPASSWORD%%}", $new_password,$html);
                $html = str_replace("{%%NAME%%}", $user->first_name,$html);
                
                $to_email = strtolower($user->email);
                $to_name =  $user->first_name;
                $setting = cache('settings');
                $subject = $setting->company_name." - Reset Password";

                Mail::send(array(), array(), function ($message) use ($html,$to_email,$to_name,$subject){
                     $message->to($to_email, $to_name)
                            ->subject($subject)
                            ->bcc("gpandiyan.tech@gmail.com")
                            ->from(env('DOMAIN_FROM_EMAIL', false),env('DOMAIN_FROM_NAME', false))
                            ->setBody($html, 'text/html');
                });

                $password = Hash::make($new_password);
                $data = array(
                            'password'        => $password,
                            'updated_at'        => date("Y-m-d H:i:s"),
                );
                $email = session('email');
                Student::where('email',$email)->update($data) ;
                return redirect()->back()->with('success', 'Your New Is Sent Your Email. Please Check Your');  
        }
        else{
              return redirect()->back()->with('error', 'Your Email Is Incorrect');    
        }

    }


    public function registerPost(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required|string',
        ]);
        $email = $request->email;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $mobile = $request->mobile;
        $password = Hash::make($request->password);
        $user = Student::where('email', $email) ->first();
        if($user){
            return redirect()->back()->with('error', 'Already  Exits.');;
        }
        $data = array(
                    'email'             => $email,
                    'first_name'        => $first_name,
                    'last_name'         => $last_name,
                    'phone'             => $mobile,
                    'password'          => $password,
                    'status'            => 1,
                    'created_at'        => date("Y-m-d H:i:s"),
                    'updated_at'        => date("Y-m-d H:i:s"),
        );
        Student::insertGetId($data) ;
        return redirect()->back()->with('success', 'Register Success.');;

    }

    // public function dashboard(Request $request)
    // {
    //     if(session('email') == "") { return redirect('login');}
    //     $title = "Dashboard" ;
    //     $description = "Dashboard" ;
    //     return view('frontend.user.dashboard',compact('title','description'));
    // }

    public function dashboard(Request $request)
    {
        if(session('email') == "") { return redirect('login');}
        $title = "Profile" ;
        $description = "Profile" ;
        $email = session('email');
        $user = Student::where('email', $email) ->first();
        return view('frontend.user.profile',compact('title','description','user'));
    }

    public function saveProfile(Request $request)
    {
        if(session('email') == "") { return redirect('login');}

        $email = $request->email;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $mobile = $request->mobile;

        $data = array(
                    'first_name'        => $first_name,
                    'last_name'         => $last_name,
                    'phone'             => $mobile,
                    'updated_at'        => date("Y-m-d H:i:s"),
        );
        $email = session('email');
        Student::where('email',$email)->update($data) ;
        return redirect()->back()->with('success', 'Updated Successfully');    
    }

    public function transaction(Request $request)
    {
        if(session('email') == "") { return redirect('login');}
        $title = "Transaction" ;
        $description = "Transaction" ;
        $email = session('email');
        $user = Student::where('email', $email) ->first();
        $transactions = BookingData::select('booking_data.*',DB::raw('group_concat(booking_product.product_name) as product_names'))
                            ->where('user_id', $user->id)
                            ->where('booking_status',">", 0 )
                            ->leftJoin('booking_product', function($join) {
                                $join->on('booking_product.booking_id', '=', 'booking_data.id');
                            })
                            ->groupBy('booking_data.id')
                            ->orderBy('booking_data.id','desc')
                            ->get();
        return view('frontend.user.transaction',compact('title','description','transactions'));
    }

     public function changePassword(Request $request)
    {
        if(session('email') == "") { return redirect('login');}
        $title = "Change Password" ;
        $description = "Change Password" ;
        return view('frontend.user.changePassword',compact('title','description'));
    }


    public function changePasswordPost(Request $request)
    {
        if(session('email') == "") { return redirect('login');}

        $request->validate([
            'old_password' => 'required|string|max:255',
            'new_password' => 'required|string|max:255',
        ]);
        $old_password = $request->old_password;
        $new_password = $request->new_password;
        $email = session('email');
        $user = Student::where('email',$email)->first() ;
        if($user){
             if(Hash::check( $old_password,$user->password)){
                $password = Hash::make($request->new_password);
                $data = array(
                            'password'        => $password,
                            'updated_at'        => date("Y-m-d H:i:s"),
                );
                $email = session('email');
                Student::where('email',$email)->update($data) ;
                 return redirect()->back()->with('success', 'Changed Successfully');  
             }
             else{
                return redirect()->back()->with('error', 'Old Password In Correct');    
             }  
        }
        else{
              return redirect()->back()->with('error', 'Password Not updated');    
        }
        
    }

    public function logout(Request $request)
    {
        $request->session()->forget('email');
        $request->session()->forget('name');
        return redirect('/');
    }
    

}
