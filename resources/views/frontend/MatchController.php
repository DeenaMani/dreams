<?php
namespace App\Http\Controllers\Storefront;
 
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use Mail;
use Tixstock;
use Xs2event;
use App\Models\SiteSetting;
use App\Models\MatchInfo;
use App\Models\SellTickets;
use App\Models\Teams;
use App\Models\StadiumDetails;
use App\Models\StadiumSeats;
use App\Models\Stadium;

 
class MatchController extends Controller
{
    private $store_id;
    private $percentage;
    private $seller_markup;
    private $store_markup;
    private $default_currency;
    private $additional_store_markup;

    public function __construct(Request $request)
    {
        $this->check_token($request);
    }

    public  function check_token($request)
    { 
        $this->middleware(function($request, $next) {

            $token = $request->header('domainkey');
           // return response(['test'=> $token], 200);
             //return response(array("domain_key" => $request->header('domainkey')),200);
             if($token){
            $setting = SiteSetting::where('site_name','SITE_DOMAIN')->where('site_value',$token)->first();
             $this->store_id = $setting->store_id; 
            $markup = SiteSetting::where('site_name','STORE_MARKUP')->where('store_id',$this->store_id)->first(); 
            $this->store_markup = $markup->site_value;
            if($this->store_markup == '' || $this->store_markup <= 0){
             $this->store_markup = 0;
            }
            $this->seller_markup = 0;
            $currency = SiteSetting::where('site_name','SITE_CURRENCY')->where('store_id',$this->store_id)->first(); 
             $this->default_currency = $currency->site_value;

            $additional_markup = SiteSetting::where('site_name','ADDTIONAL_MARKUP_BELOW_100')->where('store_id',$this->store_id)->first(); 
            $this->additional_store_markup = @$additional_markup->site_value;


        }
            return $next($request);
        });
    }


    public function all_match(Request $request){
        $lang  = $request->lang ? $request->lang : "en";
        $keywords  = $request->keywords ? $request->keywords : "";
        $tournament  = $request->tournament ? $request->tournament : "";
        $currency  = $request->currency ? $request->currency : "GBP";
        $limit = $request->limit ?  $request->limit : 10;
        $page = $request->page ? (($request->page - 1) * $limit) : 0;
        $lang_col = $lang== "ar" ? "_".$lang :"";
        $top_games  = @$request->top_games ? $request->top_games : "";
        $client_country  = @$request->client_country ? $request->client_country : "IN";

        $matchs  = MatchInfo::select('match_info.m_id','match_info.slug',
                                    'match_info.match_date',
                                    'match_info.match_time',
                                    'match_info.event_type',
                                    'match_info.blog_image',
                                    'match_info.tbc_status',
                                    'match_info_lang.match_name',
                                    'match_info_lang.language',
                                    'match_info_lang.extra_title',
                                    'match_info_lang.event_image',
                                    'team_a.team_image as team_image_a',
                                    'team_b.team_image as team_image_b',
                                    'teams_lang_a.team_name as team_name_a',
                                    'teams_lang_b.team_name as team_name_b',
                                    'team_a.url_key as team_slug_a',
                                    'team_b.url_key as team_slug_b',
                                    'tl.tournament_name',
                                     'tl.tournament_id',
                                    'stadium.stadium_name'.$lang_col." as stadium_name",
                                    'match_info.ignoreautoswitch',
                                     'match_info.oneboxoffice_status',
                                     'match_info.tixstock_status',
                                     'match_info.oneclicket_status',
                                     'match_info.xs2event_status'                            
                                )
                            ->leftJoin('match_info_lang', function($join) use($lang) {
                                $join->on('match_info_lang.match_id', '=', 'match_info.m_id')
                                    ->where('language',$lang);
                            })
                           ->leftJoin('teams as team_a', function($join) {
                                $join->on('team_a.id', '=', 'match_info.team_1');
                            })

                            ->leftJoin('teams_lang as teams_lang_a', function($join) use($lang){
                                $join->on('teams_lang_a.team_id', '=', 'team_a.id')
                                ->where('teams_lang_a.language',$lang);
                            })
                            ->leftJoin('teams as team_b', function($join) use($lang) {
                                $join->on('team_b.id', '=', 'match_info.team_2');
                            })
                            ->leftJoin('teams_lang as teams_lang_b', function($join) use($lang){
                                $join->on('teams_lang_b.team_id', '=', 'team_b.id')
                                ->where('teams_lang_b.language',$lang);
                            })
                           
                            ->leftJoin('tournament', function($join) {
                                $join->on('tournament.t_id', '=', 'match_info.tournament');
                            })
                            ->leftJoin('tournament_lang as  tl', function($join) use($lang) {
                                    $join->on('tl.tournament_id','=','tournament.t_id')
                                    ->where('tl.language',$lang);
                            })
                            ->leftJoin('stadium', function($join) use($lang) {
                                $join->on('stadium.s_id', '=', 'match_info.venue')
                                   ;
                            })
                            ->leftJoin('countries', function($join) use($lang) {
                                $join->on('countries.id', '=', 'match_info.country')
                                   ;
                            })
                            ->leftJoin('states', function($join) use($lang) {
                                $join->on('states.id', '=', 'match_info.state')
                                   ;
                            })
                            ->leftJoin('match_settings', function($join) use($lang) {
                                $join->on('match_settings.matches', '=', 'match_info.m_id')
                                   ;
                            })
                            ->leftJoin('banned_countries_match as bcm', function($join) use($client_country) {
                                    $join->on('bcm.match_id', '=', 'match_info.m_id')
                                     ->where('bcm.country_code', '=', $client_country);
                             })
                            ->WhereNull('bcm.country_code')
                        
                            //  ->leftJoin('sell_tickets', function($join) {
                            //     $join->on('sell_tickets.match_id', '=', 'match_info.m_id');
                            // })
                            // ->leftJoin('sell_tickets', function($join) {
                            //     $join->on('sell_tickets.match_id', '=', 'match_info.m_id');
                            //     // ->where('sell_tickets.price', '=', DB::raw("(select min(`price`) from sell_tickets)"));
                            // })
                            ->whereRaw('FIND_IN_SET(?, match_settings.storefronts)', [$this->store_id])
                           // ->having('match_tixstock_status',1)
                            // ->having('match_tixstock_status',1)
                            ->where(function ($query){
                                $query->where('match_info.oneboxoffice_status', '=', "1")
                                    ->orWhere('match_info.tixstock_status', '=', "1")
                                    ->orWhere('match_info.oneclicket_status', '=', "1")
                                    ->orWhere('match_info.xs2event_status', '=', "1");
                            })
                            ->where('match_info.status',1)
                            ->where('match_info.event_type','match')
                            ->where('match_info_lang.language',$lang)
                            ->where('tournament.status',1)
                            // ->whereDate('match_info.match_date','>=', Carbon::now())
                            ->where(DB::raw('IF (match_info.source_type = "1boxoffice", match_info.match_date , DATE_SUB( match_info.match_date, INTERVAL 1 DAY) )'),'>=', current_date() )
                            ->orderBy('match_info.match_date','ASC')   
                            ->groupBy('match_info.m_id');      
                            $tournment_matchs = $matchs->get();      
                            $matchs =  $matchs->skip($page)->take($limit);
                           //  return response(array("test" => $matchs),200);
                            if($tournament != "") { 
                               $matchs = $matchs->where('match_info.tournament',$tournament);
                            }
                            if($top_games == 1) { 
                               $matchs = $matchs->where('match_info.top_games',1);
                            }
                           //  return response(array("test" => $matchs),200);

        if($keywords) { 
             $matchs =  $matchs->where(function($query) use ($keywords) {
                $query->where('match_info_lang.match_name', 'LIKE', "%{$keywords}%")
                ->orwhere('countries.name', 'LIKE', "%{$keywords}%")
                ->orwhere('states.name', 'LIKE', "%{$keywords}%")
                ->orwhere('stadium.stadium_name_ar', 'LIKE', "%{$keywords}%")
                ->orwhere('stadium.stadium_name', 'LIKE', "%{$keywords}%");
            });

        }

        $matchs = $matchs->get();
        $tournaments_data = array();
        foreach ($tournment_matchs as $key => $value) {

            $tournaments_data[] = array('tournament_id' => $value->tournament_id,'tournament_name' => $value->tournament_name);
        }
        if(!empty($tournaments_data)){
                $tournaments_data = array_unique($tournaments_data, SORT_REGULAR);
        }
        foreach ($matchs as $key => $value) {
            $now_date =  date('Y-m-d H:i');
            $match_date  = date("Y-m-d H:i",strtotime($value->match_date));
            if($value->ignoreautoswitch  == 0 && $now_date >= $match_date){
                $price  = array();
            }
            else{
              

                $prices = DB::table('sell_tickets')
                    ->select('sell_tickets.price','sell_tickets.price_type','sell_tickets.add_by')
                    ->leftJoin('match_info', function($join) {
                                $join->on('sell_tickets.match_id', '=', 'match_info.m_id');
                            })
                    ->where('sell_tickets.status',1)
                    ->where('sell_tickets.price','>',0)
                    ->where('sell_tickets.match_id',$value->m_id)
               
                    ->orderBy('sell_tickets.price','asc');

                    $price_source_type = array();

                    if($value->oneboxoffice_status == 1 ){
                       $price_source_type[] = '1boxoffice';
                    }
                    if($value->tixstock_status == 1 ){
                        $price_source_type[] = 'tixstock';
                    }
                    if($value->oneclicket_status == 1 ){
                        $price_source_type[] = 'oneclicket';
                    }
                    if($value->xs2event_status == 1 ){
                        $price_source_type[] = 'xs2event';
                    }
                   
                    $prices = $prices->whereIn('sell_tickets.source_type',$price_source_type);

                    $prices = $prices->first();
            }
           
           
            $price = @$prices->price ;
            $price_type =  @$prices->price_type;

            $total_price = "";

            $seller_id = @$prices->add_by; 
            $seller_markup = @$this->seller_markup;
            if(@$prices->add_by != ""){

                 $seller_markup = get_markup($seller_id,$this->store_id, @$this->markup);
            }
           
            if(@$price){ 
                $price = get_percentage($price,$seller_markup);         
                $total_price =  get_currency($price_type,$currency,$price,0);
                $total_price_sym =  get_currency($price_type,$currency,$price,1);
            }  
            $matchs[$key]->min_price =  @$total_price ? $total_price : "";

            $matchs[$key]->currency_code =  @$total_price ? trim($currency) : "";
            $matchs[$key]->currency_symbol =  @$total_price ? get_symbol($currency) : "" ;
            $matchs[$key]->price_with_symbol =  @$total_price ? $total_price_sym : "" ;
            //return response(array("results" => $matchs),200); 

            // $top_matchs[$key]->price = 5000;
            $matchs[$key]->request_type = @$total_price ? "book" : "request";
            $matchs[$key]->match_date  = date("d F Y",strtotime($value->match_date));
            $matchs[$key]->team_image_a =  IMAGE_PATH."teams/".$value->team_image_a;
            $matchs[$key]->team_image_b =  IMAGE_PATH."teams/".$value->team_image_b;


            if($value->blog_image){
                $blog_image = $value->blog_image;
            }
            else{
                $blog_image = "no-camera.png";
            }
            $matchs[$key]->blog_image =  IMAGE_PATH."blog_image/".$blog_image;

            $matchs[$key]->match_day = date("d",strtotime($value->match_date)); 
            $matchs[$key]->match_month  = date("M",strtotime($value->match_date));  
            $matchs[$key]->match_year  = date("Y",strtotime($value->match_date));

             if($value->tbc_status == 1){
                $matchs[$key]->match_date = tbc;
                $matchs[$key]->match_time = "";
             }

        }
        return response(array("results" => $matchs,"tournaments" => $tournaments_data),200);
    }

