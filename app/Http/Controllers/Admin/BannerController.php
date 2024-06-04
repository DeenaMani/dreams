<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Banner::all();
        
        return view ('admin.banner.index',compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate ($request,[
            'banner_title' => 'required',
            'banner_image' => 'required',
            'banner_description' => 'required',
        ]);

        $data = $request ->all();

        if ($request->hasfile('banner_image'))
        {  //check file present or not
            $images = $request->file('banner_image'); //get the file
            $image = time().'.'.$images->getClientOriginalExtension();
            $destinationPath = public_path('/image/banner'); //public path folder dir
            $images->move($destinationPath, $image);  //mve to destination you mentioned 
            $data['banner_image']=$image;
        } 

        $banner = new Banner;
        $banner = Banner::create($data);

        return redirect('admin/banner');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $banner = Banner::find($id);
        return view ('admin.banner.edit',compact('banner','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate ($request, [
            'banner_title' => 'required',
            'banner_description' => 'required',
         ]);
 
         $data = $request ->all();

         if ($request->hasfile('banner_image'))
         {  //check file present or not
             $images = $request->file('banner_image'); //get the file
             $image = time().'.'.$images->getClientOriginalExtension();
             $destinationPath = public_path('/image/banner'); //public path folder dir
             $images->move($destinationPath, $image);  //mve to destination you mentioned 
             $data['banner_image']=$image;
         } 

         
         $banner =Banner::find($id);
         $banner -> update($data);
 
         return redirect ('admin/banner')->with('success',"updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner=Banner::find($id);
        $banner -> delete();

        return redirect ('admin/banner')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $banner = Banner::find($id);
        $banner->status =   $status ? $status : 0;
        $banner->save();
        echo json_encode(array("status" => 1));
    }
}
