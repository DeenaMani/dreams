@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Edit Students</h3>
                        </div>
                    </div>
                </div>
            </div>
       </div>

        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card ">
                    <div class="card-header">
                    <h4>Edit Students</h4>
                    </div>
                    <div class="card-body">
                         @include('layouts.partials.messages')
                        <form action="{{ url('admin/student/'.$student->id) }}" method="post" enctype="multipart/form-data" >
                            @csrf
                            @method('PUT')
                              <input type="password" class="form-control mb-3" name="password" value="{{$student->password}}" hidden>
                       
                            <div class="row">

                                <div class="col-lg-6">
                                    <label for="first_name"> First Name </label>
                                    <input type="text" class="form-control mb-3" name="first_name" value="{{$student->first_name}}">
                                </div>

                                <div class="col-lg-6">
                                    <label for="last_name"> Last Name </label>
                                    <input type="text" class="form-control mb-3" name="last_name" value="{{$student->last_name}}">
                                </div>

                                <div class="col-lg-6">
                                    <label for="phone"> Phone Number </label>
                                    <input type="text" class="form-control mb-3" name="phone" value="{{$student->phone}}">
                                </div>

                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="email"> E Mail </label>
                                            <input type="text" class="form-control mb-3" name="email" value="{{$student->email}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="address"> Address</label>
                                    <input class="form-control" type="text" name="address" value="{{$student->address}}">
                                </div>


                                <div class="col-lg-6 mb-3">
                                    <label for="state"> State </label>
                                    <select id="state" name="state" class="form-select form-control">
                                        <option value="" disabled="" selected="">Select State</option>
                                        @foreach ($states as $state)
                                        <option value="{{$state->id}}" {{$student->state ==  $state->id ? "selected" :"" }}>{{$state->state_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="city"> City </label>
                                    <input id="city" name="city" class="form-select form-control" value="{{$student->city}}">
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="pincode"> Post Code </label>
                                    <input class="form-control" type="text" name="pincode" value="{{$student->pincode}}">
                                </div>

                                <div class="col-lg-12 p-3 ">
                                    <input type="submit" class="btn btn-primary text-white" style="float:right;" value="Update Student">
                                </div>
                            </div>                                
                        </form>
                    </div>
                </div> 
            </div> 
        </div>

</div> 
 @endsection