    public function top_matchs(Request $request){
        //echo $request->lang;
        $currency  = @$request->currency ? $request->currency : "GBP";
        $lang  = $request->lang ? $request->lang : "en";
        $lang_col = $lang== "ar" ? "_".$lang :"";
        $client_country  = @$request->client_country ? $request->client_country : "IN";

        $current_date =  date('Y-m-d');
        $top_matchs  = MatchInfo::select('match_info.m_id','match_info.slug',
                                    'match_info.match_date',
                                    'match_info.match_time',
                                    'match_info.event_type',
                                    'match_info_lang.match_name',
                                    'match_info_lang.language',
                                    'match_info_lang.extra_title',
                                    'match_info_lang.event_image',
                                    'team_a.team_image as team_image_a',
                                    'team_b.team_image as team_image_b',
                                    'tl.tournament_name',
                                    'stadium.stadium_name'.$lang_col." as stadium_name",
                                    'match_info.ignoreautoswitch',
                                     'match_info.oneboxoffice_status',
                                     'match_info.tixstock_status',
                                     'match_info.oneclicket_status',
                                     'match_info.xs2event_status',                                
                                )
                            ->leftJoin('match_info_lang', function($join) use($lang) {
                                $join->on('match_info_lang.match_id', '=', 'match_info.m_id')
                                    ->where('language',$lang);
                            })
                            ->leftJoin('teams as team_a', function($join) {
                                $join->on('team_a.id', '=', 'match_info.team_1');
                            })
                            ->leftJoin('teams as team_b', function($join) {
                                $join->on('team_b.id', '=', 'match_info.team_2');
                            })
                            ->leftJoin('tournament', function($join) {
                                $join->on('tournament.t_id', '=', 'match_info.tournament');
                            })
                            ->leftJoin('tournament_lang as  tl', function($join) use($lang) {
                                    $join->on('tl.tournament_id','=','tournament.t_id')
                                    ->where('tl.language',$lang);
                            })
                            ->leftJoin('stadium', function($join) use($lang) {
                                $join->on('stadium.s_id', '=', 'match_info.venue')
                                   ;
                            })
                            ->leftJoin('banned_countries_match as bcm', function($join) use($client_country) {
                                    $join->on('bcm.match_id', '=', 'match_info.m_id')
                                     ->where('bcm.country_code', '=', $client_country);
                             })
                            ->WhereNull('bcm.country_code')
                            
                            //  ->leftJoin('sell_tickets', function($join) {
                            //     $join->on('sell_tickets.match_id', '=', 'match_info.m_id');
                            // })
                            // ->leftJoin('sell_tickets', function($join) {
                            //     $join->on('sell_tickets.match_id', '=', 'match_info.m_id');
                            //     // ->where('sell_tickets.price', '=', DB::raw("(select min(`price`) from sell_tickets)"));
                            // })
                            ->where('match_info.status',1)
                            ->where(function ($query){
                                $query->where('match_info.oneboxoffice_status', '=', "1")
                                    ->orWhere('match_info.tixstock_status', '=', "1")
                                    ->orWhere('match_info.oneclicket_status', '=', "1")
                                    ->orWhere('match_info.xs2event_status', '=', "1");
                            })
                            // ->having('match_tixstock_status',1)
                            ->where('tournament.status',1)
                            ->where('match_info.top_games',1) 
                            ->where('match_info.event_type','match')
                            ->where('match_info_lang.language',$lang)
                            //->whereDate('match_info.match_date','>', Carbon::now())
                            ->where(DB::raw('IF (match_info.source_type = "tixstock" || match_info.source_type = "xs2event", DATE_SUB( match_info.match_date, INTERVAL 1 DAY) , match_info.match_date)'),'>=',current_date())
                            ->orderBy('match_info.match_date','ASC')
                            
                            ->take(8)
                            ->get();
                           // echo current_date();
        foreach ($top_matchs as $key => $value) {
            //$current_date  = "2022-09-03";

            $now_date =  date('Y-m-d H:i');
            $match_date  = date("Y-m-d H:i",strtotime($value->match_date));
            if($value->ignoreautoswitch  == 0 && $now_date >= $match_date){
                $price  = array();
            }
            else{
                  $prices = DB::table('sell_tickets')
                    ->select('sell_tickets.price','sell_tickets.price_type','sell_tickets.add_by')
                    ->leftJoin('match_info', function($join) {
                                $join->on('sell_tickets.match_id', '=', 'match_info.m_id');
                            })
                    ->where('sell_tickets.status',1)
             
                    ->where('sell_tickets.match_id',$value->m_id)
               
                    ->orderBy('sell_tickets.price','asc');

                    $price_source_type = array();

                    if($value->oneboxoffice_status == 1 ){
                       $price_source_type[] = '1boxoffice';
                    }
                    if($value->tixstock_status == 1 ){
                        $price_source_type[] = 'tixstock';
                    }
                    if($value->oneclicket_status == 1 ){
                        $price_source_type[] = 'oneclicket';
                    }
                    if($value->xs2event_status == 1 ){
                        $price_source_type[] = 'xs2event';
                    }
                   
                    $prices = $prices->whereIn('sell_tickets.source_type',$price_source_type);

                    $prices = $prices->first();
            }
         
            
            $seller_id = @$prices->add_by; 
            $seller_markup =  @$this->seller_markup;
            if($this->store_id != ""){
                $seller_markup = get_markup($seller_id,$this->store_id, @$this->markup);
            }

            $top_matchs[$key]->request_type = @$prices->price ? "book" : "request";

            $price = @$prices->price ;
            $price_type =  @$prices->price_type;

            $total_price = "";

            if(@$price){
                $price = get_percentage($price,$seller_markup);
                $total_price =  get_currency($price_type,$currency,$price,0);
                $total_price_sym =  get_currency($price_type,$currency,$price,1);
            }
            $top_matchs[$key]->min_price =  @$total_price ? $total_price : "";

            $top_matchs[$key]->currency_code =  @$total_price ? trim($currency) : "";
            $top_matchs[$key]->currency_symbol =  @$total_price ? get_symbol($currency) : "" ;
            $top_matchs[$key]->price_with_symbol =  @$total_price ? $total_price_sym : "" ;
            
           // $top_matchs[$key]->price = 5000;
            $top_matchs[$key]->match_date  = date("d F Y",strtotime($value->match_date));
            $top_matchs[$key]->team_image_a =  IMAGE_PATH."teams/".$value->team_image_a;
            $top_matchs[$key]->team_image_b =  IMAGE_PATH."teams/".$value->team_image_b;
        }
        return response(array("top_matchs" => $top_matchs),200);
    }

    public function match_list(Request $request)
    {
        $currency  = $request->currency ? $request->currency : "GBP";
        $lang  = $request->lang ? $request->lang : "en";
        $current_date =  date('Y-m-d');

        $client_country  = @$request->client_country ? $request->client_country : "IN";


    

        $matchs  = MatchInfo::select('match_info.m_id','match_info.slug',
                                    'match_info.match_date',
                                    'match_info.match_time',
                                    'match_info.event_type',
                                    'match_info_lang.match_name',
                                    'match_info_lang.language',
                                    'match_info_lang.extra_title',
                                    'match_info_lang.event_image',
                                    'team_a.team_image as team_image_a',
                                    'team_b.team_image as team_image_b',
                                    'tournament.tournament_name',
                                    'match_info.oneboxoffice_status',
                                    'match_info.tixstock_status',
                                    'match_info.oneclicket_status',
                                    'match_info.xs2event_status',
                                 
                                )
                            ->leftJoin('match_info_lang', function($join) use($lang) {
                                $join->on('match_info_lang.match_id', '=', 'match_info.m_id')
                                    ->where('language',$lang);
                            })
                            ->leftJoin('teams as team_a', function($join) {
                                $join->on('team_a.id', '=', 'match_info.team_1');
                            })
                            ->leftJoin('teams as team_b', function($join) {
                                $join->on('team_b.id', '=', 'match_info.team_2');
                            })
                            ->leftJoin('tournament', function($join) {
                                $join->on('tournament.t_id', '=', 'match_info.tournament');
                            })
                            ->leftJoin('match_settings', function($join) use($lang) {
                            $join->on('match_settings.matches', '=', 'match_info.m_id')
                            ;
                            })

                             ->leftJoin('banned_countries_tournament as bct', function($join) use($client_country) {
                                    $join->on('bct.tournament_id', '=', 'match_info.tournament')
                                     ->where('bct.country_code', '=', $client_country);
                                  
                              
                             })
                            ->WhereNull('bct.country_code')
                            
                            ->leftJoin('banned_countries_match as bcm', function($join) use($client_country) {
                                    $join->on('bcm.match_id', '=', 'match_info.m_id')
                                     ->where('bcm.country_code', '=', $client_country);
                             })
                            ->WhereNull('bcm.country_code')
                            
                            //  ->leftJoin('sell_tickets', function($join) {
                            //     $join->on('sell_tickets.match_id', '=', 'match_info.m_id');
                            // })
                            // ->leftJoin('sell_tickets', function($join) {
                            //     $join->on('sell_tickets.match_id', '=', 'match_info.m_id');
                            //     // ->where('sell_tickets.price', '=', DB::raw("(select min(`price`) from sell_tickets)"));
                            // })
                            ->where(function ($query){
                                $query->where('match_info.oneboxoffice_status', '=', "1")
                                    ->orWhere('match_info.tixstock_status', '=', "1")
                                    ->orWhere('match_info.oneclicket_status', '=', "1")
                                    ->orWhere('match_info.xs2event_status', '=', "1");
                            })
                            ->where('match_info.status',1)
                            ->whereRaw('FIND_IN_SET(?, match_settings.storefronts)', [$this->store_id])
                            //->where('match_info.event_type','match')
                            ->where('match_info_lang.language',$lang)
                            //->whereDate('match_info.match_date','>=', Carbon::now())
                            ->where(DB::raw('IF (match_info.source_type = "tixstock" || match_info.source_type = "xs2event", DATE_SUB( match_info.match_date, INTERVAL 1 DAY) , match_info.match_date)'),'>=',Carbon::now())
                            ->orderBy('match_info.match_date','ASC')
                            ->get();
        foreach ($matchs as $key => $value) {
            $matchs[$key]->match_date = date("d-m-Y",strtotime($value->match_date));
            $matchs[$key]->match_date_format  = date("d M Y",strtotime($value->match_date));

        }
        return response(array("results" => $matchs),200);
    }


