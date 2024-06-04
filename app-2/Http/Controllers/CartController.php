<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Student;
use App\Models\Category;
use App\Models\BookingData;
use App\Models\BookingBillingAddress;
use App\Models\BookingProduct;
use App\Models\Setting;

use Session;
use Hash;
use DB;


class CartController extends Controller
{
    //

    public function index(Request $request)
    {


         //$this->session_set();


        //  echo Session::get('userId');
        // if(empty(Session::get('user_token'))) {
        //     $userToken = time().rand(10000000000,9000000000);
        //     Session::put('userId',$userToken);

        // }
        // else{
        //     $userToken = Session::get('userId') ;
        // }
        // echo $userToken;die;
        $title = "Cart" ;
        $description = "Cart" ;
        $userToken = Session::get('user_token');
        // $userToken = "17076255209552649749";
        $cart = Cart::where('user_id',$userToken)->get();
        return view('frontend.cart.index',compact('cart','title','description'));
    }

    public function addToCart(Request $request){
        $product_id = $request->id;
        $type = $request->type;
       // $userToken = "17076255209552649749";


        
        $userToken = Session::get('user_token') ;
        if($product_id){
            $cart = Cart::where('user_id',$userToken)->where('product_id',$product_id)->where('cart_type',$type)->first();
            if(empty($cart)){
                $data = array(
                        'product_id'        => $product_id,
                        'cart_type'         => $type,
                        'created_at'        => date("Y-m-d H:i:s"),
                        'user_id'           => $userToken,
                );
                Cart::create($data);
                $totalCount = Cart::where('user_id',$userToken)->count();
                $results = array('message' => "Added Successfully",'status' => 1 ,'totalCount' => $totalCount);
                echo json_encode($results);
                die;
            }
            else{
                $totalCount = Cart::where('user_id',$userToken)->count();
                $results = array('message' => "Already Added",'status' => 0 ,'totalCount' => $totalCount);
                echo json_encode($results);
                die;
            }
            
        }
    }

    public function session_set(){
        if(session('user_token')) {
            $userToken = session('user_token');   
        }
        else{
             $userToken = time().rand(10000000000,9000000000);
            //$request->session()->put('user_token',$userToken);
             session()->put('user_token', $userToken);
           
        }
    }

    public function cartDelete(Request $request,$id){
   
        //$userToken = "17076255209552649749";
        $userToken = Session::get('user_token');
        if($id){
            $id = base64_decode($id);
            Cart::where('user_id',$userToken)->where('id' , $id)->delete();
            return redirect()->back()->with('error', 'Deleted Successfully');   
        }
    }

    public function checkout(Request $request)
    {
        $title = "Cart" ;
        $description = "Cart" ;
        $userToken = Session::get('user_token');
        //$userToken = "17076255209552649749";
        $cart = Cart::where('user_id',$userToken)->get();

        $email = session('email');
        $user = Student::where('email',$email)->first() ;
        $states = DB::table('states')->get() ;
        //pr($user);
        return view('frontend.cart.checkout',compact('cart','title','description','user','states'));
    }

     public function checkoutPost(Request $request)
    {
        $userToken = Session::get('user_token');
       // $userToken = "17076255209552649749";
        $user_id  = "";
        if($request->post()){
            $cart = Cart::where('user_id',$userToken)->get();
           //  pr($cart);DIE;
            $userData = array(
                        'first_name'        => $request->first_name,
                        'last_name'         => $request->last_name,
                        'email'             => $request->email,
                        'phone'             => $request->phone,
                        'state'             => $request->state,
                        'address'           => $request->address,
                        'city'              => $request->city,
                        'pincode'           => $request->pincode,
            );

            $email = $request->email;
            if(empty($user_id)){
                $user = Student::where('email',$email)->first();
                if($user){
                    $user_id = $user->id;
                }
                else{
                   $user_id =  Student::insertGetId($userData);
                }
            }
            $price = 0 ;

            if($cart){
                foreach ($cart as $key => $value) {
                    if($value->cart_type){
                        $category =  Category::where('id',$value->product_id)->first();
                        if($category){
                            $price  += $category->price;
                        }

                    }
                }
            }
            $subTotal = $price ;
            $totalPrice = $price ;
            $bookingStatus = 0;
            $paymentStatus = 0;
            $bookingData = array(
                            'user_id'               =>  $user_id,
                            'booking_status'        =>  $bookingStatus,
                            'payment_status'        =>  $paymentStatus,
                            'sub_total'             =>  $subTotal,
                            'total_price'           =>  $totalPrice,
                            'currency'              =>  "INR",
                            'ip_address'            =>  getClientIps(),
                            'created_at'            =>  date("Y-m-d H:i:s"),
            );
            $bookingId = BookingData::insertGetId($bookingData);
            
            $newBookingID = str_pad($bookingId, 4, '0', STR_PAD_LEFT);
            $bookingUpdate = array('booking_no' => "CM".$newBookingID); 
            BookingData::where('id',$bookingId)->update($bookingUpdate);

            $userData['booking_id'] = $bookingId;
            DB::table('booking_billing_address')->insertGetId($userData);
            $product_names = array();
            if($cart){
                foreach ($cart as $key => $value) {
                    if($value->cart_type){
                        $category =  Category::where('id',$value->product_id)->first();
                        if($category){
                            $product_price  = $category->price;
                            $product_name = $category->category_name;
                            $product_id = $category->id;
                            $validity_days = $category->validity_days;
                            $product_type = 1;
                            $product_names[] =  $category->category_name;
                        }

                    }


                    $BookingProductData = array(
                                            'booking_id'        => $bookingId,
                                            'product_type'      => $product_type,
                                            'product_id'        => $product_id,
                                            'product_name'      => $product_name,
                                            'product_price'     => $product_price,
                                            'validity_days'     => $validity_days,
                    );
                    //pr($BookingProductData);
                     DB::table('booking_product')->insertGetId($BookingProductData);
                }
              
            }
            $setting = Setting::first();
            $paymentData = array(
                            'totalPrice'                => round($totalPrice * 100),
                            'totalPriceWithSymbol'      => get_price($totalPrice * 100),
                            'product_name'              => implode(",", $product_names),
                            'firstName'                 => $request->first_name,
                            'email'                     => $request->email,
                            'logo'                      => assets('image/setting/'.$setting->logo),
            );
            $booking_id = Crypt::encryptString($bookingId);
            $data = array(
                    'bookingId'    => $booking_id
                );
        
            echo json_encode($data);
            // $html = view('frontend.payment.generate',compact('paymentData'))->render();; 
            // echo $html;

            
        }
    }

}
