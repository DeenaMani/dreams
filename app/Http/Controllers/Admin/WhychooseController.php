<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Whychoose;
class WhychooseController extends Controller
{
       /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = Whychoose::all();
        
        return view ('admin.whychoose.index',compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('admin.whychoose.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate ($request,[
            'whychoose_image' => 'required',
            'whychoose_description' => 'required',
        ]);

        $data = $request ->all();

        if ($request->hasfile('whychoose_image'))
        {  //check file present or not
            $images = $request->file('whychoose_image'); //get the file
            $image = time().'.'.$images->getClientOriginalExtension();
            $destinationPath = public_path('/image/whychoose'); //public path folder dir
            $images->move($destinationPath, $image);  //mve to destination you mentioned 
            $data['whychoose_image']=$image;
        } 

        $whychoose = new whychoose;
        $whychoose = Whychoose::create($data);

        return redirect('admin/whychoose')->with('success',"Added Successfully");;
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
        $whychoose = Whychoose::find($id);
        return view ('admin.whychoose.edit',compact('whychoose','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate ($request, [
            'whychoose_description' => 'required',
         ]);
 
         $data = $request ->all();

         if ($request->hasfile('whychoose_image'))
         {  //check file present or not
             $images = $request->file('whychoose_image'); //get the file
             $image = time().'.'.$images->getClientOriginalExtension();
             $destinationPath = public_path('/image/whychoose'); //public path folder dir
             $images->move($destinationPath, $image);  //mve to destination you mentioned 
             $data['whychoose_image']=$image;
         } 

         
         $whychoose =Whychoose::find($id);
         $whychoose -> update($data);
 
         return redirect ('admin/whychoose')->with('success','Save Changes Successfully..');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $whychoose=Whychoose::find($id);
        $whychoose -> delete();

        return redirect ('admin/whychoose')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $faqs = Whychoose::find($id);
        $faqs->status =   $status ? $status : 0;
        $faqs->save();
        echo json_encode(array("status" => 1));
    }

}