    function match_country(Request $request) {
        $client_country  = @$request->client_country ? $request->client_country : "IN";
        $results  = MatchInfo::select('countries.*')
                        ->join('countries', function($join)  {
                            $join->on('countries.id', '=', 'match_info.country');
                        })
                        ->leftJoin('match_settings', function($join)  {
                                $join->on('match_settings.matches', '=', 'match_info.m_id')
                                   ;
                            })
                        ->whereRaw('FIND_IN_SET(?, match_settings.storefronts)', [$this->store_id])
                        ->where(DB::raw('IF (match_info.source_type = "tixstock" || match_info.source_type = "xs2event", DATE_SUB( match_info.match_date, INTERVAL 1 DAY) , match_info.match_date)'),'>=',Carbon::now())
                        ->leftJoin('banned_countries_match as bcm', function($join) use($client_country) {
                                    $join->on('bcm.match_id', '=', 'match_info.m_id')
                                     ->where('bcm.country_code', '=', $client_country);
                             })
                            ->WhereNull('bcm.country_code')
                        ->where('match_info.status','=',1)
                        ->where(function ($query){
                                $query->where('match_info.oneboxoffice_status', '=', "1")
                                    ->orWhere('match_info.tixstock_status', '=', "1")
                                    ->orWhere('match_info.oneclicket_status', '=', "1")
                                     ->orWhere('match_info.xs2event_status', '=', "1");
                            })
                        ->groupBy('countries.id')
                    ->get();
        return response(array("results" => $results),200);
    }


    public function category_list(Request $request)
    {
        $lang  = $request->lang ? $request->lang : "en";
        $match_id  = $request->match_id ? $request->match_id : "";

        $result = MatchInfo::select('venue as stadium_id')->where('m_id',$match_id)->first();
       $stadium_id =  @$result->stadium_id;

        $seat_list = StadiumDetails::select('stadium_details.block_color','stadium_seats_lang.seat_category','stadium_seats_lang.stadium_seat_id')
                            ->where('stadium_details.stadium_id',$stadium_id)
                            ->leftJoin('stadium_seats_lang', function($join) use($lang) {
                                $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_details.category')
                                ->where('stadium_seats_lang.language',$lang);
                            })
                            ->whereNotNull('stadium_seats_lang.seat_category')
                            ->groupBy('stadium_details.category')
                            ->orderBy('stadium_details.id')
                            ->get();

        //pr($stadium_details);die;
       
        return response(array("results" => $seat_list),200);
    }

