<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\AdyenClient;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

use BookingProtect\InsuranceHub\Client as BP;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;
use Jenssegers\Agent\Agent;

use Hash;
use Session;
use Auth;
use DB;
use Stripe;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $checkout;
    public function __construct(AdyenClient $checkout)
    {
        $this->checkout = $checkout->service;
        //$this->middleware('auth');
    }

    public function webhook(Request $request,$lang){

           $myfileName = date('Y-m-d h:i:s').'.txt';
         file_put_contents(storage_path().'/webhooks/'.$myfileName, $request); 
            chmod(storage_path().'/webhooks/'.$myfileName, fileperms(storage_path().'/webhooks/'.$myfileName) | 128 + 16 + 2);

        $hmac_key = env('ADYEN_HMAC_KEY');
        $validator = new \Adyen\Util\HmacSignature;
        //$out = new ConsoleOutput();

        $mynotifications = $request->getContent();
       // $mynotifications =file_get_contents(storage_path().'/webhooks/1BO00826.json'); 
       

        $notifications = json_decode($mynotifications, true);
        $notificationItems = $notifications['notificationItems'];

        foreach ($notificationItems as $item) {
            $requestItem = $item['NotificationRequestItem'];
            if ($validator->isValidNotificationHmac($hmac_key, $requestItem)) {
                $rawData = file_get_contents("php://input");
                $fileName = $requestItem['merchantReference'].'.json';

                $booking_logs = array('step' => 7,'time_stamp' => date('d-m-Y h:i:s'),'msg' => 'webhooks called -'.json_encode($item));
                booking_log($requestItem['merchantReference'],$booking_logs);
                //echo "<pre>";print_r($notifications);exit;
                file_put_contents(storage_path().'/webhooks/'.$fileName, $rawData); 
            chmod(storage_path().'/webhooks/'.$fileName, fileperms(storage_path().'/webhooks/'.$fileName) | 128 + 16 + 2);
            sleep(1);

             if($requestItem['merchantReference'] != ''){

      $booking_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->post(API_URL .'booking_details?lang=en',[
            'booking_no' => md5($requestItem['merchantReference']),
            ]); 
     $booking = $booking_response['booking'];
    sleep(1);
     if($requestItem['success']){

                if($requestItem['success'] == 'true'){
                    $payment_status = 1;
                }
                else {
                    $payment_status = 0;
                }

            }
            else{
                $payment_status = 0;
            }    
            $booking_logs = array('step' => 8,'time_stamp' => date('d-m-Y h:i:s'),'msg' => 'webhooks result called');
                booking_log($requestItem['merchantReference'],$booking_logs);

            if($payment_status == 1 && $booking->booking_status == 7){

            $booking_logs = array('step' => 8,'time_stamp' => date('d-m-Y h:i:s'),'msg' => 'webhooks result called and condition matches');
                booking_log($requestItem['merchantReference'],$booking_logs);
             
            $premium_status = $booking['premium_subscription'];
            if($booking['premium_subscription'] == 2 && $payment_status == 1){
            $premium = $this->submit_booking_protect($booking['booking_no']);
            $premium_status = $premium['status'];
            }

        $response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'payment_update?lang=en', [
                                'booking_id'        => $booking['bg_id'],
                                'payment_type'      => 1,
                                'payment_from'      => 'webhook',
                                'premium_subscription' => $premium_status,
                                'payment_status'    => $payment_status,
                                'transcation_id'    => $requestItem['pspReference'],
                                'payment_response'  => $mynotifications,
                                'message'           => $booking['message'],
                                'total_payment'     => $requestItem['amount']['value']/100,
                                'currency_code'     => $requestItem['amount']['currency']
                ]);
        $booking_logs = array('step' => 9,'time_stamp' => date('d-m-Y h:i:s'),'msg' => 'webhooks result Updated');
         booking_log($requestItem['merchantReference'],$booking_logs);
        /*$response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'payment_update?lang=en', [
                                'booking_id'        => $booking['bg_id'],
                                'payment_type'      => 1,
                                'payment_from'      => 'webhook',
                                'payment_status'    => $payment_status,
                                'transcation_id'    => $requestItem['pspReference'],
                                'payment_response'  => $mynotifications,
                                'message'           => $booking['message'],
                                'total_payment'     => $requestItem['amount']['value']/100,
                                'currency_code'     => $requestItem['amount']['currency']
                ]);*/
        sleep(1);
            //echo "<pre>";print_r($response);exit;
                }
     
                }

                //$out->writeln("MerchantReference: " . json_encode($requestItem['merchantReference'], true));
               // $out->writeln("Eventcode " . json_encode($requestItem['eventCode'], true));
            } else {
                return response()->json(["[refused]", 401]);
            }
        }
        return response()->json(["[accepted]", 200]);
    }


    public function booking_protect(Request $myrequest,$lang)
    {   
        if($myrequest->cart_id != "" && $myrequest->checked != ""){ 

            $cart_response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'cart?lang='.Session::get('locale')."&currency=".Session::get('currency'),[
            'cart_id' => $myrequest->cart_id
            ]);
        $results = $cart_response['result'];
      /*  $ticket_price    = $results['total_amount'];
        $ticket_currency = $results['currency_code'];*/
        $ticket_price    = $results['payment_price'];
        $ticket_currency = $results['payment_currency'];
        if($ticket_price != "" && $ticket_currency != ""){

            $config = parse_ini_file(storage_path().'/booking_protect' . '/config.ini');
            $apiConfig = new BP\ApiClientConfiguration();

            $apiConfig->environment = $config['environment'];
            $apiConfig->certificatePath = storage_path().'/booking_protect' . '/cacert.pem'; 
            $apiConfig->apiKey = $config['api_key'];
            $apiConfig->vendorId = $config['vendor_id'];

            $urlBuilder = new BP\DefaultApiClientUrlBuilder($apiConfig);
            $autoTokenGenerator = new BP\AuthTokenGenerator();
            $httpClient = new Client();
            $jsonMapper = new \JsonMapper();
            $client = new BP\ApiClient($apiConfig, new BP\AuthTokenGenerator(), $urlBuilder, $httpClient, $jsonMapper);

            // create offering request object
            $request = new BP\OfferingRequest();
            $request->vendorId = $config['vendor_id'];

            // set values relating to transaction
            $request->vendorRequestReference = uniqid('TEST_REF_');

            $match_date = date('Y-m-d h:i:s.' . gettimeofday()['usec'],strtotime($results['match_date'].' '.$results['match_time']));
            $eventDate = new \DateTime($match_date);
            $product1 = new BP\Product();
            $product1->categoryCode = 'TKT';
            $product1->languageCode = 'eng';
            $product1->currencyCode = strtoupper($ticket_currency);
            $product1->price = $ticket_price;
            $product1->completionDate = $eventDate;
            $request->products[] = $product1;
            try
            { 
              
            $offering = $client->getOffering($request);
            //echo "<pre>";print_r($offering);exit;
            if(empty($offering->productOfferings)){
                if(!empty($offering->productsOutsideOfPricing[0])){
                    $error ="we can not provide cover as the price falls outside the limits of the agreed pricing structure.";
                    //custom_error_log($error);
                    echo json_encode(array('status' => 0,'message' => $error));exit;

                }
            }
             if(strtoupper($offering->productOfferings[0]->currencyCode) == 'GBP'){
                    $booking_protect_price = $offering->productOfferings[0]->premium;
                }
                else if(strtoupper($offering->productOfferings[0]->currencyCode) == 'EUR'){
                    $booking_protect_price = $offering->productOfferings[0]->premium;
                }
                else if(strtoupper($offering->productOfferings[0]->currencyCode) == 'USD'){
                    $booking_protect_price = $offering->productOfferings[0]->premium;
                }
                else if(strtoupper($offering->productOfferings[0]->currencyCode) == 'AED'){
                    $booking_protect_price = $offering->productOfferings[0]->premium;
                }

                $results =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
                ])->post(API_URL .'update_booking_protect?lang='.Session::get('locale'),[
                'booking_protect'       => $offering,
                'booking_protect_price' => $booking_protect_price,
                'id' => $myrequest->cart_id
                ]);
                if($results['status'] == 1){

                    echo json_encode(array('status' => 1,'msg' => 'success'));exit;
                }
                else{
                    $error ="Oops.Something Went Wrong.";
                    custom_error_log($error);
                }

            
            }
            catch(BP\InsureHubApiNotFoundException $validationException){
            $error = $validationException->errorMessage();
            custom_error_log($error);
            }
            catch(BP\InsureHubApiValidationException $validationException){
            $error = $validationException->errorMessage();
            custom_error_log($error);
            }
            catch(BP\InsureHubApiAuthorisationException $authorisationException){
            $error = $authorisationException->errorMessage();
            custom_error_log($error);
            }
            catch(BP\InsureHubApiAuthenticationException $authenticationException){
            $error = $authenticationException->errorMessage();
            custom_error_log($error);
            }
            catch(BP\InsureHubException $insureHubException){
            $error =  $insureHubException->errorMessage();
            custom_error_log($error);
            }
            catch(Exception $exception){
            $error = $exception->getMessage();
            custom_error_log($error);
            }

        }
        else{
            $error = "Invalid Ticket Pricing.";
            custom_error_log($error); 
        }

        }
        else{ 
             $results =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
                ])->post(API_URL .'update_booking_protect?lang='.Session::get('locale'),[
                'booking_protect'       => '',
                'booking_protect_price' => '',
                'id' => $myrequest->cart_id
                ]);
                if($results['status'] == 1){

                    echo json_encode(array('status' => 1,'msg' => 'success'));exit;
                }
                else{
                    $error ="Oops.Something Went Wrong.";
                    custom_error_log($error);
                }
        }
           
    }


    public function cart(Request $request,$lang,$base_id)
    {
      
        $cart_id = base64_decode($base_id); 
        if($cart_id){
            $cart_response =  Http::withHeaders([
                    'Accept' => 'application/json',
                    'domainkey' => DOMAIN_KEY
                    ])->post(API_URL .'check_cart?lang='.Session::get('locale')."&currency=".Session::get('currency'),[
                        'cart_id' => $cart_id
                    ]);
            $results = $cart_response['result']; 
            if($results){
                $delete_response =  Http::withHeaders([
                    'Accept' => 'application/json',
                    'domainkey' => DOMAIN_KEY
                ])->post(API_URL .'update_cart?lang='.Session::get('locale'),[
                                    'ip'            => get_client_ip(),
                                    'cart_id'       => $cart_id
                        ]);
                $results = $delete_response;
                if($results['status'] == 1){ 
                    return redirect(app()->getLocale()."/".'checkout');
                }
                else{

                }
            }
            else{

            }
        }
    }
        
     public function add_to_cart(Request $request)
    { 
        $affiliate_token = "";
        if(@$_COOKIE["affiliate_token"]){
            $affiliate_token = $_COOKIE["affiliate_token"];
        }
        $affiliate_utm_content ="";
        if(@$_COOKIE["affiliate_utm_content"]){
            $affiliate_utm_content = $_COOKIE["affiliate_utm_content"];
        }
        $affiliate_utm_medium ="";
        if(@$_COOKIE["affiliate_utm_medium"]){
            $affiliate_utm_medium = $_COOKIE["affiliate_utm_medium"];
        }

        $click_ref="";
        if(@$_COOKIE["reference_url"]){
            $click_ref = $_COOKIE["reference_url"];
        }

        $partner_id = "";
        if(@$_COOKIE["partner_token"]){
            $partner_id = $_COOKIE["partner_token"];
        }

      
        if(@$_COOKIE["click_ref"]){
            $click_ref = $_COOKIE["click_ref"];
        }
        $reference_url = "";
        if(@$_COOKIE["reference_url"]){
            $reference_url = $_COOKIE["reference_url"];
        }

         try {
                $response =  Http::withHeaders([
                    'Accept' => 'application/json',
                    'domainkey' => DOMAIN_KEY
                ])->post(API_URL .'add_to_cart?lang='.Session::get('locale'), [
                    'match_id'       => base64_decode($request->match_id),
                    'quantity'       => $request->quantity,
                    'sell_ticket_id' => base64_decode($request->sell_ticket_id),
                    'stadium_id'     => base64_decode($request->stadium_id),
                    'ip'             => get_client_ip(),
                    'partner_token'  => $partner_id,
                    'affiliate_token'=> $affiliate_token,
                    'click_ref'      => $click_ref,
                    'oneclicket_id'  => @$request->oneclicket_id,
                    'tixstock_id'    => @$request->tixstock_id,
                    'xs2event_id'    => @$request->xs2event_id,
                    'reference_url'  => @$reference_url,
                    'affiliate_utm_content' => @$affiliate_utm_content,
                    'affiliate_utm_medium' => @$affiliate_utm_medium,
                    'user_token'     => Session::get('user_token_id')
                ]); 

                 if($response->failed()){
                    if($response->clientError()){
                         $response->throw();
                    }
                    if($response->serverError()){
                        $response->throw();
                    }
                    
                }
                $results  = @$response['result'];
                if($results){
                    if(@$results['cart_id']){
                        Session::put('cart_id', trim($results['cart_id']));
                        Session::put('cart_quantity', trim($request->quantity));
                    }
                }

               
               // echo "1";

                echo json_encode($results);
                //return redirect('checkout');
            }
        catch (\Exception $e) {
           echo $response;
        }
        catch (\Throwable $t) {
            custom_error_log($t->getMessage());
        }
    }

    public function go_to_cart(Request $request,$lang,$token="")
    { 
        $token = $token  ?  $token : $request->token;
        if($token == ""){
            return redirect('');
        }
        
        $booking_no = Crypt::decryptString($token);
        // echo "<br>";
    
        $booking_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'booking_details?lang='.Session::get('locale'),[
                'booking_no' => md5($booking_no),
        ]); 
      
        $booking = @$booking_response['booking'];
        // pr($booking);die;
        if($booking){

            $request_data =  [
                'match_id'      => (string)$booking['match_id'],
                'quantity'      => (string)$booking['quantity'],
                'sell_ticket_id'=> (string)$booking['ticket_id'],
                'stadium_id'    => (string)$booking['stadium_id'],
                'ip'            => get_client_ip(),
                'user_token'    => Session::get('user_token_id')
            ];
          
            $response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->post(API_URL .'add_to_cart?lang='.Session::get('locale'),$request_data);
            $results  = @$response['result'];

            if($results){
                if(@$results['status'] == 1){
                    Session::put('cart_id', trim($results['cart_id']));
                    Session::put('cart_quantity', trim($booking['quantity']));
                    return redirect(Session::get('locale')."/checkout");
                }
                else{
                    $slug  = @$results['slug'];
                    return redirect(Session::get('locale')."/".$slug);    
                }
            }
            else{
                return redirect('');
            }
           
        }
        else{
            return redirect('');
        }
        die;
    }

    public function check_coupon(Request $request)
    {
        $cartId = Session::get('cart_id'); 
        if($cartId == ""){
            return redirect('');
        }
        $cart_response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'cart?lang='.Session::get('locale')."&currency=".Session::get('currency'),[
            'cart_id' => Session::get('cart_id'),
            'discount_code' => $request->discount_code
            ]);
        $results = $cart_response['result'];
       // echo "<pre>";print_r($results);exit;
        if(!empty($results['discount_coupon'])){
                    echo json_encode(array('status' => 1,'msg' => 'success'));exit;
                }
                else{
                    $error ="Coupon code invalid.";
                    echo json_encode(array('status' => 0,'msg' => 'failure'));exit;
                }
        
    }

    public function remove_coupon(Request $request)
    {
        $cartId = Session::get('cart_id'); 
        if($cartId == ""){
            return redirect('');
        }
        $cart_response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'cart?lang='.Session::get('locale')."&currency=".Session::get('currency'),[
            'cart_id' => Session::get('cart_id'),
            'remove_coupon' => 'yes'
            ]);
        $results = $cart_response['result'];
        echo json_encode(array('status' => 1,'msg' => 'success'));exit;
    }


    public function checkout(Request $request,$lang)
    { 

         if(@$request->token != ""){ 

            if($request->currency =="USD" || $request->currency =="GBP" || $request->currency =="EUR" || $request->currency =="AED"){
                 Session::put('currency', $request->currency);
            }
           
            if(@$_COOKIE["partner_token"] == ""){
                $cookie_days =  time() + (86400 * 45);
                setcookie("partner_token", $request->token, $cookie_days, "/");
            }

            if(@$_COOKIE["click_ref"] == ""){
                $cookie_days =  time() + (86400 * 45);
                setcookie("click_ref", @$request->clickref, $cookie_days, "/");
            }

            $token = $request->token;   
            $match_id = base64_decode($request->m_id);  
            $ticket_id = base64_decode($request->ticket_id);    
            $quantity = @$request->quantity ? $request->quantity : "1";
            $reference_url = request()->headers->get('referer');    
            $click_ref = @$request->clickref;   
            $checkout_url = $request->fullUrl();    
            $cartPostData = [   
                'match_id'      => $match_id,   
                'quantity'      => $quantity,   
                'sell_ticket_id'=> $ticket_id,  
                'stadium_id'    => "11",    
                'ip'            => get_client_ip(),  
                'reference_url' => $reference_url,  
                'click_ref'     => $click_ref,  
                'checkout_url'  => $checkout_url,   
                'tixstock_id'   => @$request->eid,
                'partner_token' => $token,
                'user_token'    => Session::get('user_token_id') 
            ];  
                
            $response =  Http::withHeaders([    
                'Accept' => 'application/json', 
                'domainkey' => DOMAIN_KEY   
            ])->post(API_URL .'add_to_cart?lang='.Session::get('locale'), $cartPostData);
    
            $results  = @$response['result'];
           
           // file_put_contents("public/res-".time().".json",$response);
            if(@$response['result']['status'] != 0 ){
                Session::put('cart_quantity', trim($quantity)); 
                Session::put('cart_id', trim($results['cart_id']));      
                return redirect(Session::get('locale')."/checkout");    
            }
            else{
                return redirect(Session::get('locale')."/".@$response['result']['slug']);    
            }    
        }   

        $cartId = Session::get('cart_id');
        
        if($cartId == ""){
            return redirect('');
        }
        $cart_response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'cart?lang='.Session::get('locale')."&currency=".Session::get('currency'),[
            'cart_id' => Session::get('cart_id')
            ]);
        $results = @$cart_response['result'];
       
        if($results){
            $match_details = explode("vs",$results['match_name']);
            if((@$results['tournament_name'] == "World Cup Qatar 2022"  || @$results['tournament_name'] == "كأس العالم قطر 2022")  && Session::get('currency') != "USD"  )
            {
               Session::put('currency', "USD");
               return redirect(Session::get('locale')."/checkout");
            }

            $country_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->get(API_URL .'country?sort_by=name');
            $country = $country_response['results'];
            $states ="";
            if(Session::get('country')){
                $state_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->post(API_URL .'state?lang='.Session::get('locale'),[
                    'country_id' => Session::get('country')
                    ]);
                $states = $state_response['results'];
            }

             $nationality_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->get(API_URL .'nationality');
            $nationality = $nationality_response['results'];
            
            $agent = new Agent();
            $mobile  = $agent->isMobile();
            $title =  "Checkout";
            $description =  "Checkout";
            $footer_disable = 1;
            return view('frontend.payment.checkout',compact('results','country','nationality','cartId','title','description','states','match_details','mobile','footer_disable'));
        }
        else{
             Session::forget('cart_id');
            Session::forget('cart_quantity');
            
            return redirect(app()->getLocale());
        }
    }

     public function submit_booking_protect($booking_no){
       
        $booking_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->post(API_URL .'booking_details?lang='.Session::get('locale'),[
            'booking_no' => md5($booking_no),
            ]); 
         
        $booking = $booking_response['booking'];
        $premium_data = json_decode($booking['premium_data']);
        $premium_id   = $premium_data->id;
        $productOfferings = $premium_data->productOfferings;

        $booking_logs = array('step' => 10,'time_stamp' => date('d-m-Y h:i:s'),'msg' => 'submit_booking_protect called');
        booking_log($booking['booking_no'],$booking_logs);

        if($booking){

            $config = parse_ini_file(storage_path().'/booking_protect' . '/config.ini');
            $apiConfig = new BP\ApiClientConfiguration();

            $apiConfig->environment = $config['environment'];
            $apiConfig->certificatePath = storage_path().'/booking_protect' . '/cacert.pem'; 
            $apiConfig->apiKey = $config['api_key'];
            $apiConfig->vendorId = $config['vendor_id'];

            $urlBuilder = new BP\DefaultApiClientUrlBuilder($apiConfig);
            $autoTokenGenerator = new BP\AuthTokenGenerator();
            $httpClient = new Client();
            $jsonMapper = new \JsonMapper();
            $client = new BP\ApiClient($apiConfig, new BP\AuthTokenGenerator(), $urlBuilder, $httpClient, $jsonMapper);

            $offeringResult = new BP\OfferingResult();
            $offeringResult->vendorId = $config['vendor_id'];

    // set transaction information
    $offeringResult->offeringId = $premium_id;
    $offeringResult->vendorSaleReference = $booking['booking_no'];//'Your Sales Reference/Invoice Number';
    $offeringResult->customerSurname = $booking['buyer_last_name'];
    $offeringResult->customerForename = $booking['buyer_first_name'];
    $offeringResult->emailAddress = $booking['email'];

    $booking_logs = array('step' => 10.1,'time_stamp' => date('d-m-Y h:i:s'),'msg' => 'submit_booking_protect called'.json_encode($offeringResult));
        booking_log($booking['booking_no'],$booking_logs);

    // set customer's choice - did they want to protect their purchase?
    foreach ($productOfferings as $productOffering) {
        $sale = new BP\ProductOfferingResult();
        $sale->productOfferingId = $productOffering->id;
        $sale->sold = true;

        $offeringResult->sales[] = $sale;
    } 
        $status = 3;
        try{
        if ($data = $client->submitOfferingResult($offeringResult)){
        $msg =  "Offering result submit successfully";
        $status = 1;
        }else{
        $msg = "Result Submission Failed.";
        }
        }
        catch(BP\InsureHubApiNotFoundException $validationException){
        $msg = $validationException->errorMessage();
        }
        catch(BP\InsureHubApiValidationException $validationException){
        $msg = $validationException->errorMessage();
        }
        catch(BP\InsureHubApiAuthorisationException $authorisationException){
        $msg = $authorisationException->errorMessage();
        }
        catch(BP\InsureHubApiAuthenticationException $authenticationException){
        $msg = $authenticationException->errorMessage();
        }
        catch(BP\InsureHubException $insureHubException){
        $msg =  $insureHubException->errorMessage();
        }
        catch(Exception $exception){
        $msg =  $exception->getMessage();
        }
        $booking_logs = array('step' => 10,'time_stamp' => date('d-m-Y h:i:s'),'msg' => 'submit_booking_protect '.$msg);
        booking_log($booking['booking_no'],$booking_logs);
        return $response = array('status' => $status,'msg' => $msg);
        }

    }


    public function submit_booking_protect_manual($lang,$booking_no){
       
        $booking_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->post(API_URL .'booking_details?lang='.Session::get('locale'),[
            'booking_no' => md5($booking_no),
            ]); 
         
        $booking = $booking_response['booking'];
        $premium_data = json_decode($booking['premium_data']);
        $premium_id   = $premium_data->id;
        $productOfferings = $premium_data->productOfferings;
        if($booking){

            $config = parse_ini_file(storage_path().'/booking_protect' . '/config.ini');
            $apiConfig = new BP\ApiClientConfiguration();

            $apiConfig->environment = $config['environment'];
            $apiConfig->certificatePath = storage_path().'/booking_protect' . '/cacert.pem'; 
            $apiConfig->apiKey = $config['api_key'];
            $apiConfig->vendorId = $config['vendor_id'];

            $urlBuilder = new BP\DefaultApiClientUrlBuilder($apiConfig);
            $autoTokenGenerator = new BP\AuthTokenGenerator();
            $httpClient = new Client();
            $jsonMapper = new \JsonMapper();
            $client = new BP\ApiClient($apiConfig, new BP\AuthTokenGenerator(), $urlBuilder, $httpClient, $jsonMapper);

            $offeringResult = new BP\OfferingResult();
            $offeringResult->vendorId = $config['vendor_id'];

    // set transaction information
    $offeringResult->offeringId = $premium_id;
    $offeringResult->vendorSaleReference = $booking['booking_no'];//'Your Sales Reference/Invoice Number';
    $offeringResult->customerSurname = $booking['buyer_last_name'];
    $offeringResult->customerForename = $booking['buyer_first_name'];
    $offeringResult->emailAddress = $booking['email'];

    // set customer's choice - did they want to protect their purchase?
    foreach ($productOfferings as $productOffering) {
        $sale = new BP\ProductOfferingResult();
        $sale->productOfferingId = $productOffering->id;
        $sale->sold = true;

        $offeringResult->sales[] = $sale;
    } 
        $status = 3;
        try{
        if ($data = $client->submitOfferingResult($offeringResult)){
        $msg =  "Offering result submit successfully";
        $status = 1;
        }else{
        $msg = "Result Submission Failed.";
        }
        }
        catch(BP\InsureHubApiNotFoundException $validationException){
        $msg = $validationException->errorMessage();
        }
        catch(BP\InsureHubApiValidationException $validationException){
        $msg = $validationException->errorMessage();
        }
        catch(BP\InsureHubApiAuthorisationException $authorisationException){
        $msg = $authorisationException->errorMessage();
        }
        catch(BP\InsureHubApiAuthenticationException $authenticationException){
        $msg = $authenticationException->errorMessage();
        }
        catch(BP\InsureHubException $insureHubException){
        $msg =  $insureHubException->errorMessage();
        }
        catch(Exception $exception){
        $msg =  $exception->getMessage();
        }
         $response = array('status' => $status,'msg' => $msg);
         echo "<pre>";print_r($response);exit;
        }

    }


    public function checkout_june17(Request $request,$lang)
    { 
        $cartId = Session::get('cart_id');
        if($cartId == ""){
            return redirect('');
        }
        $cart_response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'cart?lang='.Session::get('locale')."&currency=".Session::get('currency'),[
            'cart_id' => Session::get('cart_id')
            ]);
        $results = $cart_response['result'];
        if($results){


            //echo "<pre>";print_r($results);exit;
            // $minutes = (strtotime($results['expriy_datetime']) - time()) / 60;
            // if($minutes < 0 )
            // {
            //     $delete = $this->cart_delete($request);
            //     Session::forget('cart_id');
            //     return redirect('');
            // }
            $country_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->get(API_URL .'country');
            $country = $country_response['results'];
            $states ="";
            if(Session::get('country')){
                $state_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->post(API_URL .'state?lang='.Session::get('locale'),[
                    'country_id' => Session::get('country')
                    ]);
                $states = $state_response['results'];
            }
            $title =  "Checkout";
            $description =  "Checkout";
            return view('frontend.payment.checkout',compact('results','country','cartId','title','description','states'));
        }
        else{
             Session::forget('cart_id');
            Session::forget('cart_quantity');
            return redirect(app()->getLocale());
        }
    }

 public function get_cart_data(Request $request,$lang)
    { 


        $cartId = Session::get('cart_id');
        
        $cart_response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'cart?lang='.Session::get('locale')."&currency=".Session::get('currency'),[
            'cart_id' => Session::get('cart_id')
            ]);
        $results = $cart_response['result'];
       
        if($results){

            $booking_protect_content =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
            ])->get(API_URL .'booking_protect_content?lang='.Session::get('locale'));


             //echo "<pre>";print_r($booking_protect_content['result']);exit;

            $protection_content = str_replace("{{premium_price}}",$results['premium_price_sym'],@$booking_protect_content['result']);

              $cart_ticket_price = view('frontend.ajax.cart_ticket_price',compact('results'))->render();
              $cart_ticket_protect = view('frontend.ajax.cart_ticket_protect',compact('results','protection_content'))->render();

                return response()->json( array('success' => true, 'cart_ticket_price'=>$cart_ticket_price,'cart_ticket_protect' => $cart_ticket_protect) );


        }
       
    }


