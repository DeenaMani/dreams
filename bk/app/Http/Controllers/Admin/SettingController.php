<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{

    public function store (Request $request) 
    {
        $this -> validate($request, [
            'company_name' => 'required',
            'logo'=> 'required',
            'fav_icon' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address'=> 'required'
        ]);

        $data = $request->all();

        if ($request->hasFile('logo'))
        {
            //check the file present or not
            $logos = $request->file('logo'); //get the file
            $logo = time().'.'.$logos->getClientOriginalExtension();//get the file extension
            $destinationpath = public_path('/image/setting_logo');//public path folder dir
            $logos -> move($destinationpath,$logo); //mv to destination you mensioned
            $data['logo'] = $logo;
        }
        if ($request->hasFile('fav_icon'))
        {
            //check the file present or not
            $logos = $request->file('fav_icon'); //get the file
            $logo = time().'.'.$logos->getClientOriginalExtension();//get the file extension
            $destinationpath = public_path('/image/setting_fav_icon');//public path folder dir
            $logos -> move($destinationpath,$logo); //mv to destination you mensioned
            $data['fav_icon'] = $logo;
        }

        $setting = new Setting;
        $setting = Setting::create($data);

        return redirect ('admin/setting');
    }

    public function edit ($id)
    {
        $setting = Setting::find(1);
        return view ('admin.setting.edit',compact('setting'));
    }

    public function update(Request $request,$id)
    {
        $this -> validate($request, [
            'company_name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address'=> 'required',
            'youtube'=>'required',
            'twitter'=>'required',
            'insta'=>'required',
            'facebook'=>'required',
            'linked_in'=>'required',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo'))
        {
            //check the file present or not
            $logos = $request->file('logo'); //get the file
            $logo = "logo-".time().'.'.$logos->getClientOriginalExtension();//get the file extension
            $destinationpath = public_path('/image/setting');//public path folder dir
            $logos -> move($destinationpath,$logo); //mv to destination you mensioned
            $data['logo'] = $logo;
        }
        
        if ($request->hasFile('fav_icon'))
        {
            //check the file present or not
            $logos = $request->file('fav_icon'); //get the file
            $logo = "fav-icon-".time().'.'.$logos->getClientOriginalExtension();//get the file extension
            $destinationpath = public_path('/image/setting');//public path folder dir
            $logos -> move($destinationpath,$logo); //mv to destination you mensioned
            $data['fav_icon'] = $logo;
        }
        if ($request->hasFile('contact_image'))
        {
            //check the file present or not
            $logos = $request->file('contact_image'); //get the file
            $logo = "contact-image".time().'.'.$logos->getClientOriginalExtension();//get the file extension
            $destinationpath = public_path('/image/setting');//public path folder dir
            $logos -> move($destinationpath,$logo); //mv to destination you mensioned
            $data['contact_image'] = $logo;
        }

        $setting = Setting::find(1);
        $setting -> update($data);
        Cache::flush();
        return redirect ('admin/setting/'.$id."/edit")->with('success','Save Changes Successfully..');
    }

} 
