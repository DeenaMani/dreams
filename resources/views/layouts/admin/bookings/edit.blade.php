@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Edit Instructor</h3>
                        </div>
                    </div>
                </div>
            </div>
       </div>

        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card ">
                    <div class="card-body">
                         @include('layouts.partials.messages')
                        <form action="{{ url('admin/instructor/'.$instructor->id) }}" method="post" enctype="multipart/form-data" >
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="instructor_image"> Instructor Image </label>
                                    <input type="file" class="form-control mb-3" name="instructor_image" value="{{$instructor->instructor_image}}">
                                    <img src="{{url('public/image/instructor/'.$instructor->instructor_image)}}" width="50px"> <br>
                                </div>  

                                <div class="col-lg-6">
                                    <label for="instructor_name"> Instructor Name </label>
                                    <input type="text" class="form-control mb-3" name="instructor_name" value="{{$instructor->instructor_name}}" required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="subject"> Subject </label>
                                    <input type="text" class="form-control mb-3" name="subject" value="{{$instructor->subject}}" required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="skills"> Skills </label>
                                    <input type="text" class="form-control mb-3" name="skills" value="{{$instructor->skills}}">
                                </div>

                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="email"> E Mail </label>
                                            <input type="text" class="form-control mb-3" name="email" value="{{$instructor->email}}" required>
                                        </div>

                                        <div class="col-lg-12">
                                            <label for="phone">  Phone Number </label>
                                            <input type="text" class="form-control mb-3" name="phone" value="{{$instructor->phone}}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="about">  About </label>
                                    <textarea type="text" class="form-control mb-3" name="about" rows="5">{{$instructor->about}}</textarea>
                                </div>
                               
                                <div class="col-lg-12 p-3 ">
                                    <input type="submit" class="btn btn-primary text-white" style="float:right;" value="Save instructor">
                                </div>
                            </div>                                 
                        </form>
                    </div>
                </div> 
            </div> 
        </div>

</div> 
 @endsection