    public function other_match_details(Request $request){
        $lang  = $request->lang ? $request->lang : "en";
        $slug  = $request->slug ? $request->slug : "";
        $currency  = @$request->currency ? $request->currency : "GBP";
        $current_date =  date('Y-m-d');
        $client_country  = @$request->client_country ? $request->client_country : "IN";
        $lang_col = $lang== "ar" ? "_".$lang :"";
        $store_id = $this->store_id ;
        $result = MatchInfo::select('match_info.m_id',
                                    'match_info.slug',
                                    'match_info.price_type',
                                    'match_info.match_date',
                                    'match_info.high_demand',
                                    'match_info.almost_sold',
                                    'match_info.match_time',
                                    'match_info.tbc_status',
                                    'match_info_lang.match_name','match_info_lang.language','match_info_lang.extra_title','match_info_lang.description','match_info_lang.meta_title','match_info_lang.meta_description','match_info_lang.long_description','match_info_lang.event_image','match_info.team_1','match_info.team_2','match_info.top_games',
                                    'stadium.stadium_name'.$lang_col." as stadium_name",
                                    'otherevent_category.otherevent_category_slug', 
                                    'otherevent_category_lang.category_name', 
                                        'otherevent_category_lang.other_event_cat_id',
                                        'venue as stadium_id',
                                        'match_info.ignoreautoswitch',
                                        'stadium.stadium_type',
                                        'countries.name as country_name',
                                        'countries.sortname as sortname', 
                                        'states.name as state_name',
                                        'cities.name as city_name',
                                        'match_info.seat_mapping',
                                        'teams_lang_a.team_name as artist_name',
                                        'team_a.team_image as artist_image',
                                        'team_a.url_key as artist_slug',
                                        'tournament_lang.tournament_name', 
                                        'tournament.url_key as tournament_url_key'
                                )
                            ->leftJoin('match_info_lang', function($join) use($lang,$store_id) {
                                $join->on('match_info_lang.match_id', '=', 'match_info.m_id')
                                    ->where('match_info_lang.store_id',$store_id)
                                    ->where('match_info_lang.language',$lang);
                            })
                             ->leftJoin('stadium', function($join) use($lang) {
                                $join->on('stadium.s_id', '=', 'match_info.venue');
                                
                            })
                              ->leftJoin('countries', function($join) use($lang) {    
                                $join->on('countries.id', '=', 'match_info.country')    
                                   ;    
                            })
                              ->leftJoin('states', function($join) use($lang) {   
                                $join->on('states.id', '=', 'match_info.state') 
                                   ;    
                            })
                            ->leftJoin('cities', function($join) use($lang) {   
                                $join->on('cities.id', '=', 'match_info.city') 
                                   ;    
                            })
                            ->leftJoin('otherevent_category', function($join) {
                                $join->on('otherevent_category.id', '=', 'match_info.other_event_category');
                            })
                            ->leftJoin('teams as team_a', function($join) {
                                $join->on('team_a.id', '=', 'match_info.team_1');
                            })

                            ->leftJoin('teams_lang as teams_lang_a', function($join) use($lang){
                                $join->on('teams_lang_a.team_id', '=', 'team_a.id')
                                ->where('teams_lang_a.language',$lang);
                            })
                             ->leftJoin('tournament', function($join) {
                                $join->on('tournament.t_id', '=', 'match_info.tournament');
                            })
                            ->leftJoin('tournament_lang', function($join)use($lang) {
                                $join->on( 'tournament_lang.tournament_id' ,'=','tournament.t_id')
                                ->where('tournament_lang.language',$lang);
                            })
                            ->leftJoin('otherevent_category_lang', function($join)use($lang) {
                                $join->on( 'otherevent_category_lang.other_event_cat_id' ,'=','otherevent_category.id')
                                ->where('otherevent_category_lang.language',$lang);
                            })
                            ->leftJoin('match_settings', function($join) use($lang) {
                                $join->on('match_settings.matches', '=', 'match_info.m_id')
                                   ;
                            })
                             ->leftJoin('banned_countries_tournament as bct', function($join) use($client_country) {
                                    $join->on('bct.tournament_id', '=', 'match_info.tournament')
                                     ->where('bct.country_code', '=', $client_country);
                                  
                              
                             })
                            ->WhereNull('bct.country_code')
                            ->leftJoin('banned_countries_match as bcm', function($join) use($client_country) {
                                    $join->on('bcm.match_id', '=', 'match_info.m_id')
                                     ->where('bcm.country_code', '=', $client_country);
                             })
                            ->WhereNull('bcm.country_code')
                            ->where('match_info.event_type','other')
                            ->where('match_info.status',1)
                            ->where('match_info.slug',$slug)
                            //->whereDate('match_info.match_date','>', Carbon::now())
                            ->orderBy('match_date','desc')
                            ->first();
        if(empty($result)){
             $response['result']= ["message" => "Match Details not available",'status' => 0];
            return response($response, 422);

        }

        $current_date_time = strtotime('-5 hour');
        if($result->match_status == 1 ){
            $match_status  =   strtotime($result->match_date) > $current_date_time ? 1 : 0 ;
            $result->match_status = $match_status  ;   
        } 
        else{
             $result->match_status = 0;     
        }

                //pr($result);die;
        // $top_matchs[$key]->price = 5000;
        $match_status  =   strtotime($result->match_date) > strtotime(date("Y-m-d H:i:s")) ? 1 : 0 ;
        $result->match_status = $match_status  ;    
        $result->view_per_hour = rand(200,500);
        $result->match_date  = date("d F Y",strtotime($result->match_date));

        $event_image = $result->event_image ? $result->event_image : "event.jpg";
        $result->event_image =  IMAGE_PATH."event_image/".$event_image;
        if($result->seat_mapping ) $result->seat_mapping =  IMAGE_PATH."seat_mapping/".$result->seat_mapping;
       
         $now_date =  date('Y-m-d');
         $match_date  = date("Y-m-d",strtotime($result->match_date));

        $stadium_type = $result->stadium_type ? $result->stadium_type :  1;

        $now_date =  date('Y-m-d');
         $match_date  = date("Y-m-d",strtotime($result->match_date));
        if($result->ignoreautoswitch  == 0 && $now_date == $match_date){
            $price  = array();
        } 
        else{

             if($result->tbc_status == 1){
                $result->match_date = tbc;
                $result->match_time = "";
             }
        
             $price = SellTickets::select('sell_tickets.*','stadium_seats_lang.seat_category','match_info.venue as stadium_id','stadium_block.block_id','stadium_details.block_color','sell_tickets.add_by',DB::raw('( sell_tickets.quantity - IFNULL(`cart_session`.`no_ticket`,0)) as quantity'),'cart_session.no_ticket')
                    ->where('sell_tickets.match_id',$result->m_id)
                    ->where('sell_tickets.status',1)
                    // ->where('sell_tickets.status',1)
                   // ->where(function ($query) {
                   //      $query->where('sell_tickets.split', '!=' , 3)
                   //      ->where('sell_tickets.quantity', '!=' , 1);
                   //  })
                    ->leftJoin('stadium_seats', function($join)  {
                        $join->on('stadium_seats.id', '=', 'sell_tickets.ticket_category');
                    })
                    ->leftJoin('stadium_seats_lang', function($join) use($lang) {
                        $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_seats.id')
                        ->where('stadium_seats_lang.language',$lang);
                    })
                     ->leftJoin('match_info', function($join) use($lang) {
                        $join->on('match_info.m_id', '=', 'sell_tickets.match_id');
                    })
                   ->leftJoin('stadium_details', function($join)  {
                        $join->on('stadium_details.category', '=', 'sell_tickets.ticket_category')
                        ->on('stadium_details.stadium_id','=','match_info.venue');
                    })
                    ->leftJoin('stadium_details as stadium_block', function($join)  {
                        $join->on('stadium_block.id', '=', 'sell_tickets.ticket_block');
                    })
                    ->leftJoin('cart_session', function($join)  {
                        $join->on('cart_session.sell_id', '=', 'sell_tickets.s_no')
                          ->where('cart_session.cart_status',2)
                        ->where('cart_session.expriy_datetime','>', Carbon::now());
                    })

                    ->groupBy('sell_tickets.s_no')
                    ->having('quantity','>',0)
                    ->orderBy('sell_tickets.price','asc');
                   

            $price = $price->get();
        }
        $total_quantity = 0;//return response(array("test" => $price),200);
       // pr($price);
        if(count($price)){
            foreach ($price as $key => $value) {

                if($value->split == 3 && $value->quantity == 1){
                   unset($price[$key]);
                }
                else{
                
                    $seller_id     = @$value->add_by; 
                    $seller_markup = @$this->seller_markup;
                    if(@$value->add_by != ""){

                         $seller_markup = get_markup($seller_id,$this->store_id, @$this->markup);
                         
                    }

                    $vprice = @$value->price ;
                    $price_type =  @$value->price_type;
                    $total_price = "";
                    if(@$vprice){
                        $vprice = get_percentage($vprice,$seller_markup);
                        $total_price =  get_currency($price_type,$currency,$vprice,0);
                        $total_price_sym =  get_currency($price_type,$currency,$vprice,1);
                    }
                    $price[$key]->price =  @$total_price ? $total_price : "";
                    $price[$key]->ticket_price =  @$total_price ? $total_price : "";

                    $price[$key]->currency_code =  @$total_price ? trim($currency) : "";
                    $price[$key]->currency_symbol =  @$total_price ? get_symbol($currency) : "" ;
                    $price[$key]->price_with_symbol =  @$total_price ? $total_price_sym : "" ;

                    $total_quantity += $value->quantity;
                }
            }

        } 
        $result->total_quantity = $total_quantity;
        $stadium_id = $result->stadium_id;
       // echo $stadium_id ;die;
        $category_list = SellTickets::select('sell_tickets.ticket_category','stadium_seats_lang.seat_category','stadium_details.block_color','stadium_seats_lang.stadium_seat_id')
                            ->where('sell_tickets.match_id',$result->m_id)
                            ->leftJoin('stadium_seats', function($join)  {
                                $join->on('stadium_seats.id', '=', 'sell_tickets.ticket_category');
                            })
                            ->leftJoin('stadium_seats_lang', function($join) use($lang) {
                                $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_seats.id')
                                ->where('stadium_seats_lang.language',$lang);
                            })
                             ->leftJoin('stadium', function($join) use($lang) {
                                $join->on('stadium.s_id', '=', 'sell_tickets.ticket_category');
                            })
                            ->leftJoin('stadium_details', function($join) use($stadium_id) {
                                $join->on('stadium_details.category', '=', 'sell_tickets.ticket_category')
                                ->where('stadium_details.stadium_id',$stadium_id);
                            })
                            ->groupBy('sell_tickets.ticket_category')
                            ->orderBy('sell_tickets.price','asc')
                            ->where('sell_tickets.quantity','>',0)
                           ->where('sell_tickets.status',1)
                            ->where('stadium_details.source_type','1boxoffice');
                            

                            if($result->tixstock_status == 0){
                                $category_list = $category_list->whereNull('sell_tickets.tixstock_id'); 
                            }
         

                  $category_list =     $category_list->get();
        $quantity = SellTickets::where('match_id',$result->m_id)
                            ->max('quantity');   


        if($stadium_type == 1){        
              $seat_list = StadiumDetails::select('stadium_details.block_color','stadium_seats_lang.seat_category','stadium_seats_lang.stadium_seat_id')
                            ->where('stadium_details.stadium_id',$stadium_id)
                            ->leftJoin('stadium_seats_lang', function($join) use($lang) {
                                $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_details.category')
                                ->where('stadium_seats_lang.language',$lang);
                            })
                            ->whereNotNull('stadium_seats_lang.seat_category')
                            ->groupBy('stadium_details.category')
                            ->orderBy('stadium_details.id')
                            ->get();

        

            $tot_cats = [];
            $stadium_category = StadiumSeats::leftJoin('stadium_seats_lang', function($join) use($lang) {
                        $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_seats.id');
                        })
                    ->groupBy('stadium_seats.id')
                    ->get();
            if ($stadium_category) {
                foreach ($stadium_category as $std_cat) {
                    $tot_cats[$std_cat->stadium_seat_id] = $std_cat->stadium_seat_id;
                }
            }
           
       
            
           
            $stadium_details =  StadiumDetails::
                    leftJoin('stadium_seats_lang', function($join) use($lang) {
                        $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_details.category')
                        ->where('stadium_seats_lang.language',$lang);
                    })
                     ->where('source_type','1boxoffice')
                    ->where('stadium_id',$stadium_id)->orderBy('stadium_details.id','asc')->get();
           
           
          // pr($stadium_details); die;

            $set_stadium_blocks = [];
            $set_active_stadium_blocks = [];
            $set_stadium_blocks_with_cat = [];
            $set_stadium_cat_name = [];
            if ($stadium_details) {
                foreach ($stadium_details as $stdm) {
                    $set_stadium_blocks[$stdm->block_id] = $stdm->block_color;
                    $set_active_stadium_blocks[$stdm->block_id] = @$stdm->active_color;
                    //pr($tot_cats); die;
                     $set_stadium_blocks_with_cat[@$tot_cats[$stdm->category]][] = $stdm->block_id;
                    $set_stadium_cat_name[$stdm->category][] = @$tot_cats[$stdm->category];
                }
            }
        

            $full_block_data = array();
            $ticket_price_info = [];
            $ticket_price_info_with_cat = [];
            if(count($price)){
                foreach ($price as $sl_tckt) {
                  //  pr($sl_tckt);die;
                //  $seller_id     = @$sl_tckt->add_by; 
                //  $seller_markup = @$this->seller_markup;
                // if(@$sl_tckt->add_by != ""){

                //      $seller_markup = get_markup($seller_id,$this->store_id, @$this->markup);
                     
                // }

                //     $vprice = @$sl_tckt->price ;
                //     $price_type =  @$sl_tckt->price_type;

                //     $vprice = get_percentage($vprice,$seller_markup);
                //     $total_price =  get_currency($price_type,$currency,$vprice,1);

                    $lowest_price = @$sl_tckt->price_with_symbol;
                    $ticket_price_info[@$sl_tckt->ticket_category][@$sl_tckt->block_id]['price'][] =$lowest_price;
                    $ticket_price_info[$sl_tckt->ticket_category][$sl_tckt->block_id]['no_of_ticket'][] = (int) $sl_tckt->quantity;

                          $full_block_data[$sl_tckt->ticket_category][] = $sl_tckt->block_id;
              

                }
          
            }
            

            if ($ticket_price_info) {
                foreach ($ticket_price_info as $cat_key => $tckt_prc) {
                    //echo $cat_key;
                    $tckt_price = [];
                    $tckt_ticket = 0;
                    foreach ($tckt_prc as $sub_tckt_prc) {
                        $tckt_price = array_merge($sub_tckt_prc['price'], $tckt_price);
                        $tckt_ticket = (float) $tckt_ticket + array_sum($sub_tckt_prc['no_of_ticket']);
                    }

                    $total_price = min($tckt_price);
                    // $price_type =  @$tckt_prc->price_type;


                    // $vprice = get_percentage($tckt_price,$this->percentage);
                    // $total_price =  get_currency($price_type,$currency,$vprice,0);

                    $ticket_price_info_with_cat[$cat_key]['name'] = $cat_key;
                    $ticket_price_info_with_cat[$cat_key]['price'] = $total_price;
                    $ticket_price_info_with_cat[$cat_key]['no_of_ticket'] = $tckt_ticket;
                }
            }

            // pr($tot_cats);
            // die;
            //pr($ticket_price_info_with_cat);die;




           // pr($set_stadium_blocks);die;
        }
        else{
              $price_list = array();
                foreach ($price as $key => $value6) {
                    if($value6->ticket_block){

                        $price_list[$value6->ticket_block] =  $value6->price_with_symbol;
                    }
                }
                $stadium  = DB::table('stadium')
                                 ->select('stadium.*')
                                ->where('s_id',$stadium_id)


                                ->first();

                 $stadium->stadium_image_short =  str_replace("/uploads/stadium", "", @$stadium->stadium_image);
                 $stadium->stadium_image =  "https://www.listmyticket.com/".@$stadium->stadium_image;

                 $StadiumDetails  = DB::table('stadium_details')
                                ->select('stadium_details.*','stadium_seats_lang.seat_category')
                                 ->leftJoin('stadium_seats_lang', function($join) use($lang) {
                                    $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_details.category')
                                    ->where('stadium_seats_lang.language',$lang);
                                })
                                ->where('stadium_id',$stadium_id)
                                ->where('source_type','1boxoffice')
                                ->get();

                $someArray = array();
                foreach($StadiumDetails as $values){
                   $block_price =  @$price_list[$values->id] ? $price_list[$values->id] :   "";

                    $someArray[] =  array(
                        'id'                => $values->block_id,
                        'full_block_name'   => $values->full_block_name,
                        'block_color'       => $values->block_color,
                        'category'          => $values->category,
                        'price'             => @$block_price,
                        'seat_category'     => $values->seat_category
                            );
                
                }
               
                $a = array_values($someArray);
               
                  $someArray['source'] = $stadium->stadium_image;
                $stadium->map_code =  json_encode($a);
        }


        $rprice = SellTickets::select(DB::raw('MIN(price) AS min_price'),DB::raw('MAX(price) AS max_price'))->where('status',1)->where('quantity','>',0)->where('price','>',0)->where('match_id',$result->m_id)->first();

        $result->min_price = $rprice->min_price ? get_currency($price_type,$result->price_type,$rprice->min_price,0) : 0 ;
        $result->max_price = $rprice->max_price ? get_currency($price_type,$result->price_type,$rprice->max_price,0) : 0 ;
        
        $response = array(
                
                'price' => $price,
                "result" => $result,
                'quantity' => $quantity,
                'category_list' => $category_list,
                
                "stadium_type" => $stadium_type,

                );

        if($stadium_type  == 1){

                $response['seat_list'] =  $seat_list; 
                $response['full_block_data'] =  json_encode($full_block_data); 
                $response['set_stadium_blocks'] = json_encode($set_stadium_blocks) ;
                $response['set_active_stadium_blocks'] = json_encode($set_active_stadium_blocks) ;
                $response['set_stadium_blocks_with_cat'] = json_encode($set_stadium_blocks_with_cat) ;
                $response['set_stadium_cat_name'] = json_encode($set_stadium_cat_name) ;
                $response['ticket_price_info'] = json_encode($ticket_price_info) ;
                $response['ticket_price_info_with_cat'] = json_encode($ticket_price_info_with_cat) ;
        }
        else{
            $response['stadium'] = $stadium;
        }

        return response($response);
    }