public function updateadyenresp_creditnote($booking_no){

   
    if($booking_no != ''){
     
      $booking_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->post(API_URL .'booking_details?lang='.Session::get('locale'),[
            'booking_no' => md5($booking_no),
            ]); 
     $booking = $booking_response['booking'];
   
     $payment_status = 1;

        $premium_status = $booking['premium_subscription'];
        if($booking['premium_subscription'] == 2 && $payment_status == 1){
                $premium = $this->submit_booking_protect($booking['booking_no']);
                $premium_status = $premium['status'];
        }

            
        $response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'payment_update?lang='.Session::get('locale'), [
                                'booking_id'        => $booking['bg_id'],
                                'payment_type'      => 1,
                                'payment_status'    => $payment_status,
                                'payment_from'      => 'direct',
                                'transcation_id'    => $booking['booking_no'],
                                'premium_subscription' => $premium_status,
                                'payment_response'  => '',
                                'total_payment'     => $booking['total_amount'],
                                'message'           => 'Authorised',
                                'currency_code'     => $booking['currency_type'],
                                'ip_address'        => get_client_ip(),
                ]);
     
            Session::forget('cart_id');
            Session::forget('cart_quantity');
            $payment_url = url(app()->getLocale().'/confirmation/'.md5($booking['booking_no']));
            $response = ['payment_url' => $payment_url,'status' => 1,'msg' => 'success','payment_method' => $booking['payment_method'],'api_id' => @$booking['api_id']];
            return $response;
    }
    
}

