@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Add Students</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h4>Add Students</h4>
                    </div>
                    <div class="card-body text-dark">
                         @include('layouts.partials.messages')
                        <form action="{{ url('admin/instructor') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="student_image"> Students Image </label>
                                    <input type="file" class="form-control mb-3" name="student_image" >
                                </div>  

                                <div class="col-lg-6">
                                    <label for="student_name"> Students Name </label>
                                    <input type="text" class="form-control mb-3" name="student_name" required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="email"> email Number </label>
                                    <input type="text" class="form-control mb-3" name="email" required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="password"> Password </label>
                                    <input type="text" class="form-control mb-3" name="password" required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="c_password"> Confirm Password </label>
                                    <input type="text" class="form-control mb-3" name="c_password" required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="phone"> Phone Number </label>
                                    <input type="text" class="form-control mb-3" name="phone" required>
                                </div>

                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="email"> E Mail </label>
                                            <input type="text" class="form-control mb-3" name="email" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="address_lane1"> Address Lane 1 </label>
                                    <input class="form-control" type="text" name="address_lane1" value="">
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="address_lane2"> Address Lane 2 </label>
                                    <input class="form-control" type="text" name="address_lane2" value="">
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="state"> State </label>
                                    <select id="state" name="state" class="form-select form-control" value="">
                                        <option value="" disabled="" selected="">Select State</option>
                                        <option value="">Tamil Nadu</option>
                                        <option value="">Karnataka</option>
                                        <option value="">Kerala</option>
                                    </select>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="city"> City </label>
                                    <select id="state" name="state" class="form-select form-control" value="">
                                        <option value="" disabled="" selected="">Select State</option>
                                        <option value="">Tamil Nadu</option>
                                        <option value="">Karnataka</option>
                                        <option value="">Kerala</option>
                                    </select>
                                </div>

                                <div class="col-lg-12 mb-3">
                                    <label for="post_code"> Post Code </label>
                                    <input class="form-control" type="text" name="post_code" value="">
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
        