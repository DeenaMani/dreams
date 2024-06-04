@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Setting</h3>
                        </div>
                    </div>
                </div>
            </div>
       </div>

        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card ">
                    <div class="card-header">
                            <div>
                                @if(session()->has('message'))
                                <div class="alert alert-success" id="alert">
                                    <button type="button" class="close" data-dismiss="alert"> X </button>
                                    {{session()->get('message')}}
                                </div>
                                @endif
                            </div>
                        <h4>Edit Setting</h4>
                                            
                    </div>
                    <div class="card-body">
                         @include('layouts.partials.messages')
                         <form action="{{ url('admin/setting/'.$setting->id) }}" method="post" enctype="multipart/form-data" >
                            @csrf
                            @method('PUT')
                        <div class="row">
                        <div class="col-lg-6">

                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="company_name"> Company Name </label>
                                    <input type="Text" class="form-control mb-4" name="company_name" value="{{$setting->company_name}}" required> 
                                </div>
                            </div>
                             
                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="logo"> Logo </label>
                                    <input type="file" class="form-control mb-3" name="logo" value="{{$setting->logo}}">
                                    <img src="{{url('public/image/setting/'.$setting->logo)}}" height="50px"> <br>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="fav_icon"> Fav Icon </label>
                                    <input type="file" class="form-control mb-3" name="fav_icon" value="{{$setting->fav_icon}}">
                                <img src="{{url('public/image/setting/'.$setting->fav_icon)}}" height="50px"> <br>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="phone"> phone </label>
                                    <input type="tel" class="form-control mb-4" name="phone" value="{{$setting->phone}}" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="phone_2"> phone 2 </label>
                                    <input type="tel" class="form-control mb-4" name="phone_2" value="{{$setting->phone_2}}" required>
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="email"> E Mail </label>
                                    <input type="email" class="form-control mb-4" name="email" value="{{$setting->email}}" required>
                                </div>
                            </div>
                            
                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="email_2"> Sales E Mail </label>
                                    <input type="email" class="form-control mb-4" name="email_2" value="{{$setting->email_2}}" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="currency"> Currency </label>
                                    <input type="text" class="form-control mb-4" name="currency" value="{{$setting->currency}}" required>
                                </div>
                            </div>
                            
                        </div>

                        <div class="col-lg-6">

                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="email_3">Career E Mail </label>
                                    <input type="email" class="form-control mb-4" name="email_3" value="{{$setting->email_3}}" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="address"> Address </label>
                                    <textarea type="text" class="form-control mb-4" name="address" rows="4" required> {{$setting->address}}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="google_map_linik"> G-Map </label>
                                    <input type="text" class="form-control mb-4" name="google_map_link" value="{{$setting->google_map_link}}" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="youtube"> youtube_link </label>
                                    <input type="text" class="form-control mb-4" name="youtube" value="{{$setting->youtube}}" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="twitter"> Twitter_link </label>
                                    <input type="text" class="form-control mb-4" name="twitter" value="{{$setting->twitter}}" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="insta"> Instagram_link </label>
                                    <input type="text" class="form-control mb-4" name="insta" value="{{$setting->insta}}" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="facebook"> Facebook_link </label>
                                    <input type="text" class="form-control mb-4" name="facebook" value="{{$setting->facebook}}" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="row">
                                    <label for="linked_in"> linked_in_link </label>
                                    <input type="text" class="form-control mb-4" name="linked_in" value="{{$setting->linked_in}}" required>
                                </div>
                            </div>


                                <input type="submit" class="btn btn-primary text-white mx-5 mb-0" style="float:right;" value="Update setting">
                        </div>
                        </div>
                               

                            
                        </form>
                    </div>
                </div> 
            </div> 
        </div>

</div> 
 @endsection