public function updateadyenresp(Request $request,$lang){

   
    if($request->booking_no != ''){
      
      $payment_result =  $request->result;
      if(!empty($payment_result)){
      $booking_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->post(API_URL .'booking_details?lang='.Session::get('locale'),[
            'booking_no' => md5($request->booking_no),
            ]); 
     $booking = $booking_response['booking'];

     $booking_logs = array('step' => 4,'time_stamp' => date('d-m-Y h:i:s'),'msg' => 'Payment Response Received from Adyen.'.json_encode($payment_result));
         booking_log($booking['booking_no'],$booking_logs);
   
     if(@$payment_result['resultCode']){

                if($payment_result['resultCode'] == 'Authorised'){
                    $payment_status = 1;
                }
                else if($payment_result['resultCode'] == 'Pending'){
                    $payment_status = 2;
                }
                else if($payment_result['resultCode'] == 'Received'){
                    $payment_status = 2;
                }
                else if($payment_result['resultCode'] == 'Refused'){
                    $payment_status = 0;
                }
                else {
                    $payment_status = 0;
                }

            }
            else{
                $payment_status = 0;
            }    

        $booking_logs = array('step' => 5,'time_stamp' => date('d-m-Y h:i:s'),'msg' => 'Payment Result code - '.@$payment_result['resultCode']);
         booking_log($booking['booking_no'],$booking_logs);

        $premium_status = $booking['premium_subscription'];
        if($booking['premium_subscription'] == 2 && $payment_status == 1){
                $premium = $this->submit_booking_protect($booking['booking_no']);
                $premium_status = $premium['status'];
        }

            
                                

        $response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'payment_update?lang='.Session::get('locale'), [
                                'booking_id'        => $booking['bg_id'],
                                'payment_type'      => 1,
                                'payment_status'    => $payment_status,
                                'payment_from'      => 'direct',
                                'transcation_id'    => $booking['booking_no'],
                                'premium_subscription' => $premium_status,
                                'payment_response'  => @$payment_result['sessionData'],
                                'total_payment'     => $booking['total_amount'],
                                'message'           => @$payment_result['resultCode'],
                                'currency_code'     => $booking['currency_type'],
                                'ip_address'        => get_client_ip(),
                ]);

            $booking_logs = array('step' => 6,'time_stamp' => date('d-m-Y h:i:s'),'msg' => 'Payment Update -'.json_encode($payment_result));
         booking_log($booking['booking_no'],$booking_logs);

     
            Session::forget('cart_id');
            Session::forget('cart_quantity');
            if($payment_status == 0){
                $payment_url = url(app()->getLocale().'/failed/'.md5($booking['booking_no']));
                if($_GET['sessionId'] != "" && $_GET['redirectResult'] != ""){
                    return redirect($payment_url);
                }
            }
            else{
                $payment_url = url(app()->getLocale().'/confirmation/'.md5($booking['booking_no']));
            }
            $response = ['payment_url' => $payment_url,'status' => 1,'msg' => 'success'];
            return response($response, 200);

    }
    }
    
}

    public function payment_success(Request $request,$lang,$booking_no){

            $booking_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->post(API_URL .'booking_details?lang='.Session::get('locale'),[
            'booking_no' => $booking_no,
            ]); 
         
            $booking = $booking_response['booking'];
            if(@$_GET['error'] != 'error'){
                if(@$_GET['payment_intent'] != ''){
              
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                $paymentres = $stripe->paymentIntents->retrieve(
                $_GET['payment_intent']
                );
                $paymentIntent = $paymentres->charges->data[0];
                 


         if ($paymentIntent->amount_refunded == 0 && empty($paymentIntent->failure_code) && $paymentIntent->paid == 1 && $paymentIntent->captured == 1) {

                $amount = $paymentIntent->amount_captured;
                $balance_transaction = $paymentIntent->balance_transaction;
                $currency = $paymentIntent->currency;
                $paymentstatus = $paymentIntent->status;

                if($paymentstatus == 'succeeded'){
                    $payment_status = 1;
                }
                else if($paymentstatus == 'failed'){
                    $payment_status = 0;
                }
                else if($paymentstatus == 'pending'){
                    $payment_status = 2;
                }
                else if($paymentstatus == 'reversed'){
                    $payment_status = 3;
                }
                else if($paymentstatus == 'canceled'){
                    $payment_status = 4;
                }

                 
             
        $response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'payment_update?lang='.Session::get('locale'), [
                                'booking_id'        => $booking['bg_id'],
                                'payment_type'      => 1,
                                'payment_status'    => $payment_status,
                                'transcation_id'    => $balance_transaction,
                                'payment_response'  => json_encode($paymentres),
                                'total_payment'     => $amount/100,
                                'message'           => 'success',
                                'currency_code'     => $currency,
                                'ip_address'        => get_client_ip(),
                ]); 

         }
         else{


                $amount = $paymentIntent->amount;
                $balance_transaction = $paymentIntent->balance_transaction;
                $currency = $paymentIntent->currency;
                $paymentstatus = $paymentIntent->status;

                if($paymentstatus == 'succeeded'){
                    $payment_status = 1;
                }
                else if($paymentstatus == 'failed'){
                    $payment_status = 0;
                }
                else if($paymentstatus == 'pending'){
                    $payment_status = 2;
                }
                else if($paymentstatus == 'reversed'){
                    $payment_status = 3;
                }
                else if($paymentstatus == 'canceled'){
                    $payment_status = 4;
                }


            $response =  Http::withHeaders([
                    'Accept' => 'application/json',
                    'domainkey' => DOMAIN_KEY
                ])->post(API_URL .'payment_update?lang='.Session::get('locale'), [
                                'booking_id' => $booking['bg_id'],
                                'payment_type' => 1,
                                'payment_status' => $payment_status,
                                'transcation_id' => $balance_transaction,
                                'payment_response' => json_encode($paymentIntent),
                                'message' => 'unknown',
                                'total_payment' => $amount/100,
                                'currency_code' => $currency,
                                'ip_address'        => get_client_ip(),
                ]); 
         }

        Session::forget('cart_id');
        Session::forget('cart_quantity');

         return redirect(app()->getLocale().'/confirmation/'.md5($booking['booking_no']));

            }
        }
        else{

                  $response =  Http::withHeaders([
                            'Accept' => 'application/json',
                            'domainkey' => DOMAIN_KEY
                        ])->post(API_URL .'payment_update?lang='.Session::get('locale'), [
                                'booking_id' => $booking['bg_id'],
                                'payment_type' => 1,
                                'payment_status' => 0,
                                'transcation_id' => '',
                                'payment_response' => json_encode($_GET['payment_intent']),
                                'message' => 'unknown',
                                'total_payment' => 0,
                                'currency_code' => '',
                                'ip_address'        => get_client_ip(),
                ]); 

                 Session::forget('cart_id');
                Session::forget('cart_quantity');

                return redirect(app()->getLocale().'/confirmation/'.md5($booking['booking_no']));
            }
                

               
        
    }

    public function card_process(Request $request){
           // ini_set('memory_limit', '64M');
           // echo $request->booking_no;exit;
            $booking_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->post(API_URL .'booking_details?lang='.Session::get('locale'),[
            'booking_no' => $request->booking_no,
            ]); 
         
            $booking = $booking_response['booking'];


            if($booking['bg_id'] != ''){

            

             try {
                
                $network_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
                ])->post(API_URL .'network_response?lang='.Session::get('locale'),[
                'booking_id' => md5($booking['bg_id']),
                ]); //echo "<pre>";print_r($network_response['response']);exit;

                }
                 catch (\Throwable $t) {
                    custom_error_log($t->getMessage());
                 }
                 catch (\Stripe\Exception\ApiErrorException $e) {
                custom_error_log($e->getError()->message);
                } catch (Exception $e) {
                custom_error_log($e);
                } 

             try { 

            $access_token        = $network_response['response']['token'];
            $orderCreateResponse = json_decode($network_response['response']['response']);
            $cardpay_url = $orderCreateResponse->_embedded->payment[0]->_links->{'payment:card'}->href;
            } 
            catch (\Throwable $t) {
                custom_error_log($t->getMessage());
               // echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";exit;
            }
            catch (\Stripe\Exception\ApiErrorException $e) {
                custom_error_log($e->getError()->message);
            } catch (Exception $e) {
                custom_error_log($e);
            } 
            $postData = (object)[];
            $postData->pan = $request->card_number;
            $postData->expiry = $request->expiryYear.'-'.$request->expiryMonth;
            $postData->cvv = $request->cvv;
            $postData->cardholderName = $request->card_holder_name;
            $json = json_encode($postData);
            $orderCreateHeaders  = array("Authorization: Bearer ".$access_token, "Content-Type: application/vnd.ni-payment.v2+json", "Accept: application/vnd.ni-payment.v2+json");
           
             try {
                $orderCreateResponse = $this->invokeCurlRequest("PUT", $cardpay_url, $orderCreateHeaders, $json);

                }
                catch (\Throwable $t) {
                custom_error_log($t->getMessage());
               // echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";exit;
            }
                 catch (\Stripe\Exception\ApiErrorException $e) {
                custom_error_log($e->getError()->message);
                } catch (Exception $e) {
                custom_error_log($e);
                } 
            $output = json_decode($orderCreateResponse);
            //echo "<pre>";print_r($output);exit;
            $state = $output->state;

            

             try {
                
                $update_payment_id =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
                ])->post(API_URL .'payment_create?lang='.Session::get('locale'),[
                'booking_id' => $booking['bg_id'],
                'payment_method' => PAYMENT_METHOD,
                'payment_id'   => $output->orderReference
                ]); 

                }
                catch (\Throwable $t) {
                custom_error_log($t->getMessage());
               // echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";exit;
                }
                 catch (\Stripe\Exception\ApiErrorException $e) {
                custom_error_log($e->getError()->message);
                } catch (Exception $e) {
                custom_error_log($e);
                }

            if ($state == "AWAIT_3DS") {

            $cnp3ds_url = $output->_links->{'cnp:3ds'}->href;
            $acsurl = $output->{'3ds'}->acsUrl;
            $acspareq = $output->{'3ds'}->acsPaReq;
            $acsmd = $output->{'3ds'}->acsMd;
            $acsterm = url(app()->getLocale()."/networkPaRes");
            }
            else{
         $res = json_decode($orderCreateResponse,true);

        $payment_status = 0;

        if($state == "PURCHASED" || $state == "CAPTURED"){
            $payment_status = 1;
        }
        else if($state == "FAILED"){
            $payment_status = 0;
        }
        $amount = $res['amount']['value'];
        $currency = $res['amount']['currencyCode'];
        $balance_transaction = $res['reference'];
    
        $post_data = [
                        'booking_id' => $booking['bg_id'],
                        'payment_type' => 1,
                        'payment_status' => $payment_status,
                        'transcation_id' => $balance_transaction,
                        'payment_response' => json_encode($orderCreateResponse),
                        'total_payment' => $amount/100,
                        'message' => $state,
                        'currency_code' => $currency
                ];

        //pr(json_encode($post_data)); die;
        $response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->post(API_URL .'payment_update?lang='.Session::get('locale'), $post_data); 
                $payment_url = url(app()->getLocale().'/confirmation/'.MD5($booking['booking_no']));
                $response = ["booking_id" => $booking['bg_id'],'payment_url' => $payment_url,'status' => 1,'msg' => 'success'];
                return response($response, 200);

            }
            $payment_url = url(app()->getLocale()."/ds3/".base64_encode($acsurl).'/'.base64_encode($acsterm).'/'.base64_encode($acspareq).'/'.base64_encode($acsmd).'/'.base64_encode($cnp3ds_url).'/'.md5($booking['booking_no']));
            $response = ["booking_id" => $booking['bg_id'],'payment_url' => $payment_url,'status' => 1,'msg' => 'success'];
          //  echo $payment_url;exit;
            return response($response, 200);


             }

            
           
    }

    public function networkPaRes($lang,$returnres,$booking_id)
    {  

        $booking_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->post(API_URL .'booking_details?lang='.Session::get('locale'),[
            'booking_no' => $booking_id,
            ]); 
        $booking = $booking_response['booking'];
        //pr($booking);die;
        //echo $lang.'='.$returnres.'='.$booking_id;
        //echo "<pre>";print_r($_POST);exit;
         $pares=$_POST['PaRes'];
        $returnres=base64_decode($returnres);
        $postData = (object)[];
        $postData->PaRes = $pares;

        $json = json_encode($postData);
        $token='';
        
        $orderCreateHeaders  = array("Authorization: Bearer ".$token, "Content-Type: application/vnd.ni-payment.v2+json", "Accept: application/vnd.ni-payment.v2+json");

        
                 try {
                
               $orderCreateResponse = $this->invokeCurlRequest("POST", $returnres, $orderCreateHeaders, $json);

                }
                catch (\Throwable $t) {
                //custom_error_log($t->getMessage());
               // echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";exit;
                }
                 catch (\Stripe\Exception\ApiErrorException $e) {
                //custom_error_log($e->getError()->message);
                } catch (Exception $e) {
                //custom_error_log($e);
                }

        $res = json_decode($orderCreateResponse,true);
    
        $payment_status = 0;
        if($res['state'] == "PURCHASED" || $res['state'] == "CAPTURED"){
            $payment_status = 1;
        }
        else if($res['state'] == "FAILED"){
            $payment_status = 0;
        }
        $amount = $res['amount']['value'];
        $currency = $res['amount']['currencyCode'];
        $balance_transaction = $res['reference'];
    
        $post_data = [
                        'booking_id' => $booking['bg_id'],
                        'payment_type' => 1,
                        'payment_status' => $payment_status,
                        'transcation_id' => $balance_transaction,
                        'payment_response' => json_encode($orderCreateResponse),
                        'total_payment' => $amount/100,
                        'message' => 'success',
                        'currency_code' => $currency
                ];

        //pr(json_encode($post_data)); die;
        $response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->post(API_URL .'payment_update?lang='.Session::get('locale'), $post_data); 
        return redirect(app()->getLocale().'/confirmation/'.MD5($booking['booking_no']));
    }


    public function ds3($lang,$acsUrl,$TermUrl,$PaReq,$MD,$current_res,$booking_id)
    { 
         $acsUrl         = base64_decode($acsUrl);
         $TermUrl        = base64_decode($TermUrl);
         $PaReq          = base64_decode($PaReq);
         $MD             = base64_decode($MD);
         $current_res    = base64_decode($current_res);
         $booking_id            = $booking_id;
         // echo "<pre>";print_r($acsUrl);exit;
        return view('frontend.payment.ds3',compact('acsUrl','TermUrl','PaReq','MD','current_res','booking_id'));

    }

    function v4_UUID() {
    return 'UNIQUE-ID-'.sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      // 32 bits for the time_low
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),
      // 16 bits for the time_mid
      mt_rand(0, 0xffff),
      // 16 bits for the time_hi,
      mt_rand(0, 0x0fff) | 0x4000,

      // 8 bits and 16 bits for the clk_seq_hi_res,
      // 8 bits for the clk_seq_low,
      mt_rand(0, 0x3fff) | 0x8000,
      // 48 bits for the node
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
  }

   public function attendee_post(Request $request)
    {
       
        $response =  Http::withHeaders([
                        'Accept' => 'application/json',
                        'domainkey' => DOMAIN_KEY
                    ])->post(API_URL .'update_applynominee?lang='.Session::get('locale'), [
                        'first_name'  => $request->post('attendee_firstname'),
                        'last_name'   => @$request->post('attendee_lastname'),
                        'email'       => @$request->post('attendee_email'),
                        'phone'       => @$request->post('attendee_phone'),
                        'dob'         => @$request->post('attendee_dob'),
                        'passport'    => @$request->post('attendee_passport'),
                        'nationality' => @$request->post('nationality'),
                        'gender'      => @$request->post('attendee_gender'),
                        'booking_id'  => md5($request->post('bookings_id')),
        ]);    
        if($response->successful() == 200){

                $booking_response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'booking_details?lang='.Session::get('locale'),[
            'booking_no' => md5('1BX'.$request->post('bookings_id')),
            'store_id'   => 13
            ]); 
            $booking = $booking_response['booking'];//echo "<pre>";print_r($booking);exit;
            if($booking['total_amount'] <= 0 && $booking['api_id'] == 3){

                        $payment_method = 'COUPON';
                    if(@$booking['credit_note'] == 1){
                        $payment_method = 'CREDITNOTE';
                    }
               
                $update_payment_id =  Http::withHeaders([
                    'Accept' => 'application/json',
                    'domainkey' => DOMAIN_KEY
                    ])->post(API_URL .'payment_create?lang='.Session::get('locale'),[
                    'booking_id' => $booking['bg_id'],
                    'payment_method' => $payment_method,
                    'payment_id'   => time()
                    ]); 
                    $booking_no    = md5($booking['booking_no']);
                    $bookingres    = $this->updateadyenresp_creditnote($booking['booking_no']);
                    //echo "bookingres <pre>";print_r($bookingres);exit;
                    if(!empty($bookingres)){
                        return response($bookingres, 200);
                    }
                    
                }

               $response = ["message" => 'success' ,'status' => 1];
           
           
        }
        else{
             $response = ["message" => 'success' ,'status' => 1];
        }
         return response($response, 200);  
    }

    public function attendee_post_17_05_2023(Request $request)
    {
       
        $response =  Http::withHeaders([
                        'Accept' => 'application/json',
                        'domainkey' => DOMAIN_KEY
                    ])->post(API_URL .'update_applynominee?lang='.Session::get('locale'), [
                        'first_name'  => $request->post('attendee_firstname'),
                       // 'last_name'  => $request->post('attendee_lastname'),
                        'email'  => $request->post('attendee_email'),
                        'booking_id'         => md5($request->post('bookings_id')),
        ]);
              ///   echo "<pre>";print_r($response['result']);exit;
        if($response->successful() == 200){

               $response = ["message" => 'success' ,'status' => 1];
           
           
        }
        else{
             $response = ["message" => 'success' ,'status' => 1];
        }
         return response($response, 200);  
    }

     public function checkout_post(Request $request)
    {
        $response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'checkout?lang='.Session::get('locale'), [
            'cart_id'       => base64_decode($request->cart_id),
            //'title'         => $request->title,
            'title'         => 'Mr',
            'user_id'       => Session::get('user_id'),
            'first_name'    => $request->firstname,
            'last_name'     => $request->lastname,
            'password'      => $request->password,
            'cpassword'     => $request->cpassword,
            'email'         => $request->email,
            'dialing_code'  => $request->dialling_code,
            'mobile_no'     => $request->phone_number,
            'address'       => @$request->address,
            'postal_code'   => @$request->postcode,
            'country_id'    => @$request->country,
            'state_id'      => @$request->city,
            'subscribe'      => $request->subscribe,
            'premium_subscription'      => $request->booking_protect,
            'ip_address'    => get_client_ip(),
            'user_token'    => Session::get('user_token_id'),
            'r_cart_coupon' => $request->r_cart_coupon,
        ]); 

 

        if(get_client_ip() == "182.76.247.76"){
            
        //echo $response;exit;
        }

        if(isset($response['booking_id'])) {
             
             $booking_logs = array('step' => 1,'time_stamp' => date('d-m-Y h:i:s'),'msg' => 'Booking Initiated from checkout.');
            booking_log($response['booking_no'],$booking_logs);

             $booking_response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'booking_details?lang='.Session::get('locale'),[
            'booking_no' => md5($response['booking_no']),
            'store_id'   => 13
            ]); 
            
                $booking = $booking_response['booking'];
                $payment_price    = $booking['total_amount'];
                $payment_currency = $booking['currency_type'];
                $coupon_code      = $booking['coupon_code'];
                $quantity         = $booking['quantity'];
                $guestdata        = $booking['guestdata'];
               // echo 'payment_price = '.$payment_price;exit;
              // echo "<pre>";print_r($booking);exit;
                if($booking['total_amount'] <= 0 && $booking['api_id'] != 3){

                        $payment_method = 'COUPON';
                    if(@$booking['credit_note'] == 1){
                        $payment_method = 'CREDITNOTE';
                    }
               
                $update_payment_id =  Http::withHeaders([
                    'Accept' => 'application/json',
                    'domainkey' => DOMAIN_KEY
                    ])->post(API_URL .'payment_create?lang='.Session::get('locale'),[
                    'booking_id' => $response['booking_id'],
                    'payment_method' => $payment_method,
                    'payment_id'   => time()
                    ]); 
                    $booking_no    = md5($response['booking_no']);
                    $bookingres    = $this->updateadyenresp_creditnote($response['booking_no']);
                    if(!empty($bookingres)){
                        return response($bookingres, 200);
                    }
                    
                }
                else{
                if(PAYMENT_METHOD == 'ADYEN'){

                    try {
                
                    $reference = $response['booking_no'];
                    $requestOptions['idempotencyKey'] = $this->v4_UUID();
                    $params = array(
                    'amount' => array(
                    'currency' => $payment_currency,
                    'value' => $payment_price*100
                    ),
                    'countryCode' => 'AE',
                    'merchantAccount' => env('ADYEN_MERCHANT_ACCOUNT'),
                    'reference' => $reference,
                    'returnUrl' => url(app()->getLocale()."/updateadyenresp/{$response['booking_no']}")
                    );
                    $result = $this->checkout->sessions($params,$requestOptions);
                    //echo "<pre>";print_r($result);exit;
                    $booking_logs = array('step' => 2,'time_stamp' => date('d-m-Y h:i:s'),'msg' => 'Booking Adyen Session Initiated.'.json_encode($result));
                    booking_log($response['booking_no'],$booking_logs);
                }
                catch (\Throwable $t) {
                $booking_logs = array('step' => 2,'time_stamp' => date('d-m-Y h:i:s'),'msg' => $t->getMessage());
                    booking_log($response['booking_no'],$booking_logs);
                custom_error_log($t->getMessage());
               // echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";exit;
                }
                 catch (\Stripe\Exception\ApiErrorException $e) {
                $booking_logs = array('step' => 2,'time_stamp' => date('d-m-Y h:i:s'),'msg' => $e->getError()->message);
                booking_log($response['booking_no'],$booking_logs);
                custom_error_log($e->getError()->message);

                } catch (Exception $e) {
                $booking_logs = array('step' => 2,'time_stamp' => date('d-m-Y h:i:s'),'msg' => $e);
                booking_log($response['booking_no'],$booking_logs);
                custom_error_log($e);

                }    

                  $update_payment_id =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
                ])->post(API_URL .'payment_create?lang='.Session::get('locale'),[
                'booking_id' => $response['booking_id'],
                'payment_method' => PAYMENT_METHOD,
                'payment_id'   => $result['id']
                ]); 

                $booking_logs = array('step' => 3,'time_stamp' => date('d-m-Y h:i:s'),'msg' => 'Payment create initialised.');
                booking_log($response['booking_no'],$booking_logs);

                $booking_no    = md5($response['booking_no']);
                $payment_url = url(app()->getLocale()."/updateadyenresp/{$booking_no}");

                $country_response =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
            ])->get(API_URL .'country?sort_by=name');
            $country = $country_response['results'];
            
            $attendee_form = "";
            if($guestdata != ""){
                $guestsdata = json_decode($guestdata,1);
                $attendee_form =  view('frontend.payment.attendee_form',compact('quantity','country','guestsdata'))->render();
            }
                

                $response = ["booking_id" =>$response['booking_id'],"booking_no" => $response['booking_no'],"sessionData" => $result['sessionData'],"sessionid" => $result['id'],"tournament" => $booking['tournament_id'],"payment_url" => $payment_url,'payment_method' => PAYMENT_METHOD,'status' => 1,'attendee_form' => $attendee_form];
                return response($response, 200);
                }
                else if(PAYMENT_METHOD == 'stripe'){

                try {
                
                $order_amount = round($payment_price * 100);
               
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

                $paymentIntent = $stripe->paymentIntents->create(
                [
                'amount' => $order_amount,
                'currency' => $payment_currency,
               // 'automatic_payment_methods' => ['enabled' => true],
                "payment_method_types" => [
                "card"
                ]
                ]
                ); 
                $payment_id = $paymentIntent->id;
                
                }
                catch (\Throwable $t) {
                custom_error_log($t->getMessage());
               // echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";exit;
                }
                 catch (\Stripe\Exception\ApiErrorException $e) {
                
                custom_error_log($e->getError()->message);

                } catch (Exception $e) {

                custom_error_log($e);

                }    

                $update_payment_id =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
                ])->post(API_URL .'payment_create?lang='.Session::get('locale'),[
                'booking_id' => $response['booking_id'],
                'payment_method' => PAYMENT_METHOD,
                'payment_id'   => $payment_id
                ]); 

                $payment_token = $paymentIntent->client_secret;
                $booking_no    = md5($response['booking_no']);
                $payment_url = url(app()->getLocale()."/payment_success/{$booking_no}");
                $response = ["booking_id" =>$response['booking_id'],"payment_token" => $payment_token,"tournament" => $booking['tournament_id'],"payment_url" => $payment_url,'payment_method' => PAYMENT_METHOD,'status' => 1];
                return response($response, 200);
            }
            else if(PAYMENT_METHOD == 'network'){

                try {
                $payment_token = $this->create_access_token($response['booking_id'],$booking);

                }
                catch (\Throwable $t) { 
                custom_error_log($t->getMessage());
               // echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";exit;
                }
                 catch (\Stripe\Exception\ApiErrorException $e) {
                custom_error_log($e->getError()->message);
                } catch (Exception $e) {
                custom_error_log($e);
                }    


                $booking_no    = md5($response['booking_no']);
                $payment_url = url(app()->getLocale()."/card_process");
                $response = ["booking_id" => $response['booking_id'],'booking_no' => $booking_no,"payment_token" => $payment_token,"tournament" => $booking['tournament_id'],"payment_url" => $payment_url,'payment_method' => PAYMENT_METHOD,'status' => 1];
                return response($response, 200);
            }
             else if(PAYMENT_METHOD == 'ETISALAT'){

                try {
                $payment_response = $this->create_transaction($response['booking_id'],$booking);
                $Transaction = json_decode($payment_response,1);
               //echo "<pre>";print_r($booking);exit;
                $payment_token = $Transaction['Transaction']['TransactionID'];
                $payment_url = $Transaction['Transaction']['PaymentPortal'];
                 $update_payment_id =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
                ])->post(API_URL .'payment_create?lang='.Session::get('locale'),[
                'booking_id' => $booking['bg_id'],
                'payment_method' => PAYMENT_METHOD,
                'payment_id'   => $Transaction['Transaction']['TransactionID']
                ]); 

                }
                catch (\Throwable $t) {
                custom_error_log($t->getMessage());
               // echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";exit;
                }
                 catch (\Stripe\Exception\ApiErrorException $e) {
                custom_error_log($e->getError()->message);
                } catch (Exception $e) {
                custom_error_log($e);
                }
                
            $booking_no    = md5($response['booking_no']);
            $response = ["booking_id" => $response['booking_id'],'booking_no' => $booking_no,"payment_token" => $payment_token,"payment_url" => $payment_url,'payment_method' => PAYMENT_METHOD,'status' => 1];
             return response($response, 200);
            }
        }

        }
        else{
            if($response['error_code']==-1){
                $response = ["message" => $response['message'] ,'status' => -1];
            }else{
                $response = ["message" => $response['message'] ,'status' => 0];
            }
            return response($response, 200);
        }
    }
    
    public function checkout_post_jun17(Request $request)
    { //echo "PAYMENT_METHOD = ".PAYMENT_METHOD;exit;
        $response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'checkout?lang='.Session::get('locale'), [
            'cart_id'       => base64_decode($request->cart_id),
            'title'         => $request->title,
            'user_id'         => Session::get('user_id'),
            'first_name'    => $request->firstname,
            'last_name'     => $request->lastname,
            'email'         => $request->email,
            'dialing_code'  => $request->dialling_code,
            'mobile_no'     => $request->phone_number,
            'address'       => $request->address,
            'postal_code'   => $request->postcode,
            'country_id'    => $request->country,
            'state_id'      => $request->city,
            'ip_address'    => get_client_ip()
        ]); 
        
        if(isset($response['booking_id'])) {
            // echo "<pre>";print_r($response['booking_no']);exit;
             $booking_response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'booking_details?lang='.Session::get('locale'),[
            'booking_no' => md5($response['booking_no']),
            'store_id'   => 13
            ]); 
         
                $booking = $booking_response['booking'];
                $payment_price    = $booking['total_amount'];
                $payment_currency = $booking['currency_type'];
               
                if(PAYMENT_METHOD == 'ADYEN'){

                    try {
                
                    $reference = $response['booking_no'];
                    $params = array(
                    'amount' => array(
                    'currency' => $payment_currency,
                    'value' => $payment_price*100
                    ),
                    'countryCode' => 'AE',
                    'merchantAccount' => env('ADYEN_MERCHANT_ACCOUNT'),
                    'reference' => $reference,
                    'returnUrl' => url(app()->getLocale()."/updateadyenresp/{$response['booking_no']}")
                    );
                    $result = $this->checkout->sessions($params);
                    //echo "<pre>";print_r($result);exit;
                
                }
                catch (\Throwable $t) {
                custom_error_log($t->getMessage());
               // echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";exit;
                }
                 catch (\Stripe\Exception\ApiErrorException $e) {
                
                custom_error_log($e->getError()->message);

                } catch (Exception $e) {

                custom_error_log($e);

                }    

                  $update_payment_id =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
                ])->post(API_URL .'payment_create?lang='.Session::get('locale'),[
                'booking_id' => $response['booking_id'],
                'payment_method' => PAYMENT_METHOD,
                'payment_id'   => $result['id']
                ]); 

                $booking_no    = md5($response['booking_no']);
                $payment_url = url(app()->getLocale()."/updateadyenresp/{$booking_no}");
                $response = ["booking_id" =>$response['booking_id'],"booking_no" => $response['booking_no'],"sessionData" => $result['sessionData'],"sessionid" => $result['id'],"payment_url" => $payment_url,'payment_method' => PAYMENT_METHOD,'status' => 1];
                return response($response, 200);
                }
                else if(PAYMENT_METHOD == 'stripe'){

                try {
                
                $order_amount = round($payment_price * 100);
               
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

                $paymentIntent = $stripe->paymentIntents->create(
                [
                'amount' => $order_amount,
                'currency' => $payment_currency,
               // 'automatic_payment_methods' => ['enabled' => true],
                "payment_method_types" => [
                "card"
                ]
                ]
                ); 
                $payment_id = $paymentIntent->id;
                
                }
                catch (\Throwable $t) {
                custom_error_log($t->getMessage());
               // echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";exit;
                }
                 catch (\Stripe\Exception\ApiErrorException $e) {
                
                custom_error_log($e->getError()->message);

                } catch (Exception $e) {

                custom_error_log($e);

                }    

                $update_payment_id =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
                ])->post(API_URL .'payment_create?lang='.Session::get('locale'),[
                'booking_id' => $response['booking_id'],
                'payment_method' => PAYMENT_METHOD,
                'payment_id'   => $payment_id
                ]); 

                $payment_token = $paymentIntent->client_secret;
                $booking_no    = md5($response['booking_no']);
                $payment_url = url(app()->getLocale()."/payment_success/{$booking_no}");
                $response = ["booking_id" =>$response['booking_id'],"payment_token" => $payment_token,"payment_url" => $payment_url,'payment_method' => PAYMENT_METHOD,'status' => 1];

            }
            else if(PAYMENT_METHOD == 'network'){

                try {
                $payment_token = $this->create_access_token($response['booking_id'],$booking);

                }
                catch (\Throwable $t) { 
                custom_error_log($t->getMessage());
               // echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";exit;
                }
                 catch (\Stripe\Exception\ApiErrorException $e) {
                custom_error_log($e->getError()->message);
                } catch (Exception $e) {
                custom_error_log($e);
                }    


                $booking_no    = md5($response['booking_no']);
                $payment_url = url(app()->getLocale()."/card_process");
                $response = ["booking_id" => $response['booking_id'],'booking_no' => $booking_no,"payment_token" => $payment_token,"payment_url" => $payment_url,'payment_method' => PAYMENT_METHOD,'status' => 1];
            }

                return response($response, 200);
            }
        else{
            $response = ["message" => $response['message'] ,'status' => 0];
            return response($response, 200);
        }
    }


    public function create_access_token($booking_id,$booking){

                $payment_price    = $booking['total_amount'];
                $payment_currency = $booking['currency_type'];

                $apikey        = env('NETWORK_API_KEY');
                $txnServiceURL = env('NETWORK_URL')."/identity/auth/access-token";
                $tokenHeaders  = array("Authorization: Basic ".$apikey, "Content-Type: application/vnd.ni-identity.v1+json");
                //echo $txnServiceURL;exit;
                try {
                $tokenResponse = $this->invokeCurlRequest("POST", $txnServiceURL, $tokenHeaders, '');

                }
                catch (\Throwable $t) { //echo $t->getMessage();exit;
                custom_error_log($t->getMessage());
               // echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";exit;
                }
                 catch (\Stripe\Exception\ApiErrorException $e) {
                custom_error_log($e->getError()->message);
                } catch (Exception $e) {
                custom_error_log($e);
                } 

                $tokenResponse = json_decode($tokenResponse);
                $access_token  = $tokenResponse->access_token;

                if(strtoupper($payment_currency) == 'GBP'){
                    $outletRef   = env('NETWORK_OUTLET_REF_GBP');
                }
                else if(strtoupper($payment_currency) == 'EUR'){
                    $outletRef   = env('NETWORK_OUTLET_REF_EUR');
                }
                else{
                    $outletRef   = env('NETWORK_OUTLET_REF_GBP');
                }
                

                $post_data = 

                array(
                'action' => 'PURCHASE',
                'amount' => array(
                'currencyCode' => strtoupper($payment_currency),
                'value' => $payment_price*100)
                )
                ;
                $order = json_encode($post_data); 

                $txnServiceURL = env('NETWORK_URL')."/transactions/outlets/".$outletRef."/orders";        
                $txnHeaders  = array("Authorization: Bearer ".$access_token, "Content-Type: application/vnd.ni-payment.v2+json", "Accept: application/vnd.ni-payment.v2+json");

                
                 try {
                $txnResponse = $this->invokeCurlRequest("POST", $txnServiceURL, $txnHeaders, $order);

                } 
                catch (\Throwable $t) {
                custom_error_log($t->getMessage());
               // echo $t->getMessage(), " at ", $t->getFile(), ":", $t->getLine(), "\n";exit;
                }
                catch (\Stripe\Exception\ApiErrorException $e) {
                custom_error_log($e->getError()->message);
                } catch (Exception $e) {
                custom_error_log($e);
                } 

                $add_token =  Http::withHeaders([
                'Accept' => 'application/json',
                'domainkey' => DOMAIN_KEY
                ])->post(API_URL .'create_network_token',[
                'booking_id' => $booking_id,
                'token' => $access_token,
                'response'   => $txnResponse
                ]); 
               // echo "<pre>";print_r($add_token['message']);exit;
                return $access_token;

    }

      public function makePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'booking_id' => 'required|int',
           'stripeToken' => 'required',
       ]);
        
       if ($validator->fails()) { 
            Session::flash('error', $validator->messages()->first());
            return redirect()->back()->withInput();
       }
       else{
    
          $booking_response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'booking?lang='.Session::get('locale'),[
                'booking_id' => $request->booking_id,
                'store_id'   => 13
                ]);
          $booking = $booking_response['booking'];
          if(!isset($booking['bg_id']) && !$booking['bg_id']){
            Session::forget('cart_id', trim($results['cart_id']));
            $response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'payment_update?lang='.Session::get('locale'), [
                        'booking_id' => $booking['bg_id'],
                        'payment_type' => 1,
                        'payment_status' => 0,
                        'message' => 'Invalid Booking details provided',
            ]); 
            return redirect(app()->getLocale().'/confirmation/'.md5($booking['booking_no']));
          }
           
        try {

          
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $paymentIntent =  Stripe\Charge::create ([
                "amount" => $booking['total_amount'] * 100,
                "currency" => $booking['currency_type'],
                "source" => $request->stripeToken,
                'metadata' => array(
                            'booking_no' => $booking['booking_no']
                        ),
                "description" => 'booking_no - '.$booking['booking_no'].' - '.$booking['match_name'].' - '.$booking['stadium_name'].' - '.$booking['seat_category'].' - '.$booking['seat_category'].' - '.$booking['ticket_id'].' - '.$booking['match_date'].' - '.$booking['match_time']
        ]);

      
         if ($paymentIntent->amount_refunded == 0 && empty($paymentIntent->failure_code) && $paymentIntent->paid == 1 && $paymentIntent->captured == 1) {

                $amount = $paymentIntent->amount;
                $balance_transaction = $paymentIntent->balance_transaction;
                $currency = $paymentIntent->currency;
                $paymentstatus = $paymentIntent->status;

                if($paymentstatus == 'succeeded'){
                    $payment_status = 1;
                }
                else if($paymentstatus == 'failed'){
                    $payment_status = 0;
                }
                else if($paymentstatus == 'pending'){
                    $payment_status = 2;
                }
                else if($paymentstatus == 'reversed'){
                    $payment_status = 3;
                }
                else if($paymentstatus == 'canceled'){
                    $payment_status = 4;
                }


             
               $response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'payment_update?lang='.Session::get('locale'), [
                                'booking_id' => $booking['bg_id'],
                                'payment_type' => 1,
                                'payment_status' => $payment_status,
                                'transcation_id' => $balance_transaction,
                                'payment_response' => json_encode($paymentIntent),
                                'total_payment' => $amount/100,
                                'message' => 'success',
                                'currency_code' => $currency
                ]); 

         }
         else{


                $amount = $paymentIntent->amount;
                $balance_transaction = $paymentIntent->balance_transaction;
                $currency = $paymentIntent->currency;
                $paymentstatus = $paymentIntent->status;

                if($paymentstatus == 'succeeded'){
                    $payment_status = 1;
                }
                else if($paymentstatus == 'failed'){
                    $payment_status = 0;
                }
                else if($paymentstatus == 'pending'){
                    $payment_status = 2;
                }
                else if($paymentstatus == 'reversed'){
                    $payment_status = 3;
                }
                else if($paymentstatus == 'canceled'){
                    $payment_status = 4;
                }


                    $response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'payment_update?lang='.Session::get('locale'), [
                                'booking_id' => $booking['bg_id'],
                                'payment_type' => 1,
                                'payment_status' => $payment_status,
                                'transcation_id' => $balance_transaction,
                                'payment_response' => json_encode($paymentIntent),
                                'message' => 'unknown',
                                'total_payment' => $amount/100,
                                'currency_code' => $currency
                ]); 


         }
       

        }
        catch (\Throwable $t) {
               // custom_error_log($t->getMessage());
             $response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'payment_update?lang='.Session::get('locale'), [
                    'booking_id' => $booking['bg_id'],
                            'payment_type' => 1,
                            'payment_status' => 0,
                            'payment_response' => json_encode($t->getMessage()),
                            'message' => $t->getMessage(),
            ]); 
        }
         catch (\Stripe\Exception\ApiErrorException $e) {
     
          $response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'payment_update?lang='.Session::get('locale'), [
                    'booking_id' => $booking['bg_id'],
                            'payment_type' => 1,
                            'payment_status' => 0,
                            'payment_response' => json_encode($e->getError()),
                            'message' => '400 - '.$e->getError()->message,
            ]); 

      
        } catch (Exception $e) {
      

          $response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'payment_update?lang='.Session::get('locale'), [
                            'booking_id' => $booking['bg_id'],
                            'payment_type' => 1,
                            'payment_status' => 0,
                            'payment_response' => json_encode($e),
                            'message' => '500 - '.$e,
            ]); 
    
        }
        Session::forget('cart_id');
        Session::forget('cart_quantity');
        return redirect(app()->getLocale().'/confirmation/'.md5($booking['booking_no']));

       }

      
        //return back();
    }

    public function cart_delete(Request $request)
    {
        $delete_response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'delete_cart?lang='.Session::get('locale'),[
                            'ip' => get_client_ip()
                        ]);
        $results = $delete_response;
        if($results['status'] == 1)
        {   Session::forget('cart_id');
            Session::forget('cart_quantity');
            $response = ["message" =>$results['message'],'status' => 1];
            return response($response, 200);
        }else 
        {
            $response = ["message" => $results['message'] ,'status' => 0];
            return response($response, 200);
        }
    }

    public function update_cart(Request $request)
    {
        $cart_id = Session::get('cart_id');
       
        $delete_response =  Http::withHeaders([
            'Accept' => 'application/json',
            'domainkey' => DOMAIN_KEY
        ])->post(API_URL .'update_cart?lang='.Session::get('locale'),[
                            'ip'            => get_client_ip(),
                            'cart_id'       => $cart_id
                ]);
        $results = $delete_response;
        if($results['status'] == 1)
        {   

            $itemid = array();
            $old = strtotime(date("m/d/Y h:i:s ", strtotime($results['result']['current_time'])));
            $new = strtotime(date('m/d/Y, h:i:s',strtotime($results['result']['expriy_datetime'])));
            $time = ($new - $old);
            $response = ["message" =>$results['message'],'time' => $time ,'status' => 1];
            return response($response, 200);
        }else 
        {
            $response = ["message" => $results['message'] ,'status' => 0];
            return response($response, 200);
        }
    }

    public function invokeCurlRequest($type, $url, $headers, $post='') {

        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
    
        if ($type == "POST" || $type == "PUT") {
    
            curl_setopt($ch, CURLOPT_POST, 1);
            if($post != ''){
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            }
           curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        }
    
        $server_output = curl_exec ($ch);
       
        $err = curl_error($ch);
        
        curl_close($ch);
        
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            
             return $server_output;
    
        }
    
    
    }
    
}
