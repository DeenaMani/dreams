@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Contact Form</h3>
                        </div>
                    </div>
                </div>
            </div>
       </div>
    
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card ">
                    <div class="card-header">
                        <h4>Contact Form id = {{$result->id}}</h4>                   
                    </div>

                    <div class="card-body text-dark">

                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-bordered  ">
                                    <tr>
                                        <th width="200">First Name</th>
                                        <td>{{$result->first_name}}</td>
                                    </tr>
                                    </tr>
                                    <tr>
                                        <th width="200">Last Name</th>
                                        <td>{{$result->last_name}}</td>
                                    </tr>
                                    <tr>
                                        <th width="200">E Mail</th>
                                        <td>{{$result->email}}</td>
                                    </tr>
                                    <tr>
                                        <th width="200">Phone No</th>
                                        <td>{{$result->mobile}}</td>
                                    </tr>
                                    <tr>
                                        <th width="200">Date</th>
                                        <td>{{$result->created_at}}</td>
                                    </tr>
                                    <tr>
                                        <th width="200">Message</th>
                                        <td>{{$result->message}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-12">
                                <a class="btn btn-primary text-white" href="{{url('/admin/contactform')}}"><i class="fa fa-arrow-left"></i> Go Back</a>
                            </div>
                        </div>
                        
                    </div>
                </div> 
            </div> 
        </div>

</div> 
 @endsection