<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingData;
use App\Models\BookingBillingAddress;
use App\Models\BookingProduct;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = BookingData::select('booking_data.*','bba.first_name','bba.last_name','bba.email')
                            ->leftJoin('booking_billing_address as bba', function($join) {
                                $join->on('booking_data.id', '=', 'bba.booking_id');
                             })
                             ->where('booking_data.booking_status','>',0)
                             ->orderBy('booking_data.id','desc')
                             ->get();
        return view ('admin.bookings.index',compact('results'));
    }

    public function view($id)
    {
        $result = BookingData::find($id);
        $billingDetails = BookingBillingAddress::select('booking_billing_address.*','states.state_name')->where('booking_billing_address.booking_id',$id)
                     ->leftJoin('states', function($join) {
                                $join->on('booking_billing_address.state', '=', 'states.id');
                             })
                    ->first();
        $products = BookingProduct::where('booking_id',$id)->get();
        return view ('admin.bookings.view',compact('result','billingDetails','products'));
    }

    public function bookingStatus($id,$status)
    {
        //echo $id." ".$status;
        $bookingData = BookingData::find($id);
        $bookingData->booking_status =   $status ? $status : 0;
        $bookingData->save();
        echo json_encode(array("status" => 1));
    }

    public function paymentStatus($id,$status)
    {
        //echo $id." ".$status;
        $bookingData = BookingData::find($id);
        $bookingData->payment_status =   $status ? $status : 0;
        $bookingData->save();
        echo json_encode(array("status" => 1));
    }
}
