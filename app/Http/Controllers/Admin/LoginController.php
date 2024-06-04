<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class Logincontroller extends Controller
{
    //

    public function show()
    {
        if(Auth::id()) return redirect('admin/dashboard'); 
        return view('admin.login');
    }

    public function success()
    {
        echo "success";
    }
    public function login(LoginRequest $request)
    {
        
        $credentials = $request->getCredentials();
        $remember = $request->has('remember');

        if(!Auth::validate($credentials)):
            return redirect()->to('admin/login')
                ->withErrors(trans('auth.failed'));
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        if(isset($remember)&&!empty($remember)){
            setcookie('email',$credentials['email'],time()+ 60 * 60 * 24 * 2);
            setcookie('password',$credentials['password'],time()+ 60 * 60 * 24 * 2);
        }else {
            setcookie('email',"");
            setcookie('password',"");
        }

        Auth::login($user);
        return redirect('admin/dashboard');
        //return $this->authenticated($request, $user);
    }  

    public function logout()
    {
        Auth::logout();
        return redirect('admin/login');
    }

    public function password (Request $request) 
    {
        return view ('admin.changepassword');
    }

    
    public function change_password (Request $request) 
    {

        $this -> validate ($request, [
            'old_password'      => 'required|string|min:6   ',
            'new_password'      => 'required|string|min:6',
            'confirm_password'  => 'required|same:new_password',
         ]);

        
        $user = Auth::user();

        if (Hash::check($request->old_password, $user->password)) {
                $data['password'] = Hash::make($request->new_password);
                
                $id =  Auth::user()->id;
                $user = User::find($id);
                $user -> update($data);

                return redirect()->back()->with('success',"password Chaged Successfully");

         
        }else if (!Hash::check($request->old_password, $user->password)) {
            
            return redirect()->back()->with('error',"Old Password Mismatch");

        }
    }

    

}
