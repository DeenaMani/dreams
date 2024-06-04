<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = Faq::all();
        return view ('admin.faqs.index',compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.faqs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate($request, [
            'faq_title' => 'required',
            'faq_description' => 'required',
        ]);

        $data = $request->all();

        if ($request ->hasfile('faq_image'))
        { //check if the file present or not
            $images = $request->file('faq_image'); //get the file
            $image = time().'.'.$images->getClientOriginalExtension();//get the file extension
            $destinationpath = public_path('/image/faqs');//public path folder dir
            $images -> move($destinationpath,$image); //move to destination you menstioned
            $data['faq_image'] = $image;
        }

        $faq = new Faq;
        $faq = Faq::create($data);

        return redirect('admin/faq')->with('success',"Added Successfully");
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
        $faq = Faq::find($id);
        return view ('admin.faqs.edit',compact('faq','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this -> validate($request, [
            'faq_title' => 'required',
            'faq_description' => 'required',
        ]);

        $data = $request->all();

        if ($request ->hasfile('faq_image'))
        { //check if the file present or not
            $images = $request->file('faq_image'); //get the file
            $image = time().'.'.$images->getClientOriginalExtension();//get the file extension
            $destinationpath = public_path('/image/faqs');//public path folder dir
            $images -> move($destinationpath,$image); //move to destination you menstioned
            $data['faq_image'] = $image;
        }

        $faq = Faq::find($id);
        $faq -> update($data);

        return redirect('admin/faq')->with('success',"Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $faq=Faq::find($id);
        $faq -> delete();

        return redirect('admin/faq')->with('error',"Deleted Successfully");
    }

    public function status($id,$status)
    {
        //echo $id." ".$status;
        $faqs = Faq::find($id);
        $faqs->status =   $status ? $status : 0;
        $faqs->save();
        echo json_encode(array("status" => 1));
    }
}
