<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use Session;
use Hash;
use DB;


class ApiController extends Controller
{
    //

    private $image_url ;

    public function __construct()
    {       
        $this->image_url = url('image');
    }



    public function projects(Request $request)
    {
        $limit = $request->limit ?  $request->limit : 10;
        $page = $request->page ? (($request->page - 1) * $limit) : 0;
        $projects = Project::select('projects.*',DB::raw('CONCAT("'.$this->image_url.'/project/", project_image) as project_image '),'categories.category_name')
                            ->leftJoin('categories', 'categories.id', '=', 'projects.category_id')
                            ->where('projects.status',1)
                            ->where('categories.status',1);
        $all_count = $projects->count();    

        $projects = $projects->skip($page)->take($limit)


                            ->get();
        return response(array("results" => $projects,'count' => $all_count,'status' => 1),200);
    }


    public function clients(Request $request)
    {
        $limit = $request->limit ?  $request->limit : 10;
        $page = $request->page ? (($request->page - 1) * $limit) : 0;


        $clients = Client::select('clients.*',DB::raw('CONCAT("'.$this->image_url.'/clients/", client_image) as client_image ') )
                            ->where('clients.status',1);
        $all_count = $clients->count();    

        $clients = $clients->skip($page)->take($limit)->get();


        return response(array("results" => $clients,'count' => $all_count,'status' => 1),200);
    }


}
