<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use DB;
use App\Models\Setting;
class UserToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   

         if(session('user_token')) {
            $userToken = session('user_token');   
        }
        else{
             $userToken = time().rand(10000000000,9000000000);
            //$request->session()->put('user_token',$userToken);
             session()->put('user_token', $userToken);
           
        }

        //Cache::flush();
        if (!Cache::has('settings')) {
            $settings = Setting::find(1);
            Cache::add('settings', $settings, now()->endOfDay());
        }

         // $value = cache('settings');
         //     pr($value);

        // echo (Session::get('userToken'));
        // echo "<br>";
        // if((Session::get('userToken')) == "") {
        //     $token = time().rand(10000000000,9000000000);
        //     Session::put('userToken',$token);

        // }
        // echo Session::get('userToken');   die;
        return $next($request);
    }
}
