<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Student;
use App\Models\Category;
use App\Models\BookingData;
use App\Models\BookingBillingAddress;
use App\Models\BookingPayments;
use App\Models\BookingProduct;
use Razorpay\Api\Api;
use Session;
use Hash;
use DB;



class PaymentController extends Controller
{
    //

    public function index(Request $request,$booking_id)
    {
        $id = Crypt::decryptString($booking_id);
        $booking = BookingData::where('id' ,$id)->first();
        $billing = BookingBillingAddress::where('booking_id' ,$id)->first();
        if($booking->booking_status != 0){
            return redirect('');
        }
        $title = "Payments" ;
        $description = "Payments" ;
        return view('frontend.payment.index',compact('title','description','booking','billing','booking_id'));
    }
    public function razorPaystore(Request $request)
    {

           $input = $request->all();
           $booking_id = $input['bookingId'];
           $id = Crypt::decryptString($booking_id);
           $razorpay_payment_id = $input['razorpay_payment_id'];
           // pr($input);die;
            $api = new Api (env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $payment = $api->payment->fetch($input['razorpay_payment_id']);
            if(count($input) && !empty($input['razorpay_payment_id'])) {
                try {
                    $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
                    if($response['status'] == "captured"){
                        $payment_status = 1;
                        $booking_status = 1;
                    }
                    else{
                        $payment_status = 0;
                        $booking_status = 2;
                    }
                    $payment = BookingPayments::create([
                            'booking_id'        => $id,
                            'r_payment_id'      => $response['id'],
                            'method'            => $response['method'],
                            'currency'          => $response['currency'],
                            'user_email'        => $response['email'],
                            'amount'            => $response['amount']/100,
                            'json_response'     => json_encode((array)$response)
                    ]);

                    $bookingUpdate = array(
                                'payment_type'          => 2,
                                'payment_status'        => $payment_status,
                                'booking_status'        => $booking_status,
                                'transcation_id'        => $razorpay_payment_id,
                                'payment_response'      => json_encode((array)$response),
                    );
                    $request->session()->forget('user_token');
                    BookingData::where('id',$id)->update($bookingUpdate);
                    // die;
                    if($payment_status == 1){
                        return redirect('confirmation/'.$booking_id);
                    }
                    else{
                        return redirect('failed/'.$booking_id);
                    }

                } catch(Exceptio $e) {
                    return $e->getMessage();
                    Session::put('error',$e->getMessage());
                    return redirect()->back();
                }
            }
            Session::put('success','Payment Successful');
            //return redirect()->back();
    }


    public function paymentUpdate(Request $request){
        $request->validate([
            'booking_id' => 'required|string|max:255',
        ]);

        $booking_id = $request->booking_id;
        $id = Crypt::decryptString($booking_id);
        $booking = BookingData::where('id',$id)->first();

        $payment_type = $request->payment_type;
        $newBookingID = "CM".str_pad($id, 4, '0', STR_PAD_LEFT);
        if($payment_type == 2){
            $bookingUpdate = array(
                        'payment_type'      => 2,
                        'payment_response'  => "CASH",
                        'booking_status'    => 1,
                        'transcation_id'    => $newBookingID
            );
            BookingData::where('id',$id)->update($bookingUpdate);
           // pr($bookingUpdate);die;
            $request->session()->forget('user_token');
            return redirect('confirmation/'.$booking_id);
        }
        if($payment_type == 1){


        }

    }


    public function confirmation(Request $request,$booking_id){

        $id = Crypt::decryptString($booking_id);
        $booking = BookingData::where('id' ,$id)->first();
        $bookingProduct = BookingProduct::where('booking_id',$booking->id)->get();
        $title = "Confirmation" ;
        $description = "Confirmation" ;
        return view('frontend.payment.confirmation',compact('title','description','booking','bookingProduct'));
    }

    public function failed(Request $request,$booking_id){
        $id = Crypt::decryptString($booking_id);
        $booking = BookingData::where('id' ,$id)->first();
        $bookingProduct = BookingProduct::where('booking_id',$booking->id)->get();
        $title = "Failed" ;
        $description = "Failed" ;
        return view('frontend.payment.failed',compact('title','description','booking','bookingProduct'));
    }

}
