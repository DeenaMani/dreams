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

use App\Models\SiteSetting;
use App\Models\MatchInfo;
use App\Models\SellTickets;
use App\Models\Teams;
use App\Models\Tournament;
use App\Models\SeoStadium;
use App\Models\Blog;


 
class TeamsController extends Controller
{
    private $store_id;
    private $percentage;
    private $seller_markup;
    private $store_markup;
    private $default_currency;

    public function __construct(Request $request)
    {

       $this->check_token($request);
    }

    public  function check_token($request)
    { 
        $this->middleware(function($request, $next) {
            $token = $request->header('domainkey');
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
            }
            return $next($request);
        });
    }

    public function get_teams(Request $request){
        $lang  = $request->lang ? $request->lang : "en";
        $lang_col = $lang== "ar" ? "_".$lang :"";
        $keywords = $request->keywords ? $request->keywords : ""; 
        $sort_by = strtoupper(@$request->sort_by) == "DESC" ? "DESC" : "ASC"; 
        $country = $request->country ? $request->country : ""; 
        $currency  = @$request->currency ? $request->currency : "GBP";
        $client_country  = @$request->client_country ? $request->client_country : "IN";

        $limit = $request->limit ?  $request->limit : 10;
        $page = $request->page ? (($request->page - 1) * $limit) : 0;

         $current_date =  date('Y-m-d');

        $teams = Teams::select('teams.id','teams.url_key','teams_lang.team_name','teams.team_image','teams.team_color','teams.team_url','teams_lang_a.team_name as team_name_a','teams_lang_b.team_name as team_name_b','m.slug as match_slug',
                                     'm.oneboxoffice_status',
                                     'm.tixstock_status',
                                     'm.oneclicket_status','m.m_id','ml.match_name',DB::raw('COUNT(DISTINCT m.m_id) as total_match'),'countries.name as country_name','states.name as state_name','cities.name as city_name',
            'stadium.stadium_name'.$lang_col." as stadium_name",
            'teams.team_bg',DB::raw('IF(`sell_tickets`.`s_no` > 0 , 1, 0) as total_ticket'),DB::raw('IF(COUNT(DISTINCT m.m_id) > 0 , 1, 0) as match_order'))
                    ->where('teams.status',1)
                    ->where('teams.show_status',1)
                    ->where('teams.category',1)
                    ->leftJoin('teams_lang', function($join) use($lang) {
                        $join->on('teams_lang.team_id', '=', 'teams.id')
                        ->where('language',$lang);
                    })
                    //  ->leftJoin( DB::raw('(select match_info.* from match_info LEFT JOIN match_settings ON match_settings.matches = match_info.m_id  where Date(match_date) > "'.Carbon::now().'" AND  match_info.status = 1  AND FIND_IN_SET('.$this->store_id.', match_settings.storefronts) !=0   order by match_date ASC ) m'), function($join) use($lang) {
                    ->leftJoin( DB::raw('(select match_info.* from match_info   where IF (match_info.source_type = "tixstock", DATE_SUB( match_info.match_date, INTERVAL 1 DAY) , match_info.match_date) >=  "'.current_date().'"  AND  match_info.status = 1 AND (tixstock_status = "1" || oneboxoffice_status = "1"  || oneclicket_status = "1"  ) order by match_date ASC ) m'), function($join) use($lang) {

                        // $join->on('m.team_1','=','teams.id');
                        // $join->orOn('m.team_2','=','teams.id');
                        $join->whereRaw('(m.team_1 = teams.id || m.team_2 = teams.id)' );
                    })
                    ->leftJoin('match_info_lang as  ml', function($join) use($lang) {
                        $join->on('ml.match_id','=','m.m_id')
                        ->where('ml.language',$lang);
                    })
                    ->leftJoin('teams as team_a', function($join) {
                        $join->on('team_a.id', '=', 'm.team_1');
                    })
                    ->leftJoin('teams as team_b', function($join) {
                        $join->on('team_b.id', '=', 'm.team_2');
                    })
                    ->leftJoin('teams_lang as teams_lang_a', function($join) use ($lang) {
                        $join->on('teams_lang_a.team_id', '=', 'team_a.id')
                         ->where('teams_lang_a.language',$lang);
                    })
                    ->leftJoin('teams_lang as teams_lang_b', function($join) use ($lang){
                        $join->on('teams_lang_b.team_id', '=', 'team_b.id')
                         ->where('teams_lang_b.language',$lang);
                    })
                    ->leftJoin('sell_tickets', function($join) {
                        $join->on('m.m_id', '=', 'sell_tickets.match_id')
                                ->where('sell_tickets.quantity','>',0)
                                ->where('sell_tickets.status',1);
                    })
                    ->leftJoin('countries', function($join) use($lang) {
                        $join->on('countries.id','=','m.country');
                    })
                    ->leftJoin('states', function($join) use($lang) {
                        $join->on('states.id', '=', 'm.state')
                                   ;
                    })
                    ->leftJoin('cities', function($join) use($lang) {
                        $join->on('cities.id', '=', 'm.city')
                                   ;
                    })
                    ->leftJoin('stadium', function($join) use($lang) {
                        $join->on('stadium.s_id', '=', 'm.venue');
                    })
                    ->leftJoin('tournament', function($join) {
                        $join->on('tournament.t_id', '=', 'm.tournament');
                    })
                    ->leftJoin('match_settings', function($join) use($lang) {
                                $join->on('match_settings.matches', '=', 'm.m_id')
                                   ;
                    })
                    ->whereRaw('FIND_IN_SET(?, match_settings.storefronts)', [$this->store_id])
                    //->having('total_match','>',0)
                    ->where('tournament.status',1)
                    ->groupBy("teams.id")
                    ->orderBy('total_ticket','desc')
                    ->orderBy('match_order','desc')
                    ->skip($page)->take($limit);
        if($keywords) { 
             $teams =  $teams->where(function($query) use ($keywords) {
                $query->where('teams_lang.team_name', 'LIKE', "%{$keywords}%")
                ->orwhere('countries.name', 'LIKE', "%{$keywords}%")
                ->orwhere('states.name', 'LIKE', "%{$keywords}%")
                ->orwhere('stadium.stadium_name_ar', 'LIKE', "%{$keywords}%")
                ->orwhere('stadium.stadium_name', 'LIKE', "%{$keywords}%");
            });

        }
        if($sort_by) $teams = $teams->orderBy('teams_lang.team_name',$sort_by);
        if($country) $teams = $teams->where('m.country',$country);
        $teams = $teams->get();
        foreach ($teams as $key => $value) {
             $prices = DB::table('sell_tickets')
                            ->where('sell_tickets.status',1)
                            ->where('sell_tickets.match_id',$value->m_id)
                            ->select('price','price_type','add_by')->where('quantity','>',0)->orderBy('price','asc')
                            ->where('sell_tickets.price',">",0) 
                            ->orderby('sell_tickets.price','ASC');
                            $price_source_type = array();
                            if($value->oneboxoffice_status == "1" ){
                               $price_source_type[] = '1boxoffice';
                            }
                            if($value->tixstock_status == "1" ){
                                $price_source_type[] = 'tixstock';
                            }
                            if($value->oneclicket_status == "1" ){
                                $price_source_type[] = 'oneclicket';
                            }
                    
                   $prices = $prices->whereIn('sell_tickets.source_type',$price_source_type);
                    $prices = $prices->first();

            $seller_id = @$prices->add_by; 
            $seller_markup = @$this->markup;

            if(@$prices->add_by != ""){

                 $seller_markup = get_markup($seller_id,$this->store_id, @$this->markup);
            }
            //$price = @$prices->price ;
            $price = "" ;

            $teams[$key]->request_type = @$price ? "book" : "request";
            
            $price_type =  @$prices->price_type;

            $total_price = "";
            if(@$price){  
                $price = get_percentage($price,$seller_markup);      
                $total_price =  get_currency($price_type,$currency,$price,0);
                $total_price_sym =  get_currency($price_type,$currency,$price,1);
            }
            $teams[$key]->ticket_price =  @$total_price ? $total_price : "";

            $teams[$key]->currency_code =  @$total_price ? trim($currency) : "";
            $teams[$key]->currency_symbol =  @$total_price ? get_symbol($currency) : "" ;
            $teams[$key]->price_with_symbol =  @$total_price ? $total_price_sym : "" ;

            $teams[$key]->team_image =  IMAGE_PATH."teams/".$value->team_image;

            $team_bg =  $value->team_bg? $value->team_bg: "team.jpg";
            $teams[$key]->team_bg =  IMAGE_PATH."background/".$team_bg ;

            $teams[$key]->match_name  = $value->match_name ? $value->match_name : "";
           
        }  
        return response(array("results" => $teams),200);
    }

    public function top_teams(Request $request){
        $lang  = $request->lang ? $request->lang : "en";
        $currency  = @$request->currency ? $request->currency : "GBP";
        $client_country  = @$request->client_country ? $request->client_country : "IN";
        $top_teams = Teams::select('teams.id','teams.popular_team as popular_team','teams.url_key','teams_lang.team_name','teams.team_image','teams.team_color','teams.team_url','teams_lang_a.team_name as team_name_a','teams_lang_b.team_name as team_name_b','ml.match_name',DB::raw('COUNT(DISTINCT m.m_id) as total_match'),'teams.team_bg','m.slug as match_slug','m.tixstock_status as tixs','m.m_id','m.oneboxoffice_status',
                                     'm.tixstock_status',
                                     'm.oneclicket_status')
                    ->where('teams.status',1)
                    ->where('teams.popular_team',1)
                    ->leftJoin('teams_lang', function($join) use($lang) {
                        $join->on('teams_lang.team_id', '=', 'teams.id')
                        ->where('language',$lang);
                    })
                    ->leftJoin( DB::raw('(select * from match_info where IF (match_info.source_type = "tixstock", DATE_SUB( match_info.match_date, INTERVAL 1 DAY) , match_info.match_date) >=  "'.current_date().'"  AND status = 1 AND (tixstock_status = "1" || oneboxoffice_status = "1"  || oneclicket_status = "1"  )  order by match_date ASC ) m'), function($join) use($lang) {
                        $join->whereRaw('(m.team_1 = teams.id || m.team_2 = teams.id)' );
                    })
                    ->leftJoin('match_info_lang as  ml', function($join) use($lang) {
                        $join->on('ml.match_id','=','m.m_id')
                        ->where('ml.language',$lang);
                    })
                     ->leftJoin('match_settings', function($join) use($lang) {
                                $join->on('match_settings.matches', '=', 'm.m_id')
                                   ;
                    })
                     ->leftJoin('teams as team_a', function($join) {
                            $join->on('team_a.id', '=', 'm.team_1');
                    })
                    ->leftJoin('teams as team_b', function($join) {
                        $join->on('team_b.id', '=', 'm.team_2');
                    })
                    ->leftJoin('teams_lang as teams_lang_a', function($join) use ($lang) {
                        $join->on('teams_lang_a.team_id', '=', 'team_a.id')
                         ->where('teams_lang_a.language',$lang);
                    })
                    ->leftJoin('teams_lang as teams_lang_b', function($join) use ($lang){
                        $join->on('teams_lang_b.team_id', '=', 'team_b.id')
                         ->where('teams_lang_b.language',$lang);
                    })
                    ->whereRaw('FIND_IN_SET(?, match_settings.storefronts)', [$this->store_id])
                    ->having('total_match','>',0)
                    ->orderBy("teams_lang.team_name", "asc")

                    ->groupBy("teams.id")
                    ->get();
        //dd(DB::getQueryLog()); 
        foreach ($top_teams as $key => $value) {
             $prices = DB::table('sell_tickets')
                            ->select('sell_tickets.price','sell_tickets.price_type','sell_tickets.add_by')

                           
                            ->where('sell_tickets.quantity','>',0)
                            ->orderBy('sell_tickets.price','asc')
                            ->where('sell_tickets.status',1)
                             ->where('sell_tickets.price','>',0)
                            ->where('sell_tickets.match_id',$value->m_id)
                            ->orderby('sell_tickets.price','ASC')

                            ;
                    if($value->oneboxoffice_status == "1" ){
                               $price_source_type[] = '1boxoffice';
                            }
                            if($value->tixstock_status == "1" ){
                                $price_source_type[] = 'tixstock';
                            }
                            if($value->oneclicket_status == "1" ){
                                $price_source_type[] = 'oneclicket';
                            }
                             $prices = $prices->whereIn('sell_tickets.source_type',$price_source_type);
                    $prices = $prices->first();


            $top_teams[$key]->request_type = @$prices->price ? "book" : "request";
            $seller_id = @$prices->add_by; 
            $seller_markup = @$this->markup;
            $price = @$prices->price ;
            $price_type =  @$prices->price_type;

            if(@$prices->add_by != ""){

                 $seller_markup = get_markup($seller_id,$this->store_id, @$this->markup);
            }

            $total_price = "";
            if(@$price){  
                $price = get_percentage($price,$seller_markup);      
                $total_price =  get_currency($price_type,$currency,$price,0);
                $total_price_sym =  get_currency($price_type,$currency,$price,1);
            }
            $top_teams[$key]->ticket_price =  @$total_price ? $total_price : "";

            $top_teams[$key]->currency_code =  @$total_price ? trim($currency) : "";
            $top_teams[$key]->currency_symbol =  @$total_price ? get_symbol($currency) : "" ;
            $top_teams[$key]->price_with_symbol =  @$total_price ? $total_price_sym : "" ;

            $top_teams[$key]->team_image =  IMAGE_PATH."teams/".$value->team_image;
            $top_teams[$key]->match_name  = $top_teams[$key]->match_name ? $top_teams[$key]->match_name : "";
            $team_bg =  $value->team_bg? $value->team_bg: "team.jpg";
            $top_teams[$key]->team_bg =  IMAGE_PATH."background/".$team_bg ;
        }
        return response(array("results" => $top_teams),200);
    }


     public function team_league_detail(Request $request){
   
        $team_leagu_name  = $request->team_leagu_name ? $request->team_leagu_name : "";
        $type = @$request->type ? $request->type : "";
        $lang  = $request->lang ? $request->lang : "en";
        $lang_col = $lang== "ar" ? "_".$lang :"";
        $keywords  = @$request->keywords ? $request->keywords : "";
        $month  = $request->month ? $request->month : "";
        $current_date =  date('Y-m-d');
        $currency  = @$request->currency ? $request->currency : "GBP";
        $tournment  = @$request->tournment ? $request->tournment : "";
        $client_country  = @$request->client_country ? $request->client_country : "IN";
        $result_limit = $request->limit ?  $request->limit : 10;
        $page = $request->page ? (($request->page - 1) * $result_limit) : 0;

        if($result_limit == 2000){
            $page = 10;
        }

        $store_id = $this->store_id;
       // return response(array("request" => $team),200);
        $team = Teams::select('teams_lang.*','teams.url_key','teams.id','teams.team_image','teams.team_bg')
                ->leftJoin('teams_lang', function($join) use($lang,$store_id) {
                    $join->on('teams_lang.team_id', '=', 'teams.id')
                        ->where('language',$lang)
                        ->where('teams_lang.store_id',$store_id);
                })
                ->where('teams.url_key',$team_leagu_name)->first();

        if($team != ""){
          
        $team_id = $team->id;

        $team_ticket  = MatchInfo::select('match_info.m_id','match_info.slug',
                                    'match_info.match_date',
                                    'match_info.match_time',
                                    'match_info.event_type',
                                    'match_info.high_demand',
                                    'match_info_lang.match_name',
                                    'match_info_lang.language',
                                    'match_info_lang.extra_title',
                                    'match_info_lang.event_image',
                                    'match_info.tbc_status',
                                    'team_a.team_image as team_image_a',
                                     'team_a.url_key as team_a_url_key',
                                    'team_b.team_image as team_image_b',
                                    'team_b.url_key as team_b_url_key',
                                    'teams_lang_a.team_name as team_name_a',
                                    'teams_lang_b.team_name as team_name_b',
                                    'tournament_lang.tournament_name',  
                                    'tournament_lang.tournament_id',   
                                    'stadium.stadium_name'.$lang_col." as stadium_name",
                                    'match_info.ignoreautoswitch',
                                    'countries.name as country_name',   
                                    'states.name as state_name',
                                    'cities.name as city_name',
                                    'match_info.oneboxoffice_status',
                                     'match_info.tixstock_status',
                                     'match_info.oneclicket_status',

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
                            ->leftJoin('teams_lang as teams_lang_a', function($join) use ($lang) {
                                $join->on('teams_lang_a.team_id', '=', 'team_a.id')
                                 ->where('teams_lang_a.language',$lang);
                            })
                            ->leftJoin('teams_lang as teams_lang_b', function($join) use ($lang,$store_id){
                                $join->on('teams_lang_b.team_id', '=', 'team_b.id')
                                 ->where('teams_lang_b.language',$lang);
                                 //->where('teams_lang_b.store_id',$store_id);
                            })
                            ->leftJoin('tournament', function($join) {
                                $join->on('tournament.t_id', '=', 'match_info.tournament');
                            })
                            ->leftJoin('tournament_lang', function($join) use($lang) {
                                $join->on('tournament_lang.tournament_id', '=', 'tournament.t_id')
                                    ->where('tournament_lang.language',$lang);
                            })
                             ->leftJoin('stadium', function($join) use($lang) {
                                $join->on('stadium.s_id', '=', 'match_info.venue')
                                   ;
                            })
                            ->leftJoin('match_settings', function($join) use($lang) {
                                $join->on('match_settings.matches', '=', 'match_info.m_id')
                                   ;
                            })
                             ->leftJoin('countries', function($join) use($lang) {   
                                $join->on('countries.id','=','match_info.country'); 
                            })  
                            ->leftJoin('states', function($join) use($lang) {   
                                $join->on('states.id', '=', 'match_info.state') 
                                           ;    
                            })
                            ->leftJoin('cities', function($join) use($lang) {
                                $join->on('cities.id', '=', 'match_info.city')
                                           ;
                            })
                           ->whereRaw('FIND_IN_SET(?, match_settings.storefronts)', [$this->store_id])
                            ->where('match_info.status',1)
                            ->where(function ($query){
                                $query->where('match_info.oneboxoffice_status', '=', "1")
                                    ->orWhere('match_info.tixstock_status', '=', "1")
                                    ->orWhere('match_info.oneclicket_status', '=', "1");
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
                            ->where('match_info.event_type','match')
                            ->where(function($query) use ($team_id) {
                                $query->where('match_info.team_1',$team_id)->orwhere('match_info.team_2',$team_id);
                            })
                            ->where('tournament.status',1)
                           // ->having('match_tixstock_status',1)
                            ->where('match_info_lang.language',$lang)
                            ->where(DB::raw('IF (match_info.source_type = "tixstock", DATE_SUB( match_info.match_date, INTERVAL 1 DAY) , match_info.match_date)'),'>=', current_date())
                            ->groupBy('match_info.m_id')
                            ->orderBy('match_info.match_date','ASC');

            if($keywords){
                $team_ticket = $team_ticket->where(function($query) use ($keywords) {
                                $query->where('teams_lang_a.team_name', 'LIKE', "%{$keywords}%")
                                    ->orwhere('teams_lang_b.team_name', 'LIKE', "%{$keywords}%");
                            });
            }
            $tournment_ticket = $team_ticket->get();
            if($tournment != "")
            $team_ticket = $team_ticket->where('match_info.tournament','=',$tournment);
            if($type == "home")
            $team_ticket = $team_ticket->where('match_info.hometown','=',$team_id);

            if($type == "away")
            $team_ticket = $team_ticket->where('match_info.hometown','!=',$team_id);

            $team_ticket = $team_ticket->get();
             $tournaments_data = array();
            foreach ($tournment_ticket as $key => $value) {

                $tournaments_data[] = array('tournament_id' => $value->tournament_id,'tournament_name' => $value->tournament_name);
            }
            if(!empty($tournaments_data)){
                    $tournaments_data = array_unique($tournaments_data, SORT_REGULAR);
            }
            foreach ($team_ticket as $key => $value) {
                $now_date =  date('Y-m-d H:i');
                $match_date  = date("Y-m-d H:i",strtotime($value->match_date));
                if($value->ignoreautoswitch  == 0 && $now_date >= $match_date){
                    $price  = array();
                }
                else{

                    $prices = DB::table('sell_tickets')
                    ->select(DB::raw('MIN(sell_tickets.price) as price'),'sell_tickets.price_type','sell_tickets.add_by',DB::raw('SUM(sell_tickets.quantity) as total_quantity'))
                    // ->leftJoin('match_info', function($join) {
                    //             $join->on('sell_tickets.match_id', '=', 'match_info.m_id');
                               
                    //         })
                    ->where('sell_tickets.match_id',$value->m_id)
                    ->where('sell_tickets.quantity','>',0)
                    ->where('sell_tickets.status',1)
                    ->where('sell_tickets.price',">",0) 
                    ->groupBy('sell_tickets.match_id')
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

                   
                    $prices = $prices->whereIn('sell_tickets.source_type',$price_source_type);

                    $prices = $prices->first();
                }
                $team_ticket[$key]->price_source_type = $value->oneboxoffice_status." ".$value->tixstock_status." ".$value->oneclicket_status;
                $team_ticket[$key]->total_quantity = @$prices->total_quantity ? $prices->total_quantity : 0 ;
                $team_ticket[$key]->request_type = @$prices->price ? "book" : "request";

                $seller_id = @$prices->add_by; 
                $seller_markup = @$this->markup;

                if(@$prices->add_by != ""){

                 $seller_markup = get_markup($seller_id,$this->store_id, @$this->markup);
                }

                $vprice = @$prices->price ;
                $price_type =  @$prices->price_type;
                $total_price = "";
                if(@$vprice){
                    $vprice = get_percentage($vprice,$seller_markup);
                    $total_price =  get_currency($price_type,$currency,$vprice,0);
                    $total_price_sym =  get_currency($price_type,$currency,$vprice,1);
                }
                $team_ticket[$key]->min_price =  @$total_price ? $total_price : "";
                $team_ticket[$key]->ticket_price =  @$total_price ? $total_price : "";

                $team_ticket[$key]->currency_code =  @$total_price ? trim($currency) : "";
                $team_ticket[$key]->currency_symbol =  @$total_price ? get_symbol($currency) : "" ;
                $team_ticket[$key]->price_with_symbol =  @$total_price ? $total_price_sym : "" ;

               // $top_matchs[$key]->price = 5000;
                $team_ticket[$key]->match_date  = date("d F Y",strtotime($value->match_date));
                $team_ticket[$key]->match_day = date("d",strtotime($value->match_date));    
                $team_ticket[$key]->match_month  = date("M",strtotime($value->match_date)); 
                $team_ticket[$key]->match_year  = date("Y",strtotime($value->match_date));
                $team_ticket[$key]->team_image_a =  IMAGE_PATH."teams/".$value->team_image_a;
                $team_ticket[$key]->team_image_b =  IMAGE_PATH."teams/".$value->team_image_b;
                if($value->tbc_status == 1){    
                    $team_ticket[$key]->match_date = tbc;   
                    $team_ticket[$key]->match_time = "";    
                 }
            }
            $team->team_image = IMAGE_PATH."teams/".$team->team_image;

       
            $team_bg =  $team->team_bg? $team->team_bg: "team.jpg"; 
            $team->team_bg =  IMAGE_PATH."background/".$team_bg ;


               $latestBlogs = Blog::select(
                 DB::raw("CONCAT('".IMAGE_PATH."blog/',blog.blog_small) as blog_small"),
                DB::raw("CONCAT('".IMAGE_PATH."blog/',blog.blog_medium) as blog_medium"),
                DB::raw("CONCAT('".IMAGE_PATH."blog/',blog.blog_large) as blog_large"),
                'blog.blog_slug','blog_lang.blog_title','blog.blog_author','blog_lang.blog_description','blog_lang.blog_short_description','blog_lang.meta_title','blog_lang.meta_description','blog_lang.seo_keywords','blog_category_lang.category_name','blog.created_at',DB::raw("DATE_FORMAT(blog.created_at,'%d %M %Y') as created_date"),DB::raw("DATE_FORMAT(blog.created_at,'%H:%i') as created_time"))
                        ->where('blog.blog_status','1')
                        ->where('blog.blog_type',2)
                        ->join('blog_lang', function($join) use($lang) {
                                $join->on('blog_lang.blog_id', '=', 'blog.id')
                                ->where('blog_lang.language',$lang);
                        })
                        
                        ->leftJoin('blog_category', function($join) use($lang) {
                                $join->on('blog_category.id', '=', 'blog.blog_category');
                        })
                        ->leftJoin('blog_category_lang', function($join) use($lang) {
                                $join->on('blog_category_lang.blog_category_id', '=', 'blog_category.id')
                                ->where('blog_category_lang.language',$lang);
                        })
                        ->where('blog.store_id',$this->store_id)
                        ->whereRaw('FIND_IN_SET(?, blog.team)', [$team_id])
                        ->orderBy('blog.id','desc')
                        ->limit(3)
                        ->get();
            return response(array("data_type" => "team","team" => $team,"result" => $team_ticket,'tournaments' => $tournaments_data,'latestBlogs' => $latestBlogs),200);

        }
        else{                     

        $month      = $request->month ? $request->month : "";
        $team       = $request->team ? $request->team : "";
        $currency   = @$request->currency ? $request->currency : "GBP";

        $current_date =  date('Y-m-d');


         $tournament = Tournament::select('tournament.*','tournament_lang.tournament_name','tournament_category.category_name as tournament_category','tournament_category.slug as tournament_category_slug','tournament_lang.page_content','tournament_lang.page_title','tournament_lang.meta_description','tournament.tournament_image')
                            ->leftJoin('tournament_lang', function($join) use($lang,$store_id) {
                                $join->on('tournament_lang.tournament_id', '=', 'tournament.t_id')
                                    ->where('tournament_lang.language',$lang)
                                    ->where('tournament_lang.store_id',$store_id);
                            })
                            ->leftJoin('tournament_category', function($join) use($lang,$store_id){
                                $join->on('tournament_category.category_id', '=', 'tournament.league_cup')
                                ->where('tournament_category.language',$lang);
                            })
                        ->where('tournament.url_key',$team_leagu_name)->first();
        if($tournament ==""){
             return response(['message'=> 'Invalid Url'], 422);
        } 
        $tournament_id = $tournament->t_id;
        $tournament->tournament_image =  IMAGE_PATH."tournaments/".$tournament->tournament_image;
        $lang_col = $lang== "ar" ? "_".$lang :"";
        $results = MatchInfo::select('match_info.m_id','match_info.slug',
                                    'match_info.match_date',
                                    'match_info.tbc_status',
                                    'match_info.match_time','match_info.high_demand','match_info_lang.match_name','match_info_lang.language','match_info_lang.extra_title','match_info_lang.description','match_info_lang.meta_title','match_info_lang.meta_description','match_info_lang.event_image',
                                        'match_info.team_1','match_info.team_2',
                                        'teams_lang_a.team_name as team_name_a',
                                        'teams_lang_b.team_name as team_name_b',
                                        'team_a.url_key as team_slug_a',
                                        'team_b.url_key as team_slug_b',
                                        'team_a.team_image as team_image_a',
                                        'team_b.team_image as team_image_b',
                                        'venue as stadium_id',
                                        'stadium.stadium_name'.$lang_col." as stadium_name",
                                        'cities.name as city_name',
                                        'match_info.ignoreautoswitch',
                                        'match_info.source_type',
                                        'match_info.oneboxoffice_status',
                                         'match_info.tixstock_status',
                                         'match_info.oneclicket_status',
             

                                    )
                            ->leftJoin('match_info_lang', function($join) use($lang) {
                                $join->on('match_info_lang.match_id', '=', 'match_info.m_id')
                                    ->where('language',$lang);
                            })
                             ->leftJoin('stadium', function($join) use($lang) {
                                $join->on('stadium.s_id', '=', 'match_info.venue')
                                   ;
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

                            ->leftJoin('cities', function($join) use($lang) {
                                $join->on('cities.id', '=', 'match_info.city')
                                           ;
                            })
                            
                            ->leftJoin('tournament', function($join) {
                                $join->on('tournament.t_id', '=', 'match_info.tournament');
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
                            ->where('tournament.status',1)
                            ->where(function ($query){
                            $query->where('match_info.oneboxoffice_status', '=', "1")
                                ->orWhere('match_info.tixstock_status', '=', "1")
                                ->orWhere('match_info.oneclicket_status', '=', "1");
                            })
                            //->having('match_tixstock_status',1)
                            ->whereRaw('FIND_IN_SET(?, match_settings.storefronts)', [$this->store_id])
                            ->where('match_info.event_type','match')
                            ->where('match_info.tournament',$tournament_id)
                            ->where('match_info.status',"1")
                            ->orderBy('match_date','asc')  
                            ->groupBy('match_info.m_id')  
                            ->skip($page)->take($result_limit);
                              
                            
                            if ($keywords) {
                                $results->where(function ($query) use ($keywords) {
                                    $query->where('teams_lang_a.team_name', 'LIKE', "%{$keywords}%")
                                        ->orWhere('teams_lang_b.team_name', 'LIKE', "%{$keywords}%");
                                });
                            }

                            
            $results= $results->where(DB::raw('IF (match_info.source_type = "tixstock", DATE_SUB( match_info.match_date, INTERVAL 1 DAY) , match_info.match_date)'),'>=', current_date());
                     

            $second_res = $results->get();

            if($month){
                $results= $results->whereRaw('MONTH(match_info.match_date) = '.$month);
            }
            if($team){
                $results= $results->where(function ($query) use ($team) {
                $query->where('match_info.team_1', '=', $team)
                ->orWhere('match_info.team_2', '=', $team);
                });

            }

            $features_ticket = array() ;
          
            $results = $results->get();        
        
            
            $team_ids = array();
            foreach ($results as $key => $value) {
                $now_date =  date('Y-m-d H:i');
                $match_date  = date("Y-m-d H:i",strtotime($value->match_date));
                if($value->ignoreautoswitch  == 0 && $now_date >= $match_date){
                    $price  = array();
                }
                else{
                    $prices = DB::table('sell_tickets')
                    // ->select('sell_tickets.price','sell_tickets.price_type','sell_tickets.add_by'
                    //     //,DB::raw("IF( match_info.source_type = '1boxoffice' AND match_info.tixstock_status = '0' ,1 ,match_info.tixstock_status)  AS match_tixstock_status")
                    //     )
                        ->select(DB::raw('MIN(sell_tickets.price) as price'), 'sell_tickets.price_type', 'sell_tickets.add_by', DB::raw('SUM(sell_tickets.quantity) as total_quantity'))
                    ->leftJoin('match_info', function($join) {
                                $join->on('sell_tickets.match_id', '=', 'match_info.m_id');
                               
                            })
                   

                    //->having('match_tixstock_status','1')
                    ->where('sell_tickets.match_id',$value->m_id)
                    ->where('sell_tickets.quantity','>',0)
                     ->where('sell_tickets.price','>',0)
                    ->where('sell_tickets.status','=',1)
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

                   
                    $prices = $prices->whereIn('sell_tickets.source_type',$price_source_type);
                    $prices = $prices->first();
                     $results[$key]->price_source_type = $price_source_type;
                }
               
                $results[$key]->request_type = @$prices->price ? "book" : "request";
                $results[$key]->total_quantity = @$prices->total_quantity ? $prices->total_quantity : 0 ;
                $seller_id = @$prices->add_by; 
                $seller_markup = @$this->markup;
                $price = @$prices->price ;
                $price_type =  @$prices->price_type;
                $total_price = "";
                if(@$prices->add_by != ""){

                $seller_markup = get_markup($seller_id,$this->store_id, @$this->markup);
                }
                if(@$price){
                    $price = get_percentage($price,$seller_markup);
                    $total_price =  get_currency($price_type,$currency,$price,0);
                    $total_price_sym =  get_currency($price_type,$currency,$price,1);
                }
                $results[$key]->min_price =  @$total_price ? $total_price : "";

                $results[$key]->currency_code =  @$total_price ? trim($currency) : "";
                $results[$key]->currency_symbol =  @$total_price ? get_symbol($currency) : "" ;
                $results[$key]->price_with_symbol =  @$total_price ? $total_price_sym : "" ;

               // $top_matchs[$key]->price = 5000;
                $results[$key]->match_date  = date("d F Y",strtotime($value->match_date));
                $results[$key]->match_day  = date("l",strtotime($value->match_date));
                $results[$key]->team_image_a =  IMAGE_PATH."teams/".$value->team_image_a;
                $results[$key]->team_image_b =  IMAGE_PATH."teams/".$value->team_image_b;
                $team_ids[$value->team_1] = $value->team_1;
                $team_ids[$value->team_2] = $value->team_2;

                if($value->tbc_status == 1){   
                    $results[$key]->match_date = tbc;   
                    $results[$key]->match_time = "";    
                    $results[$key]->match_day = ""; 
                }
            }

            $second_team_ids = array();
            foreach ($second_res as $key => $value) {
                $prices = DB::table('sell_tickets')->where('status',1)->where('sell_tickets.match_id',$value->m_id)->select('price','price_type','add_by')->where('quantity','>',0)->orderBy('price','asc')->first();
                $second_team_ids[$value->team_1] = $value->team_1;
                $second_team_ids[$value->team_2] = $value->team_2;
            }
            //pr($team_ids); die;
            $default_teams = array();
             // $default_teams = Teams::select('teams.id','teams.url_key','teams_lang.team_name','teams.team_image','teams.team_color','teams.team_url')
             //        // ->where('teams.status',1)
             //        // ->where('teams.popular_team',1)
             //        ->leftJoin('teams_lang', function($join) use($lang) {
             //            $join->on('teams_lang.team_id', '=', 'teams.id')
             //            ->where('language',$lang);
             //        })
             //        ->whereIn('teams.id',$second_team_ids)
             //        ->orderBy("teams_lang.team_name", "asc")
             //        ->groupBy("teams.id")
             //        ->get();

                    
                    // $other_tournament = Tournament::select('tournament.*', 'tournament_lang.*',DB::raw('COUNT(DISTINCT m.m_id) as total_match'))
                    //     ->leftJoin('tournament_lang', 'tournament_lang.tournament_id', '=', 'tournament.t_id')
                    //     ->leftJoin( DB::raw('(select m_id,tournament,source_type,oneboxoffice_status,tixstock_status,oneclicket_status  from  match_info  where status = 1  AND (tixstock_status = 1 || oneboxoffice_status = 1  || oneclicket_status = 1  )  AND IF (match_info.source_type = "1boxoffice", match_info.match_date , DATE_SUB( match_info.match_date, INTERVAL 1 DAY) ) >=  "'.current_date().'"  order by match_date ASC ) m'), function($join) use($lang) {
                    //         $join->on('m.tournament', '=', 'tournament.t_id');
                    //     })
                    //     ->where('tournament_lang.language', $lang)
                    //     ->where('tournament.status', 1)
                    //     ->where('tournament.url_key', 'not like', $team_leagu_name)
                    //     ->orderBy('tournament_lang.tournament_name', 'ASC')
                    //    ->get();

                    $other_tournament = Tournament::select('tournament.t_id','tournament.url_key','tournament_lang.tournament_name','tournament.tournament_image','tournament.tournament_url',DB::raw('COUNT(DISTINCT m.m_id) as total_match'),'m.m_id','m.source_type','m.oneboxoffice_status',
                                     'm.tixstock_status',
                                     'm.oneclicket_status')
                                ->where('tournament.status',1)
                                ->where('tournament.url_key', 'not like', $team_leagu_name)
                                ->leftJoin('tournament_lang', function($join) use($lang) {
                                    $join->on('tournament_lang.tournament_id', '=', 'tournament.t_id')
                                        ->where('tournament_lang.language',$lang);
                                })
                                ->leftJoin( DB::raw('(select m_id,tournament,source_type,oneboxoffice_status,tixstock_status,oneclicket_status  from  match_info  where status = 1  AND (tixstock_status = 1 || oneboxoffice_status = 1  || oneclicket_status = 1  )  AND IF (match_info.source_type = "1boxoffice", match_info.match_date , DATE_SUB( match_info.match_date, INTERVAL 1 DAY) ) >=  "'.current_date().'"  order by match_date ASC ) m'), function($join) use($lang) {
                                    $join->on('m.tournament', '=', 'tournament.t_id');
                                })
                               
                                ->leftJoin('match_settings', function($join) use($lang) {
                                        $join->on('match_settings.matches', '=', 'm.m_id')
                                           ;
                                    })
                                //->whereRaw('FIND_IN_SET(?, match_settings.storefronts)', [$this->store_id])
                               // ->having('total_match','>',0)
                               ->where('tournament.tournament_image', '!=', '')
                               ->where('tournament.category','1')
                                ->orWhereNull('tournament.tournament_image')
                                ->groupBy('tournament.t_id')
                                ->orderBy('tournament.sort_by','asc')
                                ->orderBy('tournament_lang.tournament_name','asc')
                                ->get();

                       foreach ($other_tournament as $key => $value) {           

                        $ticket_available = MatchInfo::select(DB::raw("SUM(sell_tickets.`quantity`) as total"))
                                             ->where('tournament',$value->t_id)
                                             ->leftJoin('match_settings', function($join) use($lang) {
                                                     $join->on('match_settings.matches', '=', 'match_info.m_id')
                                                        ;
                                                 })
                                              ->where('sell_tickets.status',1)
                                              ->where('sell_tickets.quantity','>',0)
                                              ->leftJoin('sell_tickets', function($join) {
                                                     $join->on('sell_tickets.match_id', '=', 'match_info.m_id')
                                                    ;
             
                                                 })
                                                 ->whereRaw('FIND_IN_SET(?, match_settings.storefronts)', [$this->store_id])
                                             ->where(DB::raw('IF (match_info.source_type = "tixstock", DATE_SUB( match_info.match_date, INTERVAL 1 DAY) , match_info.match_date)'),'>=', current_date()) 
             
                                            
                                              ->where('match_info.status', '=', 1)
                                             ->where(function ($query){
                                                 $query->where('match_info.oneboxoffice_status', '=', "1")
                                                     ->orWhere('match_info.tixstock_status', '=', "1")
                                                     ->orWhere('match_info.oneclicket_status', '=', "1");
                                             })
                                             ->groupBy('tournament')->first();
                         $other_tournament[$key]->ticket_available =  @$ticket_available->total ;
              }

                    $latestBlogs = Blog::select(
                 DB::raw("CONCAT('".IMAGE_PATH."blog/',blog.blog_small) as blog_small"),
                DB::raw("CONCAT('".IMAGE_PATH."blog/',blog.blog_medium) as blog_medium"),
                DB::raw("CONCAT('".IMAGE_PATH."blog/',blog.blog_large) as blog_large"),
                'blog.blog_slug','blog_lang.blog_title','blog.blog_author','blog_lang.blog_description','blog_lang.blog_short_description','blog_lang.meta_title','blog_lang.meta_description','blog_lang.seo_keywords','blog_category_lang.category_name','blog.created_at',DB::raw("DATE_FORMAT(blog.created_at,'%d %M %Y') as created_date"),DB::raw("DATE_FORMAT(blog.created_at,'%H:%i') as created_time"))
                        ->where('blog.blog_status','1')
                        ->where('blog.blog_type',2)
                        ->join('blog_lang', function($join) use($lang) {
                                $join->on('blog_lang.blog_id', '=', 'blog.id')
                                ->where('blog_lang.language',$lang);
                        })
                        
                        ->leftJoin('blog_category', function($join) use($lang) {
                                $join->on('blog_category.id', '=', 'blog.blog_category');
                        })
                        ->leftJoin('blog_category_lang', function($join) use($lang) {
                                $join->on('blog_category_lang.blog_category_id', '=', 'blog_category.id')
                                ->where('blog_category_lang.language',$lang);
                        })
                        ->where('blog.store_id',$this->store_id)
                        ->where('blog.tournament',$tournament_id)
                        ->orderBy('blog.id','desc')
                        ->limit(3)
                        ->get();
                                        
                    $latest_transactions = DB::table('booking_global')
                    ->select('booking_tickets.match_name', 'booking_tickets.quantity as ticket_sold')
                    ->leftJoin('booking_tickets', 'booking_tickets.booking_id', '=', 'booking_global.bg_id')
                    ->leftJoin('tournament', 'tournament.t_id', '=', 'booking_tickets.tournament_id')
                    ->whereIn('booking_global.booking_status',[1,2,4,5,6])
                    ->where('tournament.url_key', $team_leagu_name)
                    ->where('booking_tickets.match_date', '>=', now())
                      ->where('booking_global.created_at', '>', now()->subDays(90)->endOfDay())
                    ->groupBy('booking_tickets.match_date')
                    ->orderBy('booking_tickets.booking_id', 'desc')
                    ->limit(3)
                    ->get();

                    foreach ($latest_transactions as $key => $value) {
                        $latest_transactions[$key]->lang =  $lang;                         
                        }

            $teams = Teams::select('teams.id','teams.url_key','teams_lang.team_name','teams.team_image','teams.team_color','teams.team_url','seo_city_list.city_name','seo_city_list.url_key as city_url','seo_venue_list_lang.venue_name','seo_venue_list.url_key as  venue_url','seo_country_list.url_key as country_url','stadium_name')
           
            ->leftJoin('teams_lang', function($join) use($lang) {
                $join->on('teams_lang.team_id', '=', 'teams.id')
                ->where('teams_lang.language',$lang);
            })
            ->leftJoin('seo_city_list', function($join) use($lang) {
                $join->on('seo_city_list.top_city', '=', 'teams.city');
            })
            ->leftJoin('seo_city_list_lang', function($join) use($lang) {
                $join->on('seo_city_list_lang.country_id', '=', 'seo_city_list.c_id')
                ->where('seo_city_list_lang.language',$lang);
            })
            ->leftJoin('seo_venue_list', function($join) use($lang) {
                $join->on('seo_venue_list.top_venue', '=', 'teams.stadium');
            })
            ->leftJoin('seo_venue_list_lang', function($join) use($lang) {
                $join->on('seo_venue_list_lang.venue_id', '=', 'seo_venue_list.v_id')
                ->where('seo_venue_list_lang.language',$lang);
            })
             ->leftJoin('seo_country_list', function($join) use($lang) {
                $join->on('seo_country_list.top_country', '=', 'teams.country');
            })
             ->leftJoin('stadium', function($join) use($lang) {
                $join->on('stadium.s_id', '=', 'teams.stadium');
            })  
           
            ->leftJoin( DB::raw('(select * from match_info where status = 1 AND match_date >=  NOW() - INTERVAL 3 MONTH  order by match_date ASC ) m'), function($join) use($lang) {
                $join->on('m.team_1','=','teams.id');
                $join->orOn('m.team_2','=','teams.id');
            })
            ->leftJoin('match_settings', function($join) use($lang) {
                            $join->on('match_settings.matches', '=', 'm.m_id');
                         })
            ->whereRaw('FIND_IN_SET(?, match_settings.storefronts)', [$this->store_id])
            ->where('teams.status',1)
            ->where('teams.show_status',1)
            ->where('m.tournament',$tournament->t_id)
            //  ->where('teams.header_top_teams',1)
            ->take(1000)
            //->having('total_match','>',0)
            ->orderBy("teams_lang.team_name", "asc")
            ->groupBy("teams.id")
            ->leftJoin('banned_countries_tournament as bct', function($join) use($client_country) {
                    $join->on('bct.tournament_id', '=', 'm.tournament')
                     ->where('bct.country_code', '=', $client_country);  
                     })
            ->WhereNull('bct.country_code')
            ->get();

            foreach ($teams as $key => $value) {
               $teams[$key]->team_image =  IMAGE_PATH."teams/".$value->team_image;   
            }
          
        return response(array("data_type" => "tournament","tournament" => $tournament,"result" => $results,'teams'=> $teams,'default_teams' => $default_teams,'latestBlogs'=>$latestBlogs,'features_match' => $features_ticket,'other_tournament'=>$other_tournament,'latest_transactions'=>$latest_transactions),200);
    
        
    }

  }


   public function team_ticket(Request $request,$slug,$type=''){
        $lang  = $request->lang ? $request->lang : "en";
        $lang_col = $lang== "ar" ? "_".$lang :"";
        $keywords  = @$request->keywords ? $request->keywords : "";
        $current_date =  date('Y-m-d');
        $client_country  = @$request->client_country ? $request->client_country : "IN";
        $currency  = @$request->currency ? $request->currency : "GBP";
         //return response(array("team" => $slug,"result" => $slug),200);
        $team = Teams::select('teams_lang.*','teams.url_key','teams.id','teams.team_image')
                ->leftJoin('teams_lang', function($join) use($lang) {
                    $join->on('teams_lang.team_id', '=', 'teams.id')
                        ->where('language',$lang);
                })
                ->where('teams.team_url',$slug)->first();

        if($team ==""){
             return response(['message'=> 'Invalid Url'], 422);
        }
        $team_id = $team->id;

        $team_ticket  = MatchInfo::select('match_info.m_id','match_info.slug',
                                    'match_info.match_date',
                                    'match_info.match_time',
                                    'match_info.event_type',
                                    'match_info_lang.match_name',
                                    'match_info_lang.language',
                                    'match_info_lang.extra_title',
                                    'match_info_lang.event_image',
                                    'team_a.team_image as team_image_a',
                                     'team_a.url_key as team_a_url_key',
                                    'team_b.team_image as team_image_b',
                                    'team_b.url_key as team_b_url_key',
                                    'teams_lang_a.team_name as team_name_a',
                                    'teams_lang_b.team_name as team_name_b',
                                    'tournament.tournament_name',     
                                    'stadium.stadium_name'.$lang_col." as stadium_name", 
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
                            ->leftJoin('teams_lang as teams_lang_a', function($join) use ($lang) {
                                $join->on('teams_lang_a.team_id', '=', 'team_a.id')
                                 ->where('teams_lang_a.language',$lang);
                            })
                            ->leftJoin('teams_lang as teams_lang_b', function($join) use ($lang){
                                $join->on('teams_lang_b.team_id', '=', 'team_b.id')
                                 ->where('teams_lang_b.language',$lang);
                            })
                            ->leftJoin('tournament', function($join) {
                                $join->on('tournament.t_id', '=', 'match_info.tournament');
                            })
                             ->leftJoin('stadium', function($join) use($lang) {
                                $join->on('stadium.s_id', '=', 'match_info.venue')
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
                            ->where('match_info.status',1)
                            ->where('match_info.event_type','match')
                            ->where(function($query) use ($team_id) {
                                $query->where('match_info.team_1',$team_id)->orwhere('match_info.team_2',$team_id);
                            })
                            ->where('match_info_lang.language',$lang)
                           ->where(DB::raw('IF (match_info.source_type = "tixstock", DATE_SUB( match_info.match_date, INTERVAL 1 DAY) , match_info.match_date)'),'>=', current_date())
                            ->orderBy('match_info.match_date','ASC');

            if($keywords){
                $team_ticket = $team_ticket->where(function($query) use ($keywords) {
                                $query->where('teams_lang_a.team_name', 'LIKE', "%{$keywords}%")
                                    ->orwhere('teams_lang_b.team_name', 'LIKE', "%{$keywords}%");
                            });
            }
            if($type == "home")
            $team_ticket = $team_ticket->where('match_info.hometown','=',$team_id);

            if($type == "away")
            $team_ticket = $team_ticket->where('match_info.hometown','!=',$team_id);

            $team_ticket = $team_ticket->get();
            foreach ($team_ticket as $key => $value) {
                $prices = DB::table('sell_tickets')->where('status',1)->where('sell_tickets.match_id',$value->m_id)->select('price','price_type','add_by')->where('sell_tickets.quantity','>',0)->where('quantity','>',0)->orderBy('price','asc')->first();

                $team_ticket[$key]->request_type = @$prices->price ? "book" : "request";

                $seller_id = @$prices->add_by; 
                $seller_markup = @$this->markup;

                if(@$prices->add_by != ""){

                 $seller_markup = get_markup($seller_id,$this->store_id, @$this->markup);
                }

                $vprice = @$prices->price ;
                $price_type =  @$prices->price_type;
                $total_price = "";
                if(@$vprice){
                    $vprice = get_percentage($vprice,$seller_markup);
                    $total_price =  get_currency($price_type,$currency,$vprice,0);
                    $total_price_sym =  get_currency($price_type,$currency,$vprice,1);
                }
                $team_ticket[$key]->min_price =  @$total_price ? $total_price : "";
                $team_ticket[$key]->ticket_price =  @$total_price ? $total_price : "";

                $team_ticket[$key]->currency_code =  @$total_price ? trim($currency) : "";
                $team_ticket[$key]->currency_symbol =  @$total_price ? get_symbol($currency) : "" ;
                $team_ticket[$key]->price_with_symbol =  @$total_price ? $total_price_sym : "" ;

               // $top_matchs[$key]->price = 5000;
                $team_ticket[$key]->match_date  = date("d F Y",strtotime($value->match_date));
                $team_ticket[$key]->team_image_a =  IMAGE_PATH."teams/".$value->team_image_a;
                $team_ticket[$key]->team_image_b =  IMAGE_PATH."teams/".$value->team_image_b;

                 if($value->tbc_status == 1){    
                    $team_ticket[$key]->match_date = tbc;   
                    $team_ticket[$key]->match_time = "";    
                 }
                 
            }
        $team->team_image = IMAGE_PATH."teams/".$team->team_image;
        return response(array("team" => $team,"result" => $team_ticket),200);
    }
  
    public function get_match_by_teams_id(Request $request){
        $team_id  = @$request->team_id ? $request->team_id : "";
        $tournament_id  = @$request->tournament_id ? $request->tournament_id : "";
        $lang  = $request->lang ? $request->lang : "en";
        $lang_col = $lang== "ar" ? "_".$lang :"";
        $current_date =  date('Y-m-d');
        $currency  = @$request->currency ? $request->currency : "GBP";
        $client_country  = @$request->client_country ? $request->client_country : "IN";

        $store_id = $this->store_id;
        $team_ticket  = MatchInfo::select('match_info.m_id','match_info.slug',
                                    'match_info.match_date',
                                    'match_info.match_time',
                                    'match_info.event_type',
                                    'match_info.high_demand',
                                    'match_info_lang.match_name',
                                    'match_info_lang.language',
                                    'match_info_lang.extra_title',
                                    'match_info_lang.event_image',
                                    'match_info.tbc_status',
                                    'team_a.team_image as team_image_a',
                                     'team_a.url_key as team_a_url_key',
                                    'team_b.team_image as team_image_b',
                                    'team_b.url_key as team_b_url_key',
                                    'teams_lang_a.team_name as team_name_a',
                                    'teams_lang_b.team_name as team_name_b',
                                    'tournament_lang.tournament_name',  
                                    'tournament_lang.tournament_id',   
                                    'stadium.stadium_name'.$lang_col." as stadium_name",
                                    'match_info.ignoreautoswitch',
                                    'countries.name as country_name',   
                                    'states.name as state_name',
                                    'cities.name as city_name',
                                    'match_info.oneboxoffice_status',
                                     'match_info.tixstock_status',
                                     'match_info.oneclicket_status',

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
                            ->leftJoin('teams_lang as teams_lang_a', function($join) use ($lang) {
                                $join->on('teams_lang_a.team_id', '=', 'team_a.id')
                                 ->where('teams_lang_a.language',$lang);
                            })
                            ->leftJoin('teams_lang as teams_lang_b', function($join) use ($lang,$store_id){
                                $join->on('teams_lang_b.team_id', '=', 'team_b.id')
                                 ->where('teams_lang_b.language',$lang);
                                 //->where('teams_lang_b.store_id',$store_id);
                            })
                            ->leftJoin('tournament', function($join) {
                                $join->on('tournament.t_id', '=', 'match_info.tournament');
                            })
                            ->leftJoin('tournament_lang', function($join) use($lang) {
                                $join->on('tournament_lang.tournament_id', '=', 'tournament.t_id')
                                    ->where('tournament_lang.language',$lang);
                            })
                             ->leftJoin('stadium', function($join) use($lang) {
                                $join->on('stadium.s_id', '=', 'match_info.venue')
                                   ;
                            })
                            ->leftJoin('match_settings', function($join) use($lang) {
                                $join->on('match_settings.matches', '=', 'match_info.m_id')
                                   ;
                            })
                             ->leftJoin('countries', function($join) use($lang) {   
                                $join->on('countries.id','=','match_info.country'); 
                            })  
                            ->leftJoin('states', function($join) use($lang) {   
                                $join->on('states.id', '=', 'match_info.state') 
                                           ;    
                            })
                            ->leftJoin('cities', function($join) use($lang) {
                                $join->on('cities.id', '=', 'match_info.city')
                                           ;
                            })
                           ->whereRaw('FIND_IN_SET(?, match_settings.storefronts)', [$this->store_id])
                            ->where('match_info.status',1)
                            ->where(function ($query){
                                $query->where('match_info.oneboxoffice_status', '=', "1")
                                    ->orWhere('match_info.tixstock_status', '=', "1")
                                    ->orWhere('match_info.oneclicket_status', '=', "1");
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
                            ->where('match_info.event_type','match')
                            ->where(function($query) use ($team_id) {
                                $query->where('match_info.team_1',$team_id)->orwhere('match_info.team_2',$team_id);
                            })
                            ->where('tournament.status',1)
                           // ->having('match_tixstock_status',1)
                            ->where('match_info_lang.language',$lang)
                            ->where(DB::raw('IF (match_info.source_type = "tixstock", DATE_SUB( match_info.match_date, INTERVAL 1 DAY) , match_info.match_date)'),'>=', current_date())
                            ->groupBy('match_info.m_id')
                            ->limit(10)
                            ->orderBy('match_info.match_date','ASC');
        $team_ticket = $team_ticket->get();
        if($team_ticket){
            foreach ($team_ticket as $key => $value) {
                $now_date =  date('Y-m-d H:i');
                $match_date  = date("Y-m-d H:i",strtotime($value->match_date));
                if($value->ignoreautoswitch  == 0 && $now_date >= $match_date){
                    $price  = array();
                }
                else{

                    $prices = DB::table('sell_tickets')
                    ->select('sell_tickets.price','sell_tickets.price_type','sell_tickets.add_by',DB::raw('SUM(sell_tickets.quantity) as total_quantity'))
                    ->leftJoin('match_info', function($join) {
                                $join->on('sell_tickets.match_id', '=', 'match_info.m_id');
                               
                            })
                    ->where('sell_tickets.match_id',$value->m_id)
                    ->where('sell_tickets.quantity','>',0)
                    ->where('sell_tickets.status',1)
                    ->where('sell_tickets.price','>',0)
                    ->groupBy('sell_tickets.match_id')
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

                   
                    $prices = $prices->whereIn('sell_tickets.source_type',$price_source_type);

                    $prices = $prices->first();
                }
                $team_ticket[$key]->price_source_type = $value->oneboxoffice_status." ".$value->tixstock_status." ".$value->oneclicket_status;
                $team_ticket[$key]->total_quantity = @$prices->total_quantity ? $prices->total_quantity : 0 ;
                $team_ticket[$key]->request_type = @$prices->price ? "book" : "request";

                $seller_id = @$prices->add_by; 
                $seller_markup = @$this->markup;

                if(@$prices->add_by != ""){

                 $seller_markup = get_markup($seller_id,$this->store_id, @$this->markup);
                }

                $vprice = @$prices->price ;
                $price_type =  @$prices->price_type;
                $total_price = "";
                if(@$vprice){
                    $vprice = get_percentage($vprice,$seller_markup);
                    $total_price =  get_currency($price_type,$currency,$vprice,0);
                    $total_price_sym =  get_currency($price_type,$currency,$vprice,1);
                }
                $team_ticket[$key]->min_price =  @$total_price ? $total_price : "";
                $team_ticket[$key]->ticket_price =  @$total_price ? $total_price : "";

                $team_ticket[$key]->currency_code =  @$total_price ? trim($currency) : "";
                $team_ticket[$key]->currency_symbol =  @$total_price ? get_symbol($currency) : "" ;
                $team_ticket[$key]->price_with_symbol =  @$total_price ? $total_price_sym : "" ;

               // $top_matchs[$key]->price = 5000;
                $team_ticket[$key]->match_date  = date("d F Y",strtotime($value->match_date));
                $team_ticket[$key]->match_day = date("d",strtotime($value->match_date));    
                $team_ticket[$key]->match_month  = date("M",strtotime($value->match_date)); 
                $team_ticket[$key]->match_year  = date("Y",strtotime($value->match_date));
                $team_ticket[$key]->team_image_a =  IMAGE_PATH."teams/".$value->team_image_a;
                $team_ticket[$key]->team_image_b =  IMAGE_PATH."teams/".$value->team_image_b;
                if($value->tbc_status == 1){    
                    $team_ticket[$key]->match_date = tbc;   
                    $team_ticket[$key]->match_time = "";    
                 }
            }
        }

        
        $teams = Teams::select('teams.url_key','teams_lang.team_name as name','teams.team_url as url','seo_city_list.city_name','seo_city_list.url_key as city_url','seo_venue_list_lang.venue_name','seo_venue_list.url_key as  venue_url','seo_country_list.url_key as country_url','stadium_name')
           
            ->leftJoin('teams_lang', function($join) use($lang) {
                $join->on('teams_lang.team_id', '=', 'teams.id')
                ->where('teams_lang.language',$lang);
            })
            ->leftJoin('seo_city_list', function($join) use($lang) {
                $join->on('seo_city_list.top_city', '=', 'teams.city');
            })
            ->leftJoin('seo_city_list_lang', function($join) use($lang) {
                $join->on('seo_city_list_lang.country_id', '=', 'seo_city_list.c_id')
                ->where('seo_city_list_lang.language',$lang);
            })
            ->leftJoin('seo_venue_list', function($join) use($lang) {
                $join->on('seo_venue_list.top_venue', '=', 'teams.stadium');
            })
            ->leftJoin('seo_venue_list_lang', function($join) use($lang) {
                $join->on('seo_venue_list_lang.venue_id', '=', 'seo_venue_list.v_id')
                ->where('seo_venue_list_lang.language',$lang);
            })
             ->leftJoin('seo_country_list', function($join) use($lang) {
                $join->on('seo_country_list.top_country', '=', 'teams.country');
            })
             ->leftJoin('stadium', function($join) use($lang) {
                $join->on('stadium.s_id', '=', 'teams.stadium');
            })  
           
            ->leftJoin( DB::raw('(select * from match_info where status = 1 AND match_date >=  NOW() - INTERVAL 2 MONTH  order by match_date ASC ) m'), function($join) use($lang) {
                $join->on('m.team_1','=','teams.id');
                $join->orOn('m.team_2','=','teams.id');
            })
            ->leftJoin('match_settings', function($join) use($lang) {
                            $join->on('match_settings.matches', '=', 'm.m_id');
                         })
            ->whereRaw('FIND_IN_SET(?, match_settings.storefronts)', [$this->store_id])
            ->where('teams.status',1)
            ->where('teams.show_status',1)
            ->where('m.tournament',$tournament_id)
            //  ->where('teams.header_top_teams',1)
            ->take(1000)
            //->having('total_match','>',0)
            ->orderBy("teams_lang.team_name", "asc")
            ->groupBy("teams.id")
            ->leftJoin('banned_countries_tournament as bct', function($join) use($client_country) {
                    $join->on('bct.tournament_id', '=', 'm.tournament')
                     ->where('bct.country_code', '=', $client_country);  
                     })
            ->WhereNull('bct.country_code')
            ->get();

        return response(array("result" => $team_ticket,'teams' => $teams),200);
    }

    public function stadium_details(Request $request){
        
        $lang  = $request->lang ? $request->lang : "en";
        $lang_col = $lang== "ar" ? "_".$lang :"";
        $current_date =  date('Y-m-d');
        $currency  = @$request->currency ? $request->currency : "GBP";
        $client_country  = @$request->client_country ? $request->client_country : "IN";
        $stadium  = @$request->stadium;
        
        $store_id = $this->store_id;

        $stadium_details = SeoStadium::select('seo_venue_list.v_id','seo_venue_list.venue_image','seo_venue_list.status as stadium_status','seo_venue_list.top_venue as top_stadium','seo_venue_list.url_key','seo_venue_list_lang.venue_name','seo_venue_list_lang.page_title','seo_venue_list_lang.meta_description','seo_venue_list_lang.country_content_1 as stadium_content_1','seo_venue_list_lang.country_content_2 as stadium_content_2','seo_venue_list_lang.venue_details','stadium.stadium_image','cities.name as city_name','countries.name as country_name')
            ->leftJoin('seo_venue_list_lang', function($join) use($lang) {
                $join->on('seo_venue_list_lang.venue_id', '=', 'seo_venue_list.v_id')
                ->where('seo_venue_list_lang.language',$lang);
            })
            ->leftJoin('stadium', function($join) use($lang) {
                            $join->on('stadium.s_id', '=', 'seo_venue_list.top_venue');
                         })
            ->leftJoin('cities', function($join) use($lang) {
                            $join->on('cities.id', '=', 'seo_venue_list.venue_city');
                         })
            ->leftJoin('countries', function($join) use($lang) {
                            $join->on('countries.id', '=', 'seo_venue_list.venue_country');
                         })
            ->where('seo_venue_list.url_key',$stadium)
            ->where('seo_venue_list.status',1)
            ->first();


        $other_stadiums =  SeoStadium::select('seo_venue_list.venue_name','seo_venue_list.url_key')
            ->leftJoin('seo_venue_list_lang', function($join) use($lang) {
                $join->on('seo_venue_list_lang.venue_id', '=', 'seo_venue_list.v_id')
                ->where('seo_venue_list_lang.language',$lang);
            })
            ->leftJoin('stadium', function($join) use($lang) {
                            $join->on('stadium.s_id', '=', 'seo_venue_list.v_id');
                         })
            ->where('seo_venue_list.url_key','!=' ,$stadium)
            ->where('seo_venue_list.status',1)
            ->get();

         if(!empty($stadium_details)){

            $stadium_details->venue_image =  IMAGE_PATH."seo_venue/".$stadium_details->venue_image;
            $stadium_details->stadium_image =  STADIUM_IMG."/".ltrim($stadium_details->stadium_image,"/");


            $StadiumDetails  = DB::table('stadium_details')
                                ->select('stadium_details.*','stadium_seats_lang.seat_category')
                                 ->leftJoin('stadium_seats_lang', function($join) use($lang) {
                                    $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_details.category')
                                    ->where('stadium_seats_lang.language',$lang);
                                })
                                ->where('stadium_id',$stadium_details->top_stadium)
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
           
            $stadium_details->map_code =  json_encode($a);


           /* $stadium_details->venue_image =  "https://phpstack-892245-3420498.cloudwaysapps.com/uploads/seo_venue/".$stadium_details->venue_image;
            $stadium_details->stadium_image =  STADIUM_IMG.$stadium_details->stadium_image;
*/
            $stadium_categories =  DB::table('stadium')
                    ->select('stadium.s_id','stadium_seats_lang.seat_category','stadium_details.block_color','stadium_details.active_color')
            ->leftJoin('stadium_details', function($join) use($lang) {
                            $join->on('stadium_details.stadium_id', '=', 'stadium.s_id');
                         })
            ->leftJoin('stadium_seats_lang', function($join) use($lang) {
                $join->on('stadium_seats_lang.stadium_seat_id', '=', 'stadium_details.category')
                ->where('stadium_seats_lang.language',$lang);
            })

            ->where('stadium.s_id',$stadium_details->top_stadium)
            ->where('stadium_details.source_type','1boxoffice')
                    ->orderBy('stadium_seats_lang.seat_category','asc')
                    ->groupBy('stadium_details.category')
            ->get();

            $stadium_ticket  = MatchInfo::select('match_info.m_id','match_info.slug',
                                    'match_info.match_date',
                                    'match_info.match_time',
                                    'match_info.event_type',
                                    'match_info.high_demand',
                                    'match_info_lang.match_name',
                                    'match_info_lang.language',
                                    'match_info_lang.extra_title',
                                    'match_info_lang.event_image',
                                    'match_info.tbc_status',
                                    'team_a.team_image as team_image_a',
                                     'team_a.url_key as team_a_url_key',
                                    'team_b.team_image as team_image_b',
                                    'team_b.url_key as team_b_url_key',
                                    'teams_lang_a.team_name as team_name_a',
                                    'teams_lang_b.team_name as team_name_b',
                                    'tournament_lang.tournament_name',  
                                    'tournament_lang.tournament_id',   
                                    'stadium.stadium_name'.$lang_col." as stadium_name",
                                    'match_info.ignoreautoswitch',
                                    'countries.name as country_name',   
                                    'states.name as state_name',
                                    'cities.name as city_name',
                                    'match_info.oneboxoffice_status',
                                     'match_info.tixstock_status',
                                     'match_info.oneclicket_status',

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
                            ->leftJoin('teams_lang as teams_lang_a', function($join) use ($lang) {
                                $join->on('teams_lang_a.team_id', '=', 'team_a.id')
                                 ->where('teams_lang_a.language',$lang);
                            })
                            ->leftJoin('teams_lang as teams_lang_b', function($join) use ($lang,$store_id){
                                $join->on('teams_lang_b.team_id', '=', 'team_b.id')
                                 ->where('teams_lang_b.language',$lang);
                                 //->where('teams_lang_b.store_id',$store_id);
                            })
                            ->leftJoin('tournament', function($join) {
                                $join->on('tournament.t_id', '=', 'match_info.tournament');
                            })
                            ->leftJoin('tournament_lang', function($join) use($lang) {
                                $join->on('tournament_lang.tournament_id', '=', 'tournament.t_id')
                                    ->where('tournament_lang.language',$lang);
                            })
                             ->leftJoin('stadium', function($join) use($lang) {
                                $join->on('stadium.s_id', '=', 'match_info.venue')
                                   ;
                            })
                            ->leftJoin('match_settings', function($join) use($lang) {
                                $join->on('match_settings.matches', '=', 'match_info.m_id')
                                   ;
                            })
                             ->leftJoin('countries', function($join) use($lang) {   
                                $join->on('countries.id','=','match_info.country'); 
                            })  
                            ->leftJoin('states', function($join) use($lang) {   
                                $join->on('states.id', '=', 'match_info.state') 
                                           ;    
                            })
                            ->leftJoin('cities', function($join) use($lang) {
                                $join->on('cities.id', '=', 'match_info.city')
                                           ;
                            })
                           ->whereRaw('FIND_IN_SET(?, match_settings.storefronts)', [$this->store_id])
                            ->where('match_info.status',1)
                            ->where('match_info.venue',$stadium_details->top_stadium)
                            ->where(function ($query){
                                $query->where('match_info.oneboxoffice_status', '=', "1")
                                    ->orWhere('match_info.tixstock_status', '=', "1")
                                    ->orWhere('match_info.oneclicket_status', '=', "1");
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
                            ->where('match_info.event_type','match')
                            ->where('tournament.status',1)
                           // ->having('match_tixstock_status',1)
                            ->where('match_info_lang.language',$lang)
                            ->where(DB::raw('IF (match_info.source_type = "tixstock", DATE_SUB( match_info.match_date, INTERVAL 1 DAY) , match_info.match_date)'),'>=', current_date())
                            ->groupBy('match_info.m_id')
                            ->limit(10)
                            ->orderBy('match_info.match_date','ASC');
        $stadium_ticket = $stadium_ticket->get();
      
        if($stadium_ticket){

            


            foreach ($stadium_ticket as $key => $value) {
                $now_date =  date('Y-m-d H:i');
                $match_date  = date("Y-m-d H:i",strtotime($value->match_date));
                if($value->ignoreautoswitch  == 0 && $now_date >= $match_date){
                    $price  = array();
                }
                else{

                    $prices = DB::table('sell_tickets')
                    ->select('sell_tickets.price','sell_tickets.price_type','sell_tickets.add_by',DB::raw('SUM(sell_tickets.quantity) as total_quantity'))
                    ->leftJoin('match_info', function($join) {
                                $join->on('sell_tickets.match_id', '=', 'match_info.m_id');
                               
                            })
                    ->where('sell_tickets.match_id',$value->m_id)
                    ->where('sell_tickets.quantity','>',0)
                    ->where('sell_tickets.status',1)
                    ->where('sell_tickets.price','>',0)
                    ->groupBy('sell_tickets.match_id')
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

                   
                    $prices = $prices->whereIn('sell_tickets.source_type',$price_source_type);

                    $prices = $prices->first();
                } 
                $stadium_ticket[$key]->price_source_type = $value->oneboxoffice_status." ".$value->tixstock_status." ".$value->oneclicket_status;
                $stadium_ticket[$key]->total_quantity = @$prices->total_quantity ? $prices->total_quantity : 0 ;
                $stadium_ticket[$key]->request_type = @$prices->price ? "book" : "request";

                $seller_id = @$prices->add_by; 
                $seller_markup = @$this->markup;

                if(@$prices->add_by != ""){

                 $seller_markup = get_markup($seller_id,$this->store_id, @$this->markup);
                }

                $vprice = @$prices->price ;
                $price_type =  @$prices->price_type;
                $total_price = "";
                if(@$vprice){
                    $vprice = get_percentage($vprice,$seller_markup);
                    $total_price =  get_currency($price_type,$currency,$vprice,0);
                    $total_price_sym =  get_currency($price_type,$currency,$vprice,1);
                }
                $stadium_ticket[$key]->min_price =  @$total_price ? $total_price : "";
                $stadium_ticket[$key]->ticket_price =  @$total_price ? $total_price : "";

                $stadium_ticket[$key]->currency_code =  @$total_price ? trim($currency) : "";
                $stadium_ticket[$key]->currency_symbol =  @$total_price ? get_symbol($currency) : "" ;
                $stadium_ticket[$key]->price_with_symbol =  @$total_price ? $total_price_sym : "" ;
                $stadium_ticket[$key]->view_now = rand(100,500);

               // $top_matchs[$key]->price = 5000;
                $stadium_ticket[$key]->match_date  = date("d F Y",strtotime($value->match_date));
                $stadium_ticket[$key]->match_day = date("d",strtotime($value->match_date));    
                $stadium_ticket[$key]->match_month  = date("M",strtotime($value->match_date)); 
                $stadium_ticket[$key]->match_year  = date("Y",strtotime($value->match_date));
                $stadium_ticket[$key]->team_image_a =  IMAGE_PATH."teams/".$value->team_image_a;
                $stadium_ticket[$key]->team_image_b =  IMAGE_PATH."teams/".$value->team_image_b;
                if($value->tbc_status == 1){    
                    $stadium_ticket[$key]->match_date = tbc;   
                    $stadium_ticket[$key]->match_time = "";    
                 }
            }
        }

         }
          return response(array("stadium_details" => @$stadium_details,"other_stadiums" => @$other_stadiums,"stadium_categories" => @$stadium_categories,"stadium_tickets" => @$stadium_ticket),200);
       
        
      }


}
