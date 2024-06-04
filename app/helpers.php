<?php

function assets($path=""){
    $line =  $path  == "" ? "/"  :"";
	return url("public/".$path).$line;
}


if (! function_exists('current_date')) {
    function current_date()
    {
        return date("Y-m-d H:i:s");
       // return date("Y-m-d H:i:s");
    }
}


if (! function_exists('pr')) {
    function pr($data,$die="")
    {
        echo "<pre>"; print_r($data); echo "</pre>";
        if($die) die; 
    }
}


if (! function_exists('get_percentage')) {
    function get_percentage($totalamount,$percentage)
    {
        $request = request();
        $request->bearerToken();
      
        if($percentage > 0){
            $amount = ($percentage / 100) * $totalamount;
            return round($totalamount + $amount,2);
        }
        else{
            return round($totalamount,2);
        }
       
    }
}

if (! function_exists('get_price')) {
    function get_price($price="")
    {
       return  "₹ ".$price;
    }
}
if (! function_exists('getClientIps')) {
    // Function to get the client IP address
    function getClientIps() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}

if (! function_exists('cartCount')) {
    function cartCount() {
        $userToken = Session::get('user_token');
        //$userToken = "17076255209552649749";
        $count =  DB::table('cart')->where('user_id', $userToken)->count();
        return $count;
    }
}

if (! function_exists('bookingStatus')) {
    function bookingStatus($status) {
        switch ($status) {
            case '1':
                return "Success";
                break;
            case '2':
                return "Failed";
                break;
            case '3':
                return "Cancelled";
                break;
            default:
                return "Pending";
                break;
        }
    }
}

if (! function_exists('paymentStatus')) {
    function paymentStatus($status) {
        switch ($status) {
            case '1':
                return "Paid";
                break;
            case '2':
                return "Failed";
                break;
            case '3':
                return "Refund";
                break;
            default:
                return "Pending";
                break;
        }
    }
}


if (! function_exists('paymentType')) {
    function paymentType($status) {
        switch ($status) {
            case '1':
                return "Online";
                break;
            case '2':
                return "Cash";
                break;
            default:
                return "Others";
                break;
        }
    }
}


if (! function_exists('days_convert')) {
    function days_convert($days) {
        switch ($days) {
            case '30':
                return "1 Month";
                break;
            case '90':
                return "3 Months";
                break;
            case '180':
                return "6 Months";
                break;
            case '360':
                return "1 Year";
                break;
            case '720':
                return "2 Years";
                break;
            case '1800':
                return "5 Years";
                break;
            default:
                return "O Days";
                break;
        }
    }
}