    public function match_details(Request $request){
        $lang  = $request->lang ? $request->lang : "en";
        $slug  = $request->slug ? $request->slug : "";
        $currency  = @$request->currency ? $request->currency : "GBP";
        $current_date =  date('Y-m-d');
        $store_id      = $this->store_id;
        $client_country  = @$request->client_country ? $request->client_country : "IN";
        $lang_col = $lang== "ar" ? "_".$lang :"";


        $clicksourcetype = @$request->clicksourcetype;
        if($clicksourcetype){
            if($clicksourcetype == "affiliate"){
               $udpatdata = array(
                    'affiliate'=> DB::raw('affiliate+1'), 
                );
            }
            else if($clicksourcetype == "partner"){
                $udpatdata = array(
                    'partner'=> DB::raw('partner+1'), 
                );
            }
            else if($this->store_id == 13){
                $udpatdata = array(
                    'onebox'=> DB::raw('onebox+1'), 
                );   
            }
            else if($this->store_id == 220){
                $udpatdata = array(
                    'tixtransfer'=> DB::raw('tixtransfer+1'), 
                );
            }
            else{
                $udpatdata = array(
                    'others'=> DB::raw('others+1'), 
                );
            }
            if($udpatdata) DB::table('clicks')->update($udpatdata); 
        }
        

        $result = MatchInfo::select('match_info.m_id',
                                    'match_info.slug',
                                    'match_info.cloned',
                                    'match_info.redirect_slug',
                                    'match_info.match_date',
                                    'match_info.high_demand',
                                    'match_info.almost_sold',
                                    'match_info.match_time',
                                    'match_info.tbc_status',
                                    'match_info.tixstock_id',
                                    'match_info.xs2event_id',
                                    'match_info.venue',
                                    'match_info.status as match_status',
                                    'match_info.tournament',
                                    'match_info.price_type',
                                    'match_info_lang.long_description',
                                    'match_info_lang.match_label',
                                    'match_info_lang.match_name','match_info_lang.language','match_info_lang.extra_title','match_info_lang.description','match_info_lang.meta_title','match_info_lang.meta_description','match_info_lang.event_image','match_info.team_1','match_info.team_2','match_info.top_games',
                                    'stadium.stadium_name'.$lang_col." as stadium_name",
                                    'stadium.stadium_type',
                                    'tournament_lang.tournament_name', 
                                    'tournament_category.category_name as tournament_category',
                                    'tournament_category.slug as tournament_category_slug',
                                    'countries.name as country_name',  
                                    'states.name as state_name',
                                    'cities.name as city_name',
                                    'countries.sortname as sortname',
                                        'tournament.t_id',
                                        'tournament.url_key as tournament_url_key',
                                        'teams_lang_a.team_name as team_name_a',
                                        'teams_lang_b.team_name as team_name_b',
                                        'team_a.team_image as team_image_a',
                                        'team_b.team_image as team_image_b',
                                        'team_a.url_key as team_slug_a',
                                        'team_b.url_key as team_slug_b',
                                        'venue as stadium_id',
                                        'match_info.ignoreautoswitch',
                                        'match_info.source_type',
                                        'match_info.oneboxoffice_status',
                                        'match_info.tixstock_status',
                                        'match_info.oneclicket_status',
                                        'match_info.xs2event_status',
                                       
                                )
                            ->leftJoin('match_info_lang', function($join) use($lang,$store_id) {
                                $join->on('match_info_lang.match_id', '=', 'match_info.m_id')
                                    ->where('match_info_lang.language',$lang)
                                    ->where('match_info_lang.store_id',$store_id);
                            })
                             ->leftJoin('stadium', function($join) use($lang) {
                                $join->on('stadium.s_id', '=', 'match_info.venue');
                                
                            })
                            ->leftJoin('tournament', function($join) {
                                $join->on('tournament.t_id', '=', 'match_info.tournament');
                            })
                            ->leftJoin('tournament_lang', function($join)use($lang) {
                                $join->on( 'tournament_lang.tournament_id' ,'=','tournament.t_id')
                                ->where('tournament_lang.language',$lang);
                            })
                            ->leftJoin('tournament_category', function($join) use($lang,$store_id){
                                $join->on('tournament_category.category_id', '=', 'tournament.league_cup')
                                ->where('tournament_category.language',$lang);
                            })
                            ->leftJoin('teams as team_a', function($join) {
                                $join->on('team_a.id', '=', 'match_info.team_1');
                            })

                            ->leftJoin('teams_lang as teams_lang_a', function($join) use($lang){
                                $join->on('teams_lang_a.team_id', '=', 'team_a.id')
                                ->where('teams_lang_a.language',$lang);
                            })
                            ->leftJoin('teams as team_b', function($join) use($lang) {
                                $join->on('team_b.id', '=', 'match_info.team_2');
                            })
                            ->leftJoin('teams_lang as teams_lang_b', function($join) use($lang){
                                $join->on('teams_lang_b.team_id', '=', 'team_b.id')
                                ->where('teams_lang_b.language',$lang);
                            })
                            ->leftJoin('match_settings', function($join) use($lang) {
                                $join->on('match_settings.matches', '=', 'match_info.m_id')
                                   ;
                            })
                            ->leftJoin('countries', function($join) use($lang) {    
                                $join->on('countries.id', '=', 'match_info.country')    
                                   ;    
                            })  
                            ->leftJoin('states', function($join) use($lang) {   
                                $join->on('states.id', '=', 'match_info.state') 
                                   ;    
                            })
                             ->leftJoin('cities', function($join) use($lang) {   
                                $join->on('cities.id', '=', 'match_info.city') 
                                   ;    
                            })
                             ->leftJoin('banned_countries_tournament as bct', function($join) use($client_country) {
                                    $join->on('bct.tournament_id', '=', 'match_info.tournament')
                                     ->where('bct.country_code', '=', $client_country);           
                              
                             })
                            ->WhereNull('bct.country_code')
                            ->leftJoin('banned_countries_match as bcm', function($join) use($client_country) {
                                    $join->on('bcm.match_id', '=', 'match_info.m_id')
                                     ->where('bcm.country_code', '=', $client_country);
                             })
                            ->WhereNull('bcm.country_code')
                           /* ->leftJoin('upcoming_event', function($join) use($lang) {
                                $join->on('upcoming_event.match_id', '=', 'match_info.m_id')
                                   ;
                            })*/
                            // ->leftJoin('sell_tickets', function($join) {
                            //     $join->on('sell_tickets.match_id', '=', 'match_info.m_id');
                            //     // ->where('sell_tickets.price', '=', DB::raw("(select min(`price`) from sell_tickets)"));
                            // })
                            ->whereRaw('FIND_IN_SET(?, match_settings.storefronts)', [$this->store_id])
                            // ->orWhere('upcoming_event.add_by',$this->store_id)
                            //->having('match_tixstock_status',1)
                            ->where('match_info.event_type','match')
                            ->whereIn('match_info.status',[0,1])
                            ->where(function ($query){
                                $query->where('match_info.oneboxoffice_status', '=', "1")
                                    ->orWhere('match_info.tixstock_status', '=', "1")
                                    ->orWhere('match_info.oneclicket_status', '=', "1")
                                    ->orWhere('match_info.xs2event_status', '=', "1");
                            })
                            ->where('match_info.slug',$slug)
                          //  ->whereDate('match_info.match_date','>', Carbon::now())
                            ->orderBy('match_date','DESC')
                            ->first();

        if(empty($result)){
             $response['result']= ["message" => "Match Details not available",'status' => 0];
            return response($response, 422);

        }
        //pr($result);
        /*if(@$request->sourcetype != "1boxoffice"){

                    if($result->tixstock_id && $result->tixstock_status == 1){

                        Tixstock::get_ticket_list($result->m_id,$result->stadium_id,$result->tixstock_id) ;
                    }
                }
        */
        
        $current_date_time = strtotime('-5 hour');
                //pr($result);die;
        // $top_matchs[$key]->price = 5000;
        if($result->match_status == 1 ){
            $match_status  =   strtotime($result->match_date) > $current_date_time ? 1 : 0 ;
            $result->match_status = $match_status  ;   
        } 
        else{
             $result->match_status = 0;     
        }
        $result->view_per_hour = rand(200,500);
        $match_date  = date("Y-m-d H:i",strtotime($result->match_date));
        $result->match_date  = date("d F Y",strtotime($result->match_date));
        $result->team_image_a =  IMAGE_PATH."teams/".$result->team_image_a;
        $result->team_image_b =  IMAGE_PATH."teams/".$result->team_image_b;
        $stadium_type = $result->stadium_type;

        $now_date =  date('Y-m-d H:i');
        if($result->ignoreautoswitch  == 0 && $now_date >=  $match_date){          
            $result->match_status = 0 ; 
            $price = array();
        } 
        else{

             if($result->tbc_status == 1){
                $result->match_date = tbc;
                $result->match_time = "";
             }
            $today_date = date("Y-m-d");
             $price = SellTickets::select('sell_tickets.*','stadium_seats_lang.seat_category','match_info.venue as stadium_id','stadium_block.block_id','stadium_details.block_color','sell_tickets.add_by',
                    DB::raw(" CASE
                            WHEN sell_tickets.source_type = 'tixstock' AND DATE(sell_tickets.ticket_updated_date) < '".$today_date."' THEN 0
                            ELSE 1
                        END AS current_ticket")
                    )
                    ->having('current_ticket',1)
                    ->where('sell_tickets.match_id',$result->m_id)
                    ->where('sell_tickets.status',1)
                    // ->where('sell_tickets.status',1)
                   // ->where(function ($query) {
                   //      $query->where('sell_tickets.split', '!=' , 3)
                   //      ->where('sell_tickets.quantity', '!=' , 1);
                   //  })
                    ->leftJoin('stadium_seats', function($join)  {
                        $join->on('stadium_seats.id', '=', 'sell_tickets.ticket_category');
                    })
                    ->leftJoin('stadium_seats_lang', function($join) use($lang) {
                        $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_seats.id')
                        ->where('stadium_seats_lang.language',$lang);
                    })
                     ->leftJoin('match_info', function($join) use($lang) {
                        $join->on('match_info.m_id', '=', 'sell_tickets.match_id');
                    })
                   ->leftJoin('stadium_details', function($join)  {
                        $join->on('stadium_details.category', '=', 'sell_tickets.ticket_category')
                        ->on('stadium_details.stadium_id','=','match_info.venue');
                    })
                    ->leftJoin('stadium_details as stadium_block', function($join)  {
                        $join->on('stadium_block.id', '=', 'sell_tickets.ticket_block');
                    })
                    // ->leftJoin('cart_session', function($join)  {
                    //     $join->on('cart_session.sell_id', '=', 'sell_tickets.s_no')
                    //       ->where('cart_session.cart_status',2)
                    //     ->where('cart_session.expriy_datetime','>', Carbon::now());
                    // })
                  
                    ->groupBy('sell_tickets.s_no')
                    ->where('sell_tickets.quantity','>',0)
                    ->orderBy('sell_tickets.price','asc');
                    $price_source_type = array();

                    if($result->oneboxoffice_status == 1 ){
                       $price_source_type[] = '1boxoffice';
                    }
                    if($result->tixstock_status == 1 ){
                        $price_source_type[] = 'tixstock';
                    }
                    if($result->oneclicket_status == 1 ){
                        $price_source_type[] = 'oneclicket';
                    }
                    if($result->xs2event_status == 1 ){
                        $price_source_type[] = 'xs2event';
                    }
                   
                    $price = $price->whereIn('sell_tickets.source_type',$price_source_type);

            $price = $price->get();
        }
        $total_quantity = 0;//return response(array("test" => $price),200);
        $price_type =  @$value->price_type;
        if(count(@$price)){
            foreach ($price as $key => $value) {

                if($value->split == 3 && $value->quantity == 1){
                   unset($price[$key]);
                }
                else{
                
                    $seller_id     = @$value->add_by; 
                    $seller_markup = @$this->seller_markup;
                    if(@$value->add_by != ""){

                         $seller_markup = get_markup($seller_id,$this->store_id, @$this->markup);
                         
                    }

                    $vprice = @$value->price ;
                    $price_type =  @$value->price_type;
                    $total_price = "";
                    if(@$vprice){
                        $vprice = get_percentage($vprice,$seller_markup);
                        $total_price =  get_currency($price_type,$currency,$vprice,0);
                        $total_price_sym =  get_currency($price_type,$currency,$vprice,1);
                    }
                    $price[$key]->price =  @$total_price ? $total_price : "";
                    $price[$key]->ticket_price =  @$total_price ? $total_price : "";

                    $price[$key]->currency_code =  @$total_price ? trim($currency) : "";
                    $price[$key]->currency_symbol =  @$total_price ? get_symbol($currency) : "" ;
                    $price[$key]->price_with_symbol =  @$total_price ? $total_price_sym : "" ;

                    $total_quantity += $value->quantity;
                }
            }

        } 
        $result->total_quantity = $total_quantity;
        $stadium_id = $result->stadium_id;
       // echo $stadium_id ;die;
        $category_list = SellTickets::select('sell_tickets.ticket_category','stadium_seats_lang.seat_category','stadium_details.block_color','stadium_seats_lang.stadium_seat_id')
                            ->where('sell_tickets.match_id',$result->m_id)
                            ->leftJoin('stadium_seats', function($join)  {
                                $join->on('stadium_seats.id', '=', 'sell_tickets.ticket_category');
                            })
                            ->leftJoin('stadium_seats_lang', function($join) use($lang) {
                                $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_seats.id')
                                ->where('stadium_seats_lang.language',$lang);
                            })
                             ->leftJoin('stadium', function($join) use($lang) {
                                $join->on('stadium.s_id', '=', 'sell_tickets.ticket_category');
                            })
                            ->leftJoin('stadium_details', function($join) use($stadium_id) {
                                $join->on('stadium_details.category', '=', 'sell_tickets.ticket_category')
                                ->where('stadium_details.stadium_id',$stadium_id);
                            })
                            ->groupBy('sell_tickets.ticket_category')
                            ->orderBy('sell_tickets.price','asc')
                            ->where('sell_tickets.quantity','>',0)
                           ->where('sell_tickets.status',1)
                            ->where('stadium_details.source_type','1boxoffice');
                            

                            if($result->tixstock_status == 0){
                                $category_list = $category_list->whereNull('sell_tickets.tixstock_id'); 
                            }
         

                  $category_list =     $category_list->get();
        $quantity = SellTickets::where('match_id',$result->m_id)
                            ->max('quantity');   


        if($stadium_type == 2){


              $price_list = array();
                foreach ($price as $key => $value6) {
                    if($value6->ticket_block){

                        $price_list[$value6->ticket_block] =  $value6->price_with_symbol;
                    }
                }
                $stadium  = DB::table('stadium')
                                 ->select('stadium.*')
                                ->where('s_id',$stadium_id)


                                ->first();

                 $stadium->stadium_image_short =  str_replace("/uploads/stadium", "", $stadium->stadium_image);
                 $stadium->stadium_image =  "https://www.listmyticket.com/".$stadium->stadium_image;

                 $StadiumDetails  = DB::table('stadium_details')
                                ->select('stadium_details.*','stadium_seats_lang.seat_category')
                                 ->leftJoin('stadium_seats_lang', function($join) use($lang) {
                                    $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_details.category')
                                    ->where('stadium_seats_lang.language',$lang);
                                })
                                ->where('stadium_id',$stadium_id)
                                ->where('source_type','1boxoffice')
                                ->get();

                $someArray = array();
                foreach($StadiumDetails as $values){
                   $block_price =  @$price_list[$values->id] ? $price_list[$values->id] :   "";

                    $someArray[] =  array(
                        'id'                => $values->block_id,
                        'full_block_name'   => $values->full_block_name,
                        'block_color'       => $values->block_color,
                        'category'          => $values->category,
                        'price'             => @$block_price,
                        'seat_category'     => $values->seat_category
                            );
                
                }
               
                $a = array_values($someArray);
               
                  $someArray['source'] = $stadium->stadium_image;
                $stadium->map_code =  json_encode($a);
        
        }
        else{

              $seat_list = StadiumDetails::select('stadium_details.block_color','stadium_seats_lang.seat_category','stadium_seats_lang.stadium_seat_id')
                            ->where('stadium_details.stadium_id',$stadium_id)
                            ->leftJoin('stadium_seats_lang', function($join) use($lang) {
                                $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_details.category')
                                ->where('stadium_seats_lang.language',$lang);
                            })
                            ->whereNotNull('stadium_seats_lang.seat_category')
                            ->groupBy('stadium_details.category')
                            ->orderBy('stadium_details.id')
                            ->get();

        

            $tot_cats = [];
            $stadium_category = StadiumSeats::leftJoin('stadium_seats_lang', function($join) use($lang) {
                        $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_seats.id');
                        })
                    ->groupBy('stadium_seats.id')
                    ->get();
            if ($stadium_category) {
                foreach ($stadium_category as $std_cat) {
                    $tot_cats[$std_cat->stadium_seat_id] = $std_cat->stadium_seat_id;
                }
            }
           
       
            
           
            $stadium_details =  StadiumDetails::
                    leftJoin('stadium_seats_lang', function($join) use($lang) {
                        $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_details.category')
                        ->where('stadium_seats_lang.language',$lang);
                    })
                     ->where('source_type','1boxoffice')
                    ->where('stadium_id',$stadium_id)->orderBy('stadium_details.id','asc')->get();
           
           
          // pr($stadium_details); die;

            $set_stadium_blocks = [];
            $set_active_stadium_blocks = [];
            $set_stadium_blocks_with_cat = [];
            $set_stadium_cat_name = [];
            if ($stadium_details) {
                foreach ($stadium_details as $stdm) {
                    $set_stadium_blocks[$stdm->block_id] = $stdm->block_color;
                    $set_active_stadium_blocks[$stdm->block_id] = @$stdm->active_color;
                    //pr($tot_cats); die;
                     $set_stadium_blocks_with_cat[@$tot_cats[$stdm->category]][] = $stdm->block_id;
                    $set_stadium_cat_name[$stdm->category][] = @$tot_cats[$stdm->category];
                }
            }
        

            $full_block_data = array();
            $ticket_price_info = [];
            $ticket_price_info_with_cat = [];
            if(count($price)){
                foreach ($price as $sl_tckt) {
                  //  pr($sl_tckt);die;
                //  $seller_id     = @$sl_tckt->add_by; 
                //  $seller_markup = @$this->seller_markup;
                // if(@$sl_tckt->add_by != ""){

                //      $seller_markup = get_markup($seller_id,$this->store_id, @$this->markup);
                     
                // }

                //     $vprice = @$sl_tckt->price ;
                //     $price_type =  @$sl_tckt->price_type;

                //     $vprice = get_percentage($vprice,$seller_markup);
                //     $total_price =  get_currency($price_type,$currency,$vprice,1);

                    $lowest_price = @$sl_tckt->price_with_symbol;
                    $ticket_price_info[@$sl_tckt->ticket_category][@$sl_tckt->block_id]['price'][] =$lowest_price;
                    $ticket_price_info[$sl_tckt->ticket_category][$sl_tckt->block_id]['no_of_ticket'][] = (int) $sl_tckt->quantity;

                          $full_block_data[$sl_tckt->ticket_category][] = $sl_tckt->block_id;
              

                }
          
            }
            

            if ($ticket_price_info) {
                foreach ($ticket_price_info as $cat_key => $tckt_prc) {
                    //echo $cat_key;
                    $tckt_price = [];
                    $tckt_ticket = 0;
                    foreach ($tckt_prc as $sub_tckt_prc) {
                        $tckt_price = array_merge($sub_tckt_prc['price'], $tckt_price);
                        $tckt_ticket = (float) $tckt_ticket + array_sum($sub_tckt_prc['no_of_ticket']);
                    }

                    $total_price = min($tckt_price);
                    // $price_type =  @$tckt_prc->price_type;


                    // $vprice = get_percentage($tckt_price,$this->percentage);
                    // $total_price =  get_currency($price_type,$currency,$vprice,0);

                    $ticket_price_info_with_cat[$cat_key]['name'] = $cat_key;
                    $ticket_price_info_with_cat[$cat_key]['price'] = $total_price;
                    $ticket_price_info_with_cat[$cat_key]['no_of_ticket'] = $tckt_ticket;
                }
            }

            // pr($tot_cats);
            // die;
            //pr($ticket_price_info_with_cat);die;




           // pr($set_stadium_blocks);die;
        
        }
        $rprice = SellTickets::select(DB::raw('MIN(price) AS min_price'),DB::raw('MAX(price) AS max_price'))->where('status',1)->where('quantity','>',0)->where('price','>',0)->where('match_id',$result->m_id)->first();

        $result->min_price = @$rprice->min_price ? get_currency($price_type,$result->price_type,$rprice->min_price,0) : 0 ;
        $result->max_price = @$rprice->max_price ? get_currency($price_type,$result->price_type,$rprice->max_price,0) : 0 ;

        $response = array(
                
                'price' => $price,
                "result" => $result,
                'quantity' => $quantity,
                'category_list' => $category_list,
                
                "stadium_type" => $stadium_type,

                );

        if($stadium_type  == 2){

                  $response['stadium'] = $stadium;
        }
        else{

             $response['seat_list'] =  $seat_list; 
                $response['full_block_data'] =  json_encode($full_block_data); 
                $response['set_stadium_blocks'] = json_encode($set_stadium_blocks) ;
                $response['set_active_stadium_blocks'] = json_encode($set_active_stadium_blocks) ;
                $response['set_stadium_blocks_with_cat'] = json_encode($set_stadium_blocks_with_cat) ;
                $response['set_stadium_cat_name'] = json_encode($set_stadium_cat_name) ;
                $response['ticket_price_info'] = json_encode($ticket_price_info) ;
                $response['ticket_price_info_with_cat'] = json_encode($ticket_price_info_with_cat) ;

          
        }

        return response($response);
    }


     public function sell_ticket_details(Request $request){
        $store_id      = $this->store_id;
        $price = SellTickets::select('sell_tickets.*',DB::raw('SUM(cart_session.no_ticket) as no_ticket'),'cart_session.expriy_datetime')
                    ->where('sell_tickets.match_id',$request->match_id)
                    ->where('sell_tickets.s_no',$request->ticket_id)
                    ->where('sell_tickets.status',1)
                    ->leftJoin('cart_session', function($join)  {
                        $join->on('cart_session.sell_id', '=', 'sell_tickets.s_no')
                        ->where('cart_session.cart_status',2)
                        ->where('cart_session.expriy_datetime','>', Carbon::now());
                    })
                    ->orderBy('expriy_datetime','asc')
                    ->groupBy('sell_tickets.s_no');
        $price = $price->first();


        $seconds = "0" ;
        if(@$price->expriy_datetime){
            $to_time = strtotime($price->expriy_datetime);
            $from_time = strtotime(date("Y-m-d H:i:s"));
            $seconds = ($to_time -  $from_time) + 30;
          
        }
        $expire_time = $seconds ;
        return response(array(
                    "quantity" => @$price->quantity ? $price->quantity - $price->no_ticket:  0 ,
                    "total_quantity" => $price->quantity ? $price->quantity : 0 ,
                    "total_blocked" => $price->no_ticket ? (int)$price->no_ticket : 0  ,
                    'expire_time' => $expire_time,
                    'split'         => $price->split
            ),200);
    }

    public function sell_ticket_fliter(Request $request)
    {   
        $client_country  = @$request->client_country ? $request->client_country : "IN";
        $currency  = @$request->currency ? $request->currency : "GBP";
        $lang  = $request->lang ? $request->lang : "en";
        $limit  = $request->limit ? $request->limit : 50;
        $page = $request->page ? (($request->page - 1) * $limit) : 0;
        $tixpage = $request->page ? $request->page : 1;
        $user_token = @$request->user_token ;
        if($request->match_id){



            $category = $request->category;
            $quantity = $request->quantity;
            $block_id = $request->block_id;
            $price_range = $request->price_range;
            $seller_id = @$request->seller_id;
            $seller_id = @$request->seller_id;
            $clicksourcetype = @$request->clicksourcetype;
            $ip_address = @$request->ip_address;

            $price_with_fees = @$request->price_with_fees;
            $seat_together = @$request->seats_together;
            $sub_category = @$request->sub_category;
            if($sub_category){
                $category = $sub_category;
            }

           
            $match = MatchInfo::where('m_id',$request->match_id)->where('status',1)->first();
            if(empty($match)){
             $response['result']= ["message" => "Match not found",'status' => 0];
                return response($response, 422);
            }
            $tournament_id = @$match->tournament;
            $match_id = $match->m_id;
            //$match_date =  date("Y-m-d H:i:s" ,strtotime($match->match_date."-1 day")) ;
            $match_date =  date("Y-m-d H:i:s" ,strtotime($match->match_date)) ;
            $now =  date("Y-m-d H:i:s");

            $match_on           = $match->match_date;
            $today_date         = date("Y-m-d H:i:s");
            $hourdiff           = round((strtotime($match_on) - strtotime($today_date))/3600, 1);
            $updatehourdiff     = round((strtotime($today_date) - strtotime($match->tixstock_update_date))/3600, 1);
           // return response(array("result" => 'if'.$match->tixstock_status),200);
            //if(@$request->sourcetype != "1boxoffice"){
                if(!empty($match)){
                 if($user_token && $page == 0 && ($category == 0 || $category == ''  ) && ($quantity == '' || $quantity == 0  ) ){
                    DB::table('search_events')->where('match_id',$request->match_id)->where('user_token',$user_token)->delete();
                }

                if($user_token && $page == 0 && $match->oneboxoffice_status == 1 && ($category == 0 || $category == ''  ) && ( $quantity == '' || $quantity == 0 ) && $seat_together != 1  ){
                    $this->get_tickets($request->match_id,'1boxoffice',$user_token);
                }

                if($match->xs2event_id && $match->xs2event_status == 1 ){
                    try
                    {
                        //echo $match->m_id.'-'.$match->venue.'-'.$match->xs2event_id.'-'.$limit.'-'.$tixpage.'-'.$user_token.'-'.$quantity.'-'.$category.'-'.$block_id;exit;
                        Xs2event::get_ticket_list_pagination($match->m_id,$match->venue,$match->xs2event_id,$limit,$tixpage,$user_token,$quantity,$category,$block_id);
                    }catch(BP\InsureHubApiNotFoundException $validationException){
                        $error = $validationException->errorMessage();
                    }
                    catch(BP\InsureHubApiValidationException $validationException){
                        $error = $validationException->errorMessage();
                    }
                    catch(BP\InsureHubApiAuthorisationException $authorisationException){
                        $error = $authorisationException->errorMessage();
                    }
                    catch(BP\InsureHubApiAuthenticationException $authenticationException){
                        $error = $authenticationException->errorMessage();
                    }
                    catch(BP\InsureHubException $insureHubException){
                        $error =  $insureHubException->errorMessage();
                    }
                    catch(Exception $exception){
                        $error = $exception->getMessage();
                        
                    }
                }
               if($match->tixstock_id && $match->tixstock_status == 1 ){

                $ref = $this->store_id."-".$clicksourcetype."-".$lang."-".$ip_address;
                    try
                    {
                        Tixstock::get_ticket_list_pagination($match->m_id,$match->venue,$match->tixstock_id,$limit,$tixpage,$user_token,$quantity,$category,$block_id,$match->price_type,$ref);
                    }catch(BP\InsureHubApiNotFoundException $validationException){
                        $error = $validationException->errorMessage();
                    }
                    catch(BP\InsureHubApiValidationException $validationException){
                        $error = $validationException->errorMessage();
                    }
                    catch(BP\InsureHubApiAuthorisationException $authorisationException){
                        $error = $authorisationException->errorMessage();
                    }
                    catch(BP\InsureHubApiAuthenticationException $authenticationException){
                        $error = $authenticationException->errorMessage();
                    }
                    catch(BP\InsureHubException $insureHubException){
                        $error =  $insureHubException->errorMessage();
                    }
                    catch(Exception $exception){
                        $error = $exception->getMessage();
                        
                    }
                    
                }

                if($match->oneclicket_id && $match->oneclicket_status == 1 && $category == 0 && $quantity == '' && ($hourdiff >= 40)){/*
                    try{
                    Tixstock::get_oneclicket_list($match->m_id,$match->venue,$match->oneclicket_id);
                    }catch(BP\InsureHubApiNotFoundException $validationException){
                    $error = $validationException->errorMessage();
                    }
                    catch(BP\InsureHubApiValidationException $validationException){
                    $error = $validationException->errorMessage();
                    }
                    catch(BP\InsureHubApiAuthorisationException $authorisationException){
                    $error = $authorisationException->errorMessage();
                    }
                    catch(BP\InsureHubApiAuthenticationException $authenticationException){
                    $error = $authenticationException->errorMessage();
                    }
                    catch(BP\InsureHubException $insureHubException){
                    $error =  $insureHubException->errorMessage();
                    }
                    catch(Exception $exception){
                    $error = $exception->getMessage();
                    }

                */}
                else{
                    if($hourdiff <= 40){
                        $up_response = array('status' => 0);
                        DB::table('sell_tickets')->where('match_id',$request->match_id)->where('source_type','oneclicket')->where('status',1)->update($up_response);
                    }
                    
                }

                }

                else{
                    $response['result']= ["message" => "Match not found",'status' => 0];
                    return response($response, 422);

                }
            //}
            //pr($match);
            $price_source_type = array();
        
            if($match->oneboxoffice_status == 1 ){
               $price_source_type[] = '1boxoffice';
            }
            if($match->tixstock_status == 1 ){
                $price_source_type[] = 'tixstock';
            }
            if($match->oneclicket_status == 1 ){
                $price_source_type[] = 'oneclicket';
            }
            if($match->xs2event_status == 1 ){
                $price_source_type[] = 'xs2event';
            }
            

            $price =  DB::table('search_events')->select(
                            'search_events.user_token',
                            'sell_tickets.*',
                            'stadium_seats_lang.seat_category',
                            'match_info.venue as stadium_id',
                            'stadium_block.block_id',
                            'stadium_block.full_block_name',
                            'stadium_details.block_color',
                            'sell_tickets.add_by',
                            DB::raw('( sell_tickets.quantity ) as quantity'),
                            'cart_session.no_ticket','cart_session.expriy_datetime',
                            'ticket_types_lang.name  as ticket_type_name',
                            'ticket_types_lang.ticket_image as ticket_type_image',
                            DB::raw('( sell_tickets.quantity + IFNULL(`cart_session`.`no_ticket`,0)) as quantity_2'),'cart_session.ip')
                    ->where('sell_tickets.status',1)
                    ->where('sell_tickets.price','>',0)
                    ->having('quantity_2','>',0)
                    ->where('sell_tickets.match_id',$request->match_id)
                    ->where('search_events.user_token',$user_token);
                    
           
                    // ->where(function ($query) {
                    //      $query->where('sell_tickets.split', '!=' , 3)
                    //      ->where('sell_tickets.quantity', '!=' , 1);
                    //  })


        $price = $price->leftJoin('sell_tickets', function($join) use($user_token)  {
                         $join->on( 'sell_tickets.match_id','search_events.match_id')
                          ->on( 'sell_tickets.s_no','search_events.ticket_id');        
                    });

        $price = $price->leftJoin('stadium_seats', function($join)  {
                        $join->on('stadium_seats.id', '=', 'sell_tickets.ticket_category');
                    })
                    ->leftJoin('stadium_seats_lang', function($join) use($lang) {
                        $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_seats.id')
                        ->where('stadium_seats_lang.language',$lang);
                    })
                     ->leftJoin('match_info', function($join) use($lang) {
                        $join->on('match_info.m_id', '=', 'sell_tickets.match_id');
                    })
                   ->leftJoin('stadium_details', function($join)  {
                        $join->on('stadium_details.category', '=', 'sell_tickets.ticket_category')
                        ->on('stadium_details.stadium_id','=','match_info.venue');
                    })
                    ->leftJoin('stadium_details as stadium_block', function($join)  {
                        $join->on('stadium_block.id', '=', 'sell_tickets.ticket_block');
                    })
                    ->leftJoin('cart_session', function($join)  {
                        $join->on('cart_session.sell_id', '=', 'sell_tickets.s_no')
                        ->where('cart_session.cart_status',2)
                        ->where('cart_session.expriy_datetime','>', Carbon::now());
                    })
                    ->leftJoin('ticket_types', function($join)  {
                        $join->on('ticket_types.id', '=', 'sell_tickets.ticket_type');
                    })

                   ->leftJoin('ticket_types_lang', function($join)  use($lang)  {
                        $join->on('ticket_types_lang.ticket_type_id', '=', 'ticket_types.id')
                        ->where('ticket_types_lang.language',$lang);
                    })
                   
                    ->groupBy('sell_tickets.s_no')
                    ->having('quantity_2','>',0)
                    ->where('sell_tickets.price','>',0)
                    ->orderBy('sell_tickets.price','asc')

                    ->whereIn('sell_tickets.source_type',$price_source_type);

        if($category) $price = $price->whereIn('ticket_category',explode(",",$category));
        if($quantity) $price = $price->where('sell_tickets.quantity','>=',$quantity);
        if($block_id) $price = $price->where('stadium_block.block_id',$block_id);
        if($price_range){ 
            $price_range_arr = explode("-", $price_range);
            $price = $price->whereBetween('sell_tickets.price', [$price_range_arr[0], $price_range_arr[1]]);
        }
        if($seller_id) $price = $price->where('user_id',$seller_id);

        if ($seat_together){
            $listing_note = "93";
            $price = $price->where(function($q) use ($quantity,$listing_note){
                $q->whereRaw('FIND_IN_SET(?, sell_tickets.listing_note)', [$listing_note]);
                if($quantity == 4 ){
                    $q->orWhereRaw('FIND_IN_SET(?, sell_tickets.listing_note)', ["31"]);
                }
                if($quantity == 2 ){
                    $q->orWhereRaw('FIND_IN_SET(?, sell_tickets.listing_note)', ["28"]);
                }
             });
            //  $price = $price->whereRaw('FIND_IN_SET(?, sell_tickets.listing_note)', [$listing_note]);
        }


        // if($match->tixstock_status  == 1 && ($match_date > $now) ){
        //         $price = $price->whereIn('sell_tickets.source_type',array('1boxoffice','tixstock'));
        // }
        // else{
        //     $price = $price->whereIn('sell_tickets.source_type',array('1boxoffice'));
        // }
        
        //else   $price = $price->where('sell_tickets.quantity','>',0);
        //echo $price = $price->toSql(); die;
        $price =  $price->skip($page)->take($limit);
        $price =  $price->groupBy('sell_tickets.s_no')->orderBy('sell_tickets.price','asc');
        $price = $price->get();
        
        // pr($page.'='.$limit); die;
        $block_ids = array();
        $price_list = array();
        if(count($price)){
            foreach ($price as $key => $value) {

                if($value->no_ticket > 0){

                    $cart = SellTickets::select('sell_tickets.*',DB::raw('SUM(cart_session.no_ticket) as no_ticket'),'cart_session.expriy_datetime')
                            ->where('sell_tickets.match_id',$request->match_id)
                            ->where('sell_tickets.s_no',$value->s_no)
                            ->where('sell_tickets.status',1)
                            ->leftJoin('cart_session', function($join)  {
                                $join->on('cart_session.sell_id', '=', 'sell_tickets.s_no')
                                  ->where('cart_session.cart_status',2)
                                ->where('cart_session.expriy_datetime','>', Carbon::now());
                            })
                            ->orderBy('expriy_datetime','asc')
                            ->groupBy('sell_tickets.s_no');
                    $cart = $cart->first();
                    $price[$key]->no_ticket  = $cart->no_ticket;
                }


                $price[$key]->match_date_time_current = $match_date." ".$now;

                $seconds = "0" ;
                if(@$value->expriy_datetime){
                    $to_time = strtotime($value->expriy_datetime);
                    $from_time = strtotime(date("Y-m-d H:i:s"));
                    $seconds = ($to_time -  $from_time) + 30;
                  
                }
   
                $price[$key]->expire_time = $seconds ;

                 $price[$key]->ticket_type_image = IMAGE_PATH."ticket_image/".$value->ticket_type_image;

                $ticket_details = array();
                if($value->listing_note){
                    $notes =  explode(",", $value->listing_note);
                    $ticket_details = DB::table("ticket_details")
                    ->select('ticket_details_lang.ticket_name',DB::raw('CONCAT("'.IMAGE_PATH.'ticket_details/", ticket_details_lang.ticket_image) AS ticket_image'))
                     ->leftJoin('ticket_details_lang', function($join) use($lang) {
                                $join->on('ticket_details_lang.ticket_details_id', '=', 'ticket_details.id')
                                    ->where('language',$lang);
                            })
                    ->whereIn('ticket_details.id',$notes)
                    ->get();
                } 
                $price[$key]->ticket_details = $ticket_details;

                 if($value->split == 3 && $value->quantity == 1){
                   unset($price[$key]);
                }

                else{
                    // $price[$key]->new_quantity =  Carbon::now() ." -". date("Y-m-d H:i");
                    $seller_id = @$value->add_by; 
                    $seller_markup = @$this->seller_markup;
                    if(@$value->add_by != ""){

                     $seller_markup = get_markup($seller_id,$this->store_id, @$this->markup);
                     
                    }
                    $vprice = @$value->price ;
                    $price_type =  @$value->price_type;
                    $total_price = "";
                    if(@$vprice){
                        $vprice = get_percentage($vprice,$seller_markup);

                        $additional_store_markup = 0;
                        if($vprice  <= 100){
                            $additional_store_markup = $this->additional_store_markup;
                        }
                        if(@$price_with_fees){
                            $current_markup  = get_tournament_markup($tournament_id,$match_id,$this->store_id, @$this->store_markup);
                            $current_markup =  ( $current_markup + $additional_store_markup);
                            $store_price = get_percentage_only($vprice,$current_markup);
                            $vprice = $vprice + $store_price;
                        }

                        $total_price =  get_currency_decial($price_type,$currency,$vprice,0);
                        $total_price_sym =  get_currency_decial($price_type,$currency,$vprice,1);
                    }
                    $price[$key]->price =  @$total_price ? $total_price : "";
                    $price[$key]->ticket_price =  @$total_price ? $total_price : "";

                    $price[$key]->currency_code =  @$total_price ? trim($currency) : "";
                    $price[$key]->currency_symbol =  @$total_price ? get_symbol($currency) : "" ;
                    $price[$key]->price_with_symbol =  @$total_price ? $total_price_sym : "" ;

                    $price[$key]->view_now = rand(100,500);
                }

                 if($value->ticket_block){
                    $block_ids[] = $value->ticket_block;
                    $price_list[$value->ticket_block] =  @$value->price_with_symbol;
                }
               

            }
           
        }

            $stadium_id = $match->venue; 
            $block_lists = array();
            if($block_ids){
                $StadiumDetails  = DB::table('stadium_details')
                                ->select('stadium_details.*','stadium_seats_lang.seat_category')
                                ->leftJoin('stadium_seats_lang', function($join) use($lang) {
                                    $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_details.category')
                                    ->where('stadium_seats_lang.language',$lang);
                                })
                               // ->where('stadium_id',$stadium_id)
                                ->whereIn('stadium_details.id',$block_ids)
                                ->where('source_type','1boxoffice')
                                ->get();

               
                foreach($StadiumDetails as $values){
                   $block_price =  @$price_list[$values->id] ? $price_list[$values->id] :   "";
                         $block_lists[] =  array(
                        'id'                => $values->block_id,
                        'full_block_name'   => $values->full_block_name,
                        'block_color'       => $values->block_color,
                        'category'          => $values->category,
                        'price'             => @$block_price,
                        'seat_category'     => $values->seat_category
                            );
                }  
            }   
             
            return response(array("result" => $price,'block_lists' => $block_lists),200);
        }else{
            $response['result']= ["message" => "Match Id Invalid",'status' => 0];
                    return response($response, 422);
        }
    }

     public function get_stadium_id(Request $request)
    {
         $client_country  = @$request->client_country ? $request->client_country : "IN";
        if($request->stadium_id){
             $stadium  = Stadium::select('*')
                            ->where('s_id',$request->stadium_id)
                            ->first();
            $stadium->stadium_image_short =  str_replace("/uploads/stadium/", "", @$stadium->stadium_image);
             $stadium->stadium_image =  IMAGE_PATH."stadium".str_replace("/uploads/stadium", "", $stadium->stadium_image);
            // $mapcode = json_decode($stadium->map_code,true);
            // $mapcode['source'] = IMAGE_PATH."stadium".$mapcode['source'];
            // $stadium->map_code =  json_encode($mapcode);

             $mapcode = json_decode($stadium->map_code,true);
            
             $mapcode['source'] = $stadium->stadium_image;
             
             $stadium->map_code =  json_encode($mapcode);


             $StadiumDetails  = StadiumDetails::select('*')
                            ->where('stadium_id',$request->stadium_id)
                            ->where('source_type','1boxoffice')
                            ->get();


                $regions = array();

                foreach($StadiumDetails as $values){

                        $block_id = $values->block_id;
                        $regions = array();
                        $regions['id'] = $block_id;
                        $regions['id_no_spaces'] = $block_id;
                        $regions['fill'] = $values->block_color;
                        $regions['href'] = $values->category;
                        $regions['tooltip'] = $values->category;

                        $regions['data'] = array();

                        $someArray['regions'][$block_id] = $regions;
                }

                 $someArray['source'] = $stadium->stadium_image;
                 $stadium->map_code =  json_encode($someArray);

    
            return response(array("result" => $stadium),200);
        }else{
            $response['result']= ["message" => "Stadium Id Invalid",'status' => 0];
                    return response($response, 422);
        }
    }

    public function get_tickets($match_id,$sour_type = '1boxoffice',$user_token=""){
        $tickets = SellTickets::select('*')
                ->where('sell_tickets.match_id',$match_id)
                ->where('sell_tickets.source_type',$sour_type)
                ->where('sell_tickets.status',1)
                ->where('quantity','>',0)
                ->where('sell_tickets.price','>',0)
                ->orderBy('sell_tickets.price','asc')
                ->groupBy('sell_tickets.s_no')
                ->get();

        if($tickets){
            foreach ($tickets as $key => $value) {
                $seach_data =  array(
                    'user_token'    => $user_token,
                    'match_id'      => $value->match_id,
                    'ticket_id'     => $value->s_no,
                    'api_id'        => 1,

                );
               DB::table('search_events')->insertGetId($seach_data);
            }
        }
    